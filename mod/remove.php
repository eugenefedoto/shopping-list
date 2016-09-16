<?php 

$servername = "mysql11.000webhost.com";
$username = "a4773789_efedoto";
$password = "Fluffy!132816";

try {

	if (empty($_POST['ids'])) {

		throw new PDOException('Invalid request');
	}

	$ids = $_POST['ids'];
	$idsArray = explode('|', $ids);
	$placeHolders = implode(',', array_fill(0, count($idsArray), '?'));

	$done = empty($done) ? 0 : 1;

	$objDb = new PDO("mysql:host=$servername;dbname=a4773789_myDB", $username, $password);
	$objDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "DELETE FROM `items`
			WHERE `id` IN ({$placeHolders})";

	$statement = $objDb->prepare($sql);

	if (!$statement->execute($idsArray)) {

		throw new PDOException('The execute method failed');
	}

	echo json_encode(array(

		'error' => false

	), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

} catch(PDOException $e) {
	echo json_encode(array(

		'error' => true,
		'message' => $e->getMessage()

	));
}