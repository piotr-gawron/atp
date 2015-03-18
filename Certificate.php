<?php
	/**
	 * Class containing information about certificate.
	 *
	 */
	class Certificate{
		private $id;
		private $certificateNumber;
		private $buildingNumber;
		private $vin;
		private $expireDate;
		private $symbol;
		public function __construct($row = null) {
			if ($row!=null) {
				$this->assignDbValues($row);
			}
		}
		private function assignDbValues($row) {
			$this->id = $row['id'];
			$this->certificateNumber = $row['certificate_number'];
			$this->buildingNumber = $row['building_number'];
			$this->vin = $row['vin'];
			$this->expireDate = $row['expire_date'];
			$this->symbol = $row['symbol'];
		}

		public function getId(){
			return $this->id;
		}
		public function getCertificateNumber(){
			return $this->certificateNumber;
		}
		public function setCertificateNumber($val){
			$this->certificateNumber=$val;
		}
		public function getBuildingNumber(){
			return $this->buildingNumber;
		}
		public function setBuildingNumber($val){
			if ($val!=null) {
				$val = trim($val);
				if ($val!="" && $val!="-" && $val!="--") {
					$this->buildingNumber=$val;
				}
			}
		}
		public function getVin(){
			return $this->vin;
		}
		public function setVin($val){
			if ($val!=null) {
				$val = trim($val);
				if ($val!="" && $val!="-" && $val!="--") {
					$this->vin=$val;
				}
			}
		}
		public function getExpireDate(){
			return $this->expireDate;
		}
		public function setExpireDate($val){
			$this->expireDate = $val;
		}
		public function getSymbol(){
			return $this->symbol;
		}
		public function setSymbol($val){
			$this->symbol = $val;
		}
	}
?>
