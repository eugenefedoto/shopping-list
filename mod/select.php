<?php

$servername = "mysql11.000webhost.com";
$username = "a4773789_name";
$password = "132816a";

try {
	
	try {
    	$objDb = new PDO("mysql:host=$servername;dbname=a4773789_mydb", $username, $password);
    	// set the PDO error mode to exception
    	$objDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	echo "Connected successfully"; 
    }
		catch(PDOException $e)
    {
    	echo "Connection failed: " . $e->getMessage();
    }

	$sql = "SELECT `i`.*,
			`t`.`name` AS `type_name`
			FROM `items` `i`
			JOIN `types` `t`
			ON `t`.`id` = `i`.`type`
			ORDER BY `i`.`date` ASC";
	$result = $objDb->query($sql);

	if (!$result) {
		throw new PDOException('The result returned no object');
	}

	$result->setFetchMode(PDO::FETCH_ASSOC);

	$items = $result->fetchAll();

	$sql = "SELECT *
			FROM `types`
			ORDER BY `id`";

	$result = $objDb->query($sql);

	if (!$result) {
		throw new PDOException('The result returned no object');
	}

	$result->setFetchMode(PDO::FETCH_ASSOC);

	$types = $result->fetchAll();

	echo json_encode(array(
		'error' => false,
		'items' => $items,
		'types' => $types
	));

} catch(PDOException $e) {
	echo json_encode(array(
		'error' => true,
		'message' => $e->getMessage()
	));
}

?>