<?php
	/**
	 * Clas containing information about users.
	 */
	class User {
		private $id;
		private $login;
		private $cryptedPassword;
		public function __construct($row) {
			$this->id = $row['id'];
			$this->login = $row['login'];
			$this->cryptedPassword = $row['password'];
		}

		public function getId(){
			return $this->id;
		}
		public function getLogin(){
			return $this->login;
		}
		public function getCryptedPassword(){
			return $this->cryptedPassword;
		}
	}
?>
