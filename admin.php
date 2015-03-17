<?php
	include_once "config.php";

	$config = new Config();
	$dbConnection = new DbConnection($config);

	function printHeader() {
		global $config;
		echo "<html>\n";
		echo "<head>\n";
		echo "<title>Admin panel - ".$config->getTitle()."</title>";
	  echo "<meta charset=\"UTF-8\">\n";
		echo "<link href=\"style.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\" />\n";
		echo "<link href=\"form.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\" />\n";
		echo "</meta>\n";
		echo "<body>\n";
		echo "<center><h2>Admin panel - ".$config->getTitle()."</h2></center>\n";
	}

	function isLoggedIn() {
		return isset($_SESSION['user']);
	}

	function tryLogin() {
		global $dbConnection;
		if (isLoggedIn()) {
			return true;
		} else if (isset($_POST['login'])&& isset($_POST['passwd'])){
			$user = $dbConnection->userDao->getUserByLoginPassword($_POST['login'],$_POST['passwd']);
			if ($user != null) {
				$_SESSION['user'] = $user;
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function uploadFile($filename) {
		global $dbConnection;
		$ok = true;
		$errors = "";

		$noCol = null;
		$certificateNumberCol = null;
		$buildingNumberCol= null;
		$vinCol = null;
		$symbolCol = null;
		$expireDateCol = null;
		$handle = fopen($filename, "r");
		if ($handle) {
			$line = fgets($handle);
			$cols = preg_split('/\t/',$line);
			for ($i=0;$i<count($cols);$i++) {
				$cols[$i]=trim($cols[$i]);
				if ($cols[$i]=="LP") {
					$noCol = $i;
				} else if ($cols[$i]=="nr_certyfikatu") {
					$certificateNumberCol = $i;
				} else if ($cols[$i]=="nr_zabudowy") {
					$buildingNumberCol= $i;
				} else if ($cols[$i]=="nr_podwozia") {
					$vinCol = $i;
				} else if ($cols[$i]=="symbol_calosci") {
					$symbolCol = $i;
				} else if ($cols[$i]=="data_waznosci") {
					$expireDateCol = $i;
				} else {
					$ok = false;
					$errors .= "Nieznana kolumna: \"".$cols[$i]."\"<br/>";
				}
			}
			if ($certificateNumberCol == null) {
				$ok = false;
				$errors.="Nie podano kolumny \"nr_certyfikatu\"";
			} else if ($buildingNumberCol == null && $vinCol==null) {
				$ok = false;
				$errors.="Nie podano ani kolumny \"nr_zabudowy\" ani \"nr_podwozia\".<br/>";
			} else if ($symbolCol == null) {
				$ok = false;
				$errors.="Nie podano kolumny \"symbol_calosci\"";
			} else if ($expireDateCol == null) {
				$ok = false;
				$errors.="Nie podano kolumny \"data_waznosci\"";
			}
			if ($ok) {
				$dbConnection->certificateDao->clear();
				$lineNumber = 1;
				while (($line = fgets($handle)) !== false) {
					if (trim($line)=="") {
						continue;
					}
					$cols = preg_split('/\t/',$line);
					for ($i=0;$i<count($cols);$i++) {
						$cols[$i]=trim($cols[$i]);
					}
					$certificate = new Certificate();

					if ($certificateNumberCol != null) {
						if (isset($cols[$certificateNumberCol])) {
							$certificate->setCertificateNumber($cols[$certificateNumberCol]);
						} else {
							$ok = false;
							$errors.="Nie podano certyfikatu w linii $lineNumber: ".$line."<br/>";
						}
					} 
					if ($buildingNumberCol != null) {
						if (isset($cols[$buildingNumberCol])) {
							$certificate->setBuildingNumber($cols[$buildingNumberCol]);
						}
					} 
					if ($vinCol!=null) {
						if (isset($cols[$vinCol])) {
							$certificate->setVin($cols[$vinCol]);
						}
					}
					if ($symbolCol != null) {
						if (isset($cols[$symbolCol])) {
							$certificate->setSymbol($cols[$symbolCol]);
						} else {
							$ok = false;
							$errors.="Nie podano symobolu w linii $lineNumber: ".$line."<br/>";
						}
					}
					if ($expireDateCol != null) {
						if (isset($cols[$expireDateCol])) {
							$certificate->setExpireDate($cols[$expireDateCol]);
						} else {
							$ok = false;
							$errors.="Nie podano daty w linii $lineNumber: ".$line."<br/>";
						}
					}
					try {
						$dbConnection->certificateDao->addCertificate($certificate);
					} catch (Exception $e) {
						$ok = false;
						$errors.= $e->getMessage()."<br/>";
					}
					$lineNumber++;
				}
			}
	    fclose($handle);
		} else {
			$ok=false;
			$errors.="Problem z otwarciem pliku...";
		    // error opening the file.
		} 
		$updateEvent = new UpdateEvent();
		$updateEvent->setDate(date("Y-m-d H:i:s"));
		$dbConnection->updateEventDao->add($updateEvent);
		if ($ok) {
			echo "Plik zaladowany pomyslnie.";
		} else {
			echo "Wystapily bledy podczas ladowania pliku:<br/>";
			echo $errors;
		}
		echo "<br/><br/>";
	}
	function printUploadPage(){
		echo "Witaj ".$_SESSION['user']->getLogin()."<br/>\n";
		echo "<a href=\"logout.php\">Wyloguj</a><br/>\n";
		echo "<a href=\"index.php\">Przegladaj baze</a><br/><br/><br/>\n";

		if (isset($_FILES["fileToUpload"]["tmp_name"])) {
			$filename = $_FILES["fileToUpload"]["tmp_name"];
			if ($filename!=null) {
				uploadFile($filename);
			}
		}

		echo "<form method=\"post\" enctype=\"multipart/form-data\">\n";
		echo "Plik z baza danych:\n";
		echo "<input type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\"><br/>\n";
		echo "<input type=\"submit\" value=\"Aktualizuj\" name=\"submit\">\n";
		echo "</form>\n";
	}

	function printStatistics() {
		global $dbConnection;

		$updateEvents = $dbConnection->updateEventDao->getAll();
		$count = count($updateEvents);
		echo "<h3>Aktualizacje bazy danych:</h3>";
		echo "<table>";
		echo "<tr><th>Lp.</th><th>Data</th></tr>";
		for ($i=0;$i<$count;$i++) {
			$event = $updateEvents[$i];
			echo "<tr>";
			echo "<td>".($i+1)."</td>";
			echo "<td>".$event->getDate()."</td>";
			echo "</tr>";
		}
		echo "</table>";

	}
	function printLoginPage() {
		$login = "";
		if (isset($_POST['login'])){
			$login = htmlspecialchars($_POST['login']);
		}
		echo "<form method=\"POST\">\n";
		echo "<center>\n";
		echo "<table>\n";
		echo "<tr>\n";
		echo "<td><label for=\"login\">Login</label></td>\n";
		echo "<td><input type=\"text\" id=\"login\" name=\"login\" value=\"$login\"/></td>\n";
		echo "</tr>";
		echo "<tr>\n";
		echo "<td><label for=\"passwd\">Haslo</label></td>\n";
		echo "<td><input type=\"password\" id=\"passwd\" name=\"passwd\"/></td>\n";
		echo "</tr>";
		echo "</table>";
		echo "<input type=\"submit\" value=\"Zaloguj\"/></br>\n";
		echo "</center>\n";
		echo "</form>";

	}

	function printFooter() {
		echo "</body>\n";
	}

	$dbConnection->openDbConnection();
	printHeader();

	tryLogin();

	if (isset($_SESSION['user'])){
		printUploadPage();
		printStatistics();
	} else {
		printLoginPage();
	}
	printFooter();

	$dbConnection->closeDbConnection();
?>
