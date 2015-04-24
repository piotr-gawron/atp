<?php
	include_once "config.php";

	$config = new Config();
	$dbConnection = new DbConnection($config);

	function printHeader() {
		global $config;
		global $dbConnection;
		echo "<html>\n";
		echo "<head>\n";
		echo "<title>".$config->getTitle()."</title>";
	  echo "<meta charset=\"UTF-8\">\n";
		echo "<link href=\"style.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\" />\n";
		echo "<link href=\"form.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\" />\n";
		echo "</meta>\n";
		echo "<body>\n";
		echo "<center><h2>".$config->getTitle()."</h2></center>\n";
	}
	function printFooter() {
		global $dbConnection;
		$lastUpdate =  $dbConnection->updateEventDao->getLast();
		$date = '';
		if ($lastUpdate!=null) {
			echo '<div style="position: absolute; bottom: 5px; left: 0px; right: 0px; float:right; background-color:#AAAAAA; padding-right: 10px; "><p align="right">wersja bazy: '.$lastUpdate->getDate().' </p> </div>';
		}
		echo "</body>\n";
	}

	function getQueryForm($query) {
		$result = "<div class=\"queryForm\">";
		$result .= "<form method=\"POST\">\n";
		$result .= "<label for=\"query\">numer</label>\n";
		$result .= "<input type=\"text\" name=\"query\" value=\"$query\"/>\n";
		$result .= "<input type=\"submit\" value=\"Szukaj\">\n";

		$result .= "</form>";

		$result .="</div>";

		return $result;
	}

	function printQueryResult($query) {
		global $dbConnection;
		
		$result = $dbConnection->certificateDao->getCertificatesByQuery($query);

		$counter = count($result);
		echo "<div class=\"caption\">";
		echo "<div style=\"display:inline;\">Znalezione certyfikaty</div>";
		echo getQueryForm($query);
		echo "</div>";
		echo "<div id=\"table\">\n";
		echo "<div class=\"header-row row\">\n";
		echo "<span class=\"cell primary\">Lp.</span>\n";
		echo "<span class=\"cell\">Numer certyfikatu</span>\n";
		echo "<span class=\"cell\">Numer zabudowy</span>\n";
		echo "<span class=\"cell\">Numer podwozia</span>\n";
		echo "<span class=\"cell\">Klasa ATP</span>\n";
		echo "<span class=\"cell\">Data waznosci</span>\n";
		echo "</div>\n";


		for ($i=0; $i<$counter; $i++) {
			echo "<div class=\"row\">\n";
			echo "<input type=\"radio\" name=\"expand\">";
			echo "<span class=\"cell primary\" data-label=\"Lp.\">".($i+1)."</span>";
			echo "<span class=\"cell\" data-label=\"Numer certyfikatu\">".$result[$i]->getCertificateNumber()."</span>";
			echo "<span class=\"cell\" data-label=\"Numer zabudowy\">".$result[$i]->getBuildingNumber()."</span>";
			echo "<span class=\"cell\" data-label=\"Numer podwozia\">".$result[$i]->getVin()."</span>";
			echo "<span class=\"cell\" data-label=\"Numer podwozia\">".$result[$i]->getSymbol()."</span>";
			$date = strtotime($result[$i]->getExpireDate());
			$date = date("m/Y", $date);
			echo "<span class=\"cell\" data-label=\"Data waznosci\">".$date."</span>";
			echo "</div>\n";
		}
		echo "</div>\n";
		if ($counter<=0) {
			echo '<div style="background-color:white; padding-top: 10px; padding-bottom: 10px;">';
			echo "<center><b>Nie znaleziono wynikow dla zapytania: ".$query."</b></center>";
			echo "</div>\n";
		}
	}

	$dbConnection->openDbConnection();
	printHeader();
	$query = "";
	if (isset($_POST["query"])) {
		$query = $_POST["query"];
		$query = mysql_real_escape_string ($query);
	}

	printQueryResult($query);
	printFooter();

	$dbConnection->closeDbConnection();
?>
