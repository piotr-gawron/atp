<?php
	class UpdateEventDao {
		private $table = "atp_update_event";
		public function __construct($link) {
			$this->link = $link;
		}
		public function getAll() {
			$query = "SELECT * FROM ".$this->table." ORDER BY id";
			$dbRes=mysqli_query($this->link, $query);
			$result = array();
			while ($row=mysqli_fetch_array($dbRes)) {
				$result []= new UpdateEvent($row);
			}
			return $result;
		}
		public function getLast() {
			$query = "SELECT * FROM ".$this->table." ORDER BY id desc limit 1";
			$dbRes=mysqli_query($this->link, $query);
			while ($row=mysqli_fetch_array($dbRes)) {
				return new UpdateEvent($row);
			}
			return null;
		}
		public function add($certificate) {
			$query = "INSERT INTO ".$this->table." (date) values (";
			$query .= $this->prepareDate($certificate->getDate())." ";
			$query .= ");" ;
			mysqli_query($this->link, $query);
		}

		public function clear() {
			$query="DELETE FROM ".$this->table.";";
			mysqli_query($this->link, $query);
		}

		protected function prepareDate($str) {
			if ($str ==null) {
				return "null";
			} else {
				$result = date("Y-m-d H:i:s", strtotime($str));
				return " '$result' ";
			}
		}
	}
?>
