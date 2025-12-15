<?
use Amikomnew\BrandsDetail;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
header('Content-type: application/json');
CModule::IncludeModule('iblock');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$dataIDS = $_POST['elements'];
    $dataBrand = $_POST['brand'];
    $result =  BrandsDetail::getElementsNewsBrands($dataBrand, $dataIDS);
	echo json_encode(['result'=>$result]);
}
?>
