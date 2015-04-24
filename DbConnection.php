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
			mysql_connect('localhost', $this->user, $this->password);
			@mysql_select_db($this->database) or die("Problem z polaczeniem z baza danych.");

			$this->certificateDao = new CertificateDao();
			$this->userDao = new UserDao();
			$this->updateEventDao = new UpdateEventDao();
			$this->logEventDao = new LogEventDao();
		}
		function closeDbConnection() {
			mysql_close();
		}
	}
?>
