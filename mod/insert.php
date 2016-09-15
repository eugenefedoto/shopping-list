<?php 

try {

	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	$item = $request->item;
	$qty = $request->qty;
	$type = $request->type;


	if (empty($item) ||
		empty($qty) || 
		empty($type)
	) 
	{
		throw new PDOException('Invalid request');
	}


	$objDb = new PDO('sqlite:../db/shopping-list');
	$objDb -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "INSERT INTO `items`
			(`id`, `item`, `qty`, `type`)
			VALUES (null, ?, ?, ?)";

	$statement = $objDb->prepare($sql);

	if (!$statement->execute(array($item, $qty, $type))) {
		throw new PDOException('The execute method failed');
	}

	$id = $objDb->lastInsertId();

	$sql = "SELECT `i`.*,
			`t`.`name` AS `type_name`
			FROM `items` `i`
			JOIN `types` `t`
			ON `t`.`id` = `i`.`type`
			WHERE `i`.`id` = ?";

	$statement = $objDb->prepare($sql);

	if (!$statement->execute(array($id))) {
		throw new PDOException('The result returned no object');
	}

	$statement->setFetchMode(PDO::FETCH_ASSOC);

	$item = $statement->fetch();

	echo json_encode(array(
		'error' => false,
		'item' => $item,
	), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

} catch(PDOException $e) {
	echo json_encode(array(
		'error' => true,
		'message' => $e->getMessage()
	), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
}