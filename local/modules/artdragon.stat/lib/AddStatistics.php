<? 
namespace artdragon\stat;
use artdragon\stat\StatisticsTable;
use Bitrix\Catalog\Model\Price;
use CIBlockElement;
use CIBlockSection;
class AddStatistics {
	public static function addData($arrayData, $type) {
		$timeNow = new \Bitrix\Main\Type\DateTime();
		$primary = self::getPrimaryMax();
		$priceOld = self::getPriceOld($arrayData['ID']);
		$sectionPath = self::getSection($arrayData['ID']);
		$elementCode = self::getElementCode($arrayData['ID']);
		$data = [
			'ID_NUMBER' => $primary,
			'ID_ELEMENT' => $arrayData['ID'],
			'ELEMENT_CODE' => $arrayData['CODE'],
			'ELEMENT_SECTION' => $sectionPath,
			'TYPE' => $type, 
			'OLD_PRICE' => $priceOld,
			'DATE_TIME_ELEMENT' => new \Bitrix\Main\Type\DateTime($timeNow->format('d.m.Y H:i:s')), // Используем правильный формат
			'URL_ELEMENT' => 'https://www.ami-com.ru/catalog/'. $elementCode.'/', 
		];
		$result = StatisticsTable::add($data);
		if (!$result->isSuccess()) {
			$errors = $result->getErrors();
			foreach ($errors as $error) {
				echo "Ошибка: " . $error->getMessage() . "<br>";
			}
		}
		self::removeOldStat();
	}
	public static function getElementCode($ID) {
		$res = CIBlockElement::GetByID($ID)->Fetch()['CODE'];
		return $res;
	}
	
	public static function getPriceOld($IDElement) {
		$priceData = Price::getList([
			'filter' => ['PRODUCT_ID' => intval($IDElement)],
			'select' => ['PRICE'],
			'limit' => 1,
		])->fetch();
		return floatval($priceData['PRICE']);
	}
	public static function getSection($IDElement) {
		$res = CIBlockElement::GetByID($IDElement)->Fetch();
		if (empty($res['IBLOCK_SECTION_ID'])) {
			return 'https://www.ami-com.ru/catalog/';
		}
		else {
			$url =  CIBlockSection::GetNavChain(29,$res['IBLOCK_SECTION_ID'],array(),true);
			$arrData = [];
			foreach ($url as $arSectionPath) {
				$arrData[] = $arSectionPath['CODE'];
			}
			$arrData = 'https://www.ami-com.ru/catalog/'.implode('/',$arrData).'/';
			$arrData = str_replace('\\', '',  $arrData);
			return $arrData;
		}
	}
	public static function addNewPrice($idElement, $newPrice){
		global $DB;
		$result = $DB->Query("SELECT * FROM 1c_statistics WHERE ID_ELEMENT = " . intval($idElement) . " ORDER BY ID_NUMBER DESC LIMIT 1;")->Fetch();
    	$updateQuery = "UPDATE 1c_statistics SET NEW_PRICE = " . floatval($newPrice) . " WHERE ID_NUMBER = " . intval($result['ID_NUMBER']) . ";";
		$DB->Query($updateQuery);
	}
	public static function getPrimaryMax() {
		global $DB;
		$resPrime = $DB->Query('SELECT MAX(ID_NUMBER) AS PRIM FROM 1c_statistics;')->Fetch()['PRIM'];
		return intval($resPrime)+1;
	}
	public static function removeOldStat(){
		$dateTime = new \Bitrix\Main\Type\DateTime();
		$dateTime->add('-2 day');
		$rsData = StatisticsTable::getList([
			'select'=>['ID_NUMBER'],
			'filter'=>['<=DATE_TIME_ELEMENT'=>$dateTime],
		]);
		while($arRes = $rsData->Fetch()) {
			StatisticsTable::delete(intval($arRes['ID_NUMBER']));
		}
	}
}	