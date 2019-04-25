<?php
	class UserDao {
		private $table = 'atp_user_table';

		public function __construct($link) {
			$this->link = $link;
		}

		public function getUserByLoginPassword($login, $password) {
			$escapedLogin = mysqli_real_escape_string ($this->link, $login);
			$cryptedPassword = md5($password);
			$query = "SELECT * FROM ".$this->table." WHERE login = '".$escapedLogin."'";
			$query.= " AND password = '".$cryptedPassword."'";
			$dbRes=mysqli_query($this->link, $query);
			$result = array();
			while ($row=mysqli_fetch_array($dbRes)) {
				return new User($row);
			}
			return null;
		}
	}
?>
