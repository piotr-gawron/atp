<?php
	class DbConnection {
		private $user;
		private $password;
		private $database;

		public $certificateDao;
		public $userDao;
		public $updateEventDao;
		public $logEventDao;

		public function __construct($config) {
			$this->user = $config->getUser();
			$this->password = $config->getPassword();
			$this->database = $config->getDatabase();
		}
		function openDbConnection(){
			$this->link = mysqli_connect('localhost', $this->user, $this->password, $this->database) or die("Problem z polaczeniem z baza danych.");

			$this->certificateDao = new CertificateDao($this->link);
			$this->userDao = new UserDao($this->link);
			$this->updateEventDao = new UpdateEventDao($this->link);
			$this->logEventDao = new LogEventDao($this->link);
		}
		function closeDbConnection() {
			mysqli_close($this->link);
		}
	}
?>
