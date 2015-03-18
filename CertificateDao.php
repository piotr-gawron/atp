<?php
	class CertificateDao {
		private $table = "atp_certificate";
		public function getCertificatesByQuery($str) {
			$escaped = mysql_real_escape_string ($str);
			$query = "SELECT * FROM ".$this->table." WHERE building_number = '".$escaped."'";
			$query.= " OR vin = '".$escaped."'";
			if (strlen($str)>=6) {
				$query.= " OR building_number like '%".$escaped."'";
				$query.= " OR vin like '%".$escaped."'";
			}
			$dbRes=mysql_query($query);
			$result = array();
			while ($row=mysql_fetch_array($dbRes)) {
				$result []= new Certificate($row);
			}
			return $result;
		}
		public function addCertificate($certificate) {
			$query = "INSERT INTO ".$this->table." (certificate_number, building_number, vin, expire_date, symbol) values (";
			$query .= $this->prepareStr($certificate->getCertificateNumber()).", ";
			$query .= $this->prepareStr($certificate->getBuildingNumber()).", ";
			$query .= $this->prepareStr($certificate->getVin()).", ";
			$query .= $this->prepareDate($certificate->getExpireDate()).", ";
			$query .= $this->prepareStr($certificate->getSymbol()).");" ;
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
				$str = str_replace('-', '/', $str);
				
				$cols = preg_split('/\//',$str);
				if (count($cols)!=2) {
					throw new InvalidDateFormatException("Niepoprawna data: \"".$str."\"");
				}
				$month=trim($cols[0]);
				$year=trim($cols[1]);
				if (!is_numeric($month)) {
					throw new InvalidDateFormatException("Niepoprawna data: \"".$str."\"");
				}
				if (!is_numeric($year)) {
					throw new InvalidDateFormatException("Niepoprawna data: \"".$str."\"");
				}
				if ($month<1 || $month>12){
					throw new InvalidDateFormatException("Niepoprawna data: \"".$str."\"");
				}
				$result = date("Y-m-d H:i:s", strtotime($year."-".$month."-01"));
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
