<?
//namespace AmikomB2B;
use AmikomB2B\InformHLBlockB2B;
use AmikomB2B\InformIblockB2B;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$dataJson = file_get_contents('php://input');
	$objHLBlock = new InformHLBlockB2B($dataJson);
	$objHLBlock->setManagers(InformHLBlockB2B::HLIBLOCK_MANAGERS);
	$objHLBlock->setPriceGroup(InformHLBlockB2B::HLIBLOCK_PRICE_GROUP);
	$objHLBlock->setPriceMatrix(InformHLBlockB2B::HLIBLOCK_PRICE_MATRIX);

	$objIBlock = new InformIblockB2B($dataJson);
	$objIBlock->checkElement(InformIblockB2B::IBLOCK_COMPANY_B2B);
	$objIBlock->checkElement(InformIblockB2B::IBLOCK_PARTNER_B2B);
	$objIBlock->checkElement(InformIblockB2B::IBLOCK_TERMS_B2B);
}
?>