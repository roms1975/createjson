<?php

require_once(__DIR__ . "/CreateJson.php");
	
$all_nodes = array();
$childs = array();
$roots = array();
$branches = array();

if (empty($argv[1]) || empty($argv[2])) {
	error_log("Arguments is empty\nUsage: php start.php <input file> <output file>");
	exit();
}

if (file_exists($argv[1])) {
	$input_fd = fopen($argv[1], "r");
} else {
	error_log("Unable open file " . $argv[1]);
	exit();
}

fgetcsv($input_fd, 1000, ";");

while ($row = fgetcsv($input_fd, 1000, ";")) {
	if (!empty($row[3])) {
		$all_nodes[$row[0]]['relation'] = $row[3];
	}

	if (empty($row[2])) {
		$roots[] = $row[0];
	}

	$childs[$row[2]][] = $row[0];
	$all_nodes[$row[0]]['parent'] = $row[2];
}

$json = new CreateJson($all_nodes, $childs);
	
foreach ($roots as $name) {
	$branches[] = $json->create_tree($name, null);
}

try {
	$json = json_encode($branches, JSON_UNESCAPED_UNICODE);
} catch(Exception $e) {
	error_log("Unable create json");
	exit();
}

file_put_contents($argv[2], $json);

?>