<?
require_once (__DIR__.'/crest.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$params = ['FIELDS' => [
		'TITLE' => 'New Lead RestB2B', 
		'NAME' => 'Иван', 
		'LAST_NAME' => 'Петров', 
		'EMAIL' => [
			'0' => [
				'VALUE' => 'mail@example.com', 
				'VALUE_TYPE' => 'WORK', 
			], 
		], 
		'PHONE' => [
			'0' => [
				'VALUE' => '555888', 
				'VALUE_TYPE' => 'WORK', 
			], 
		], 
	], 
	];
	$result = CRest::call(
			'crm.lead.add',
			$params		
		);
	
	echo json_decode('red');
}
