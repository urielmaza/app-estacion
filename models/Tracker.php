<?php

class Tracker extends DBAbstract {
    function __construct(){
        parent::__construct();
    }

    /**
     * Devuelve ip (única), latitud, longitud y cantidad de accesos por IP
     * Para lat/long se toma el último registro (mayor id_tracker) por IP.
     */
    public function listClientsLocation(){
        $sql = "SELECT t.ip, t.latitud, t.longitud, stats.accesos\n                FROM (\n                  SELECT ip, MAX(id_tracker) AS last_id, COUNT(*) AS accesos\n                  FROM tracker\n                  GROUP BY ip\n                ) AS stats\n                INNER JOIN tracker t ON t.id_tracker = stats.last_id";
        $rows = $this->consultar($sql);
        // Normalizar tipos (lat/long números si vienen como string)
        foreach($rows as &$r){
            if(isset($r['latitud'])) $r['latitud'] = is_numeric($r['latitud']) ? (float)$r['latitud'] : $r['latitud'];
            if(isset($r['longitud'])) $r['longitud'] = is_numeric($r['longitud']) ? (float)$r['longitud'] : $r['longitud'];
            if(isset($r['accesos'])) $r['accesos'] = (int)$r['accesos'];
        }
        return $rows;
    }

    private function esc($v){ return addslashes($v); }

    private function genToken(){
        try { return bin2hex(random_bytes(16)); } catch (\Throwable $e) { return uniqid('trk_', true); }
    }

    /** Inserta un registro en tracker */
    public function insertVisit($ip,$lat,$lng,$pais,$navegador,$sistema){
        $token = $this->esc($this->genToken());
        $ipEsc = $this->esc($ip);
        $latVal = is_numeric($lat) ? (float)$lat : 'NULL';
        $lngVal = is_numeric($lng) ? (float)$lng : 'NULL';
        $paisEsc = $this->esc($pais);
        $navEsc = $this->esc($navegador);
        $sisEsc = $this->esc($sistema);
        $latSql = ($latVal==='NULL')? 'NULL' : (string)$latVal;
        $lngSql = ($lngVal==='NULL')? 'NULL' : (string)$lngVal;
        $sql = "INSERT INTO tracker (token, ip, latitud, longitud, pais, navegador, sistema, add_date) VALUES ('{$token}', '{$ipEsc}', {$latSql}, {$lngSql}, '{$paisEsc}', '{$navEsc}', '{$sisEsc}', NOW())";
        return $this->ejecutar($sql);
    }

    /** Detección simple de SO y navegador a partir del User-Agent */
    public static function parseUA($ua){
        $os = 'Desconocido';
        $uaL = $ua;
        if (stripos($uaL,'Windows')!==false) $os='Windows';
        elseif (stripos($uaL,'Android')!==false) $os='Android';
        elseif (stripos($uaL,'iPhone')!==false || stripos($uaL,'iPad')!==false) $os='iOS';
        elseif (stripos($uaL,'Mac')!==false) $os='macOS';
        elseif (stripos($uaL,'Linux')!==false) $os='Linux';

        $nav = 'Navegador';
        foreach(['Chrome','Firefox','Safari','Edge','Opera'] as $n){ if(stripos($uaL,$n)!==false){ $nav=$n; break; } }
        return [$os,$nav];
    }

    /** Consulta ipwho.is; retorna [lat,long,pais] o [null,null,''] */
    public static function enrichByIP($ip){
        $lat = null; $lng = null; $pais = '';
        if (!$ip || $ip==='127.0.0.1' || $ip==='::1') return [$lat,$lng,$pais];
        $url = 'http://ipwho.is/'.urlencode($ip);
        try {
            $ctx = stream_context_create(['http'=>['timeout'=>2]]);
            $resp = @file_get_contents($url,false,$ctx);
            if($resp){
                $json = json_decode($resp,true);
                if(is_array($json)){
                    $lat = isset($json['latitude']) ? $json['latitude'] : $lat;
                    $lng = isset($json['longitude']) ? $json['longitude'] : $lng;
                    $pais = isset($json['country']) ? $json['country'] : $pais;
                }
            }
        } catch(\Throwable $e){ /* silencioso */ }
        return [$lat,$lng,$pais];
    }
}

?>
