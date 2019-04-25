<?php
	class LogEventDao {
		private $table = 'atp_log_event';
		public function __construct($link) {
			$this->link = $link;
		}
    public function add($event) {
      $query = "INSERT INTO ".$this->table." (date, notes) values (";
      $query .= $this->prepareDate($event->getDate()).", ";
      $query .= $this->prepareStr($event->getNotes())." ";
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
    protected function prepareStr($str) {
      if ($str ==null) {
        return "null";
      } else {
        return " '".mysqli_real_escape_string($this->link, $str)."' ";
      }
    }

	}
?>
