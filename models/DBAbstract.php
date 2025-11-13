<?php 


	/**
	 * 
	 */
	class DBAbstract
	{

		private $db;
		
		function __construct()
		{
			$this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		}


		/* solo funciona para hacer select a futuro lo haremos para todas las dml: SELECT, INSERT, UPDATE, DELETE */
		public function consultar($sql){

			$response = $this->db->query($sql);

			return $response->fetch_all(MYSQLI_ASSOC);
		}
	}


 ?>