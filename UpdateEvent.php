<?php
	class UpdateEvent{
		private $id;
		private $date;
		public function __construct($row = null) {
			if ($row!=null) {
				$this->assignDbValues($row);
			}
		}
		private function assignDbValues($row) {
			$this->id = $row['id'];
			$this->date = $row['date'];
		}

		public function getId(){
			return $this->id;
		}
		public function getDate(){
			return $this->date;
		}
		public function setDate($val){
			$this->date = $val;
		}
	}
?>
