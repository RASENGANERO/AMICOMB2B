<?
use Bitrix\Catalog\PriceTable;
use Bitrix\Catalog\VatTable;
use Aspro\Functions\CAsproMaxItem;
//CMax::getFieldImageData($arResult, array('PREVIEW_PICTURE'));

/*get price and avaible */
$arResult["SHOW_BUY_BUTTON"] = false;
if($arResult['PROPERTIES']['ALLOW_BUY']['VALUE'] === 'Y'){
	$product_data = CCatalogProduct::GetByID($arResult['ID']);
	if($product_data['AVAILABLE'] === 'Y'){

		$arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID"], $arParams["PRICE_CODE"]);
		$arResult['PRICES_ALLOW'] = CIBlockPriceTools::GetAllowCatalogPrices($arResult["PRICES"]);

		$select = [
			'ID', 'PRODUCT_ID', 'CATALOG_GROUP_ID', 'PRICE', 'CURRENCY',
			'QUANTITY_FROM', 'QUANTITY_TO'
		];

		if($arResult['PRICES_ALLOW']){

			$iterator = PriceTable::getList([
				'select' => $select,
				'filter' => ['@PRODUCT_ID' => $arResult['ID'], '@CATALOG_GROUP_ID' => $arResult['PRICES_ALLOW']],
				'order' => ['PRODUCT_ID' => 'ASC', 'CATALOG_GROUP_ID' => 'ASC']
			]);

			$arPrices = [];

			while ($row = $iterator->fetch())
			{
				$arPrices[$row['CATALOG_GROUP_ID']] = $row;

			}

			//$arResult["CATALOG_QUANTITY"] = $product_data["QUANTITY"];	

			$vatData = [];
			$vatIterator = VatTable::getList([
				'select' => ['ID', 'RATE'],
				'order' => ['ID' => 'ASC']
			]);
			while ($rowVat = $vatIterator->fetch())
				$vatData[(int)$rowVat['ID']] = (float)$rowVat['RATE'];

			$vatRate = $vatData[$product_data['VAT_ID']];


			$arMinimalPrice = CAsproMaxItem::getServicePrices($arParams, $arPrices, $product_data, $vatRate);
			if(is_array($arMinimalPrice) && isset($arMinimalPrice['PRICE']) && $arMinimalPrice['PRICE']>0 ){
				$arResult["BUTTON_RESULT_PRICE"] = $arMinimalPrice;
				$arResult["SHOW_BUY_BUTTON"] = true;
			}
		}
	}	
}

/* */

?>
