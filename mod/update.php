<?php 

try {

	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata, true);
	$id = $request['id'];
	$done = $request['done'];


	if (empty($id)) 
	{
		throw new PDOException('Invalid request');
	}

	$done = empty($done) ? 0 : 1;

	$objDb = new PDO('sqlite:../db/shopping-list');
	$objDb -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "UPDATE `items` 
			SET `done` = ?
			WHERE `id` = ?";

	$statement = $objDb->prepare($sql);

	if (!$statement->execute(array($done, $id))) {
		throw new PDOException('The execute method failed');
	}

	$id = $objDb->lastInsertId();

	echo json_encode(array(

		'error' => false

	), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

} catch(PDOException $e) {
	echo json_encode(array(

		'error' => true,
		'message' => $e->getMessage()

	), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
}