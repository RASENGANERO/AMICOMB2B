<?
use Amikomnew\ReviewsProducts;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
header('Content-type: application/json');

CModule::IncludeModule('iblock');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = $_POST;
    $result =  ReviewsProducts::addReview($data);
	echo json_encode(['result'=>$result]);
}
?>
