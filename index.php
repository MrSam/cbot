<?php

// Incoming payload
$text = $_POST['text'];
$trigger = $_POST['trigger_word'];
$search = ltrim(str_replace($trigger, "", $text));
//
//$search = $argv[1];

// Do something
$res = MrCSV::find('cronos.csv', (string)$search);
if(sizeof($res) == 0) {
 $message = "Zo geen cronosboite gevonden :joy:";
} elseif(sizeof($res) == 1) {
 $message = "Bedoel je *" . $res[0]['name'] . "* :upside_down_face: Yep dat is er eentje !";
 $message .= "\n";
 $message .= "> BTW: " . $res[0]['vat'] . " (" . $res[0]['type'] . ")";
 $message .= "\n";
 $message .= "> Adres: " . $res[0]['address'];
 
} else {
 $message = "De volgende zijn allemaal van cronos :raised_hands: :raised_hands: :raised_hands:\n";
 foreach($res as $boite) {
 	$message .= "> *". $boite['name'] ."*\n";
 }
}


// Reply
$message = ["text" => $message];
echo json_encode($message);


class MrCSV { 

	public static function find($file, $search) {
		$ch = fopen($file, "r");
		$matches = [];
		while(($row = fgetcsv($ch)) !== FALSE) {
			if(stristr($row[0], $search)) {
				$matches[] = array("name" => $row[0], "vat" => $row[1], "type" => $row[2], "address" => $row[3]);
			}
		}
		return $matches;
	}

}
