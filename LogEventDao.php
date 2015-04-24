<?php
	class LogEventDao {
		private $table = 'atp_log_event';
    public function add($event) {
      $query = "INSERT INTO ".$this->table." (date, notes) values (";
      $query .= $this->prepareDate($event->getDate()).", ";
      $query .= $this->prepareStr($event->getNotes())." ";
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

