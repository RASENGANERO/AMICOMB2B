<?
require_once('InformHLBlockB2B.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$dataJson = file_get_contents('php://input');
	$dataJson = json_decode($dataJson, true);

	$objHLBlock = new \AmikomB2B\InformHLBlockB2B($dataJson);
	$objHLBlock->setManagers($objHLBlock::HLIBLOCK_MANAGERS);
	$objHLBlock->setPriceGroup($objHLBlock::HLIBLOCK_PRICE_GROUP);
	$objHLBlock->setPriceMatrix($objHLBlock::HLIBLOCK_PRICE_MATRIX);

	$objIBlock = new \AmikomB2B\InformIblockB2B($dataJson);
	$objIBlock->checkElement($objIBlock::IBLOCK_COMPANY_B2B);
	$objIBlock->checkElement($objIBlock::IBLOCK_PARTNER_B2B);
	$objIBlock->checkElement($objIBlock::IBLOCK_TERMS_B2B);
	echo json_encode(['DATA' => $dataJson['_11_Region']], JSON_UNESCAPED_UNICODE);
}
?>