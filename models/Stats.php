<?php

class Stats extends DBAbstract {
    // Cantidad de administradores (usuarios con flag is_admin=1)
    public function countAdmins(){
        $r = $this->consultar("SELECT COUNT(*) AS c FROM clienteAppEstacion WHERE is_admin=1");
        return isset($r[0]['c']) ? (int)$r[0]['c'] : 0;
    }
    // Cantidad de clientes normales (is_admin=0)
    public function countNormalClients(){
        $r = $this->consultar("SELECT COUNT(*) AS c FROM clienteAppEstacion WHERE is_admin=0");
        return isset($r[0]['c']) ? (int)$r[0]['c'] : 0;
    }
    // (Métodos anteriores podrían mantenerse si hicieran falta, pero redefinimos según requerimiento)
}

?>
