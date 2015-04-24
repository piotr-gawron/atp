<?php
	class UpdateEventDao {
		private $table = "atp_update_event";
		public function getAll() {
			$query = "SELECT * FROM ".$this->table." ORDER BY id";
			$dbRes=mysql_query($query);
			$result = array();
			while ($row=mysql_fetch_array($dbRes)) {
				$result []= new UpdateEvent($row);
			}
			return $result;
		}
		public function getLast() {
			$query = "SELECT * FROM ".$this->table." ORDER BY id desc limit 1";
			$dbRes=mysql_query($query);
			while ($row=mysql_fetch_array($dbRes)) {
				return new UpdateEvent($row);
			}
			return null;
		}
		public function add($certificate) {
			$query = "INSERT INTO ".$this->table." (date) values (";
			$query .= $this->prepareDate($certificate->getDate())." ";
			$query .= ");" ;
			mysql_query($query);
		}

		public function clear() {
			$query="DELETE FROM ".$this->table.";";
			mysql_query($query);
		}

		protected function prepareDate($str) {
			if ($str ==null) {
				return "null";
			} else {
				$result = date("Y-m-d H:i:s", strtotime($str));
				return " '$result' ";
			}
		}
		protected function prepareStr($str) {
			if ($str ==null) {
				return "null";
			} else {
				return " '".mysql_real_escape_string($str)."' ";
			}
		}
	}
?>
