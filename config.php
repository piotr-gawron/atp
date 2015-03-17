<?php
	include_once "Certificate.php";
	include_once "CertificateDao.php";
	include_once "DbConnection.php";
	include_once "InvalidDateFormatException.php";
	include_once "UpdateEvent.php";
	include_once "UpdateEventDao.php";
	include_once "User.php";
	include_once "UserDao.php";

	if (session_status() == PHP_SESSION_NONE) {
	  session_start();
	}

	class Config {
		private $user = 'atp_user';
		private $password = 'atp_passwd';	
		private $database = 'atp_db';
		private $title = "Baza ATP";

		public function __construct() {
		}

		public function getUser() {
			return $this->user;
		}
		public function getPassword() {
			return $this->password;
		}
		public function getDatabase() {
			return $this->database;
		}
		public function getTitle() {
			return $this->title;
		}
	}
?>
	
