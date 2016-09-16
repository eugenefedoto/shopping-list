<?php 

try {

	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	$ids = $request['ids'];


	if (empty($ids)) 
	{
		throw new PDOException('Invalid request');
	}

	$idsArray = explode('|', $ids);
	$placeHolders = implode(', ', array_fill(0, count($idsArray), '?'));

	$done = empty($done) ? 0 : 1;

	$objDb = new PDO('sqlite:../db/shopping-list');
	$objDb -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "DELETE FROM `items` 
			WHERE `id` in ({$placeholders})";

	$statement = $objDb->prepare($sql);

	if (!$statement->execute($idsArray)) {
		throw new PDOException('The execute method failed');
	}

	$id = $objDb->lastInsertId();

	echo json_encode(array(

		'error' => false

	));

} catch(PDOException $e) {
	echo json_encode(array(

		'error' => true,
		'message' => $e->getMessage()

	));
}