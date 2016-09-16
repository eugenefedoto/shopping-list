<?php

try {
	$objDb = new PDO('sqlite:../db/shopping-list');
	$objDb -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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