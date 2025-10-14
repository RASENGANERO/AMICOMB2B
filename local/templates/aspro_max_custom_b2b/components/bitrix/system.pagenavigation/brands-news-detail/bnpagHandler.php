<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
header('Content-type: application/json');
CModule::IncludeModule('iblock');
use Amikomnew as Amikomnew;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$dataIDS = $_POST['elements'];
    $dataBrand = $_POST['brand'];
    $result =  Amikomnew\BrandsDetail::getElementsNewsBrands($dataBrand, $dataIDS);
	echo json_encode(['result'=>$result]);
}
?>