<?php
	class UserDao {
		private $table = 'user_table';

		public function getUserByLoginPassword($login, $password) {
			$escapedLogin = mysql_real_escape_string ($login);
			$cryptedPassword = md5($password);
			$query = "SELECT * FROM ".$this->table." WHERE login = '".$escapedLogin."'";
			$query.= " AND password = '".$cryptedPassword."'";
			$dbRes=mysql_query($query);
			$result = array();
			while ($row=mysql_fetch_array($dbRes)) {
				return new User($row);
			}
			return null;
		}
	}
?>

