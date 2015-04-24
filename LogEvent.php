<?php
	/**
	 * Clas containing log event information.
	 */
	class LogEvent {
		private $id;
		private $date;
		private $notes;
		public function __construct($row = null) {
			if ($row!=null) {
				$this->id = $row['id'];
				$this->date = $row['date'];
				$this->notes = $row['notes'];
			}
		}

		public function getId(){
			return $this->id;
		}
		public function setDate($date){
			$this->date=$date;
		}
		public function getDate(){
			return $this->date;
		}
		public function setNotes($notes){
			$this->notes=$notes;
		}
		public function getNotes(){
			return $this->notes;
		}
	}
?>
