<?
namespace Amikomnew;
require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");

use CIBlockElement;
use CIBlockSection;

class FunctionsProducts{
    
    public static function getCharactersProduct($IBLOCK_ID,$ELEMENT_ID) :array{
        $propertyNot = [
            369,
            370,
            393,
            394,
            396,
            371,
            375,
            377,
            378,
            2880,
            384,
            382,
            395,
            2875,
            374
        ];
        $propertyValues = [];
        $props = CIBlockElement::GetProperty($IBLOCK_ID,$ELEMENT_ID, [], []);
        $valueProp = '';
        while ($prop = $props->Fetch()) {
            if (!empty($prop['VALUE'])) {
                if (!in_array($prop['ID'],$propertyNot)) {
                    if ($prop['PROPERTY_TYPE'] === 'L') {
                        $valueProp = $prop['VALUE_ENUM'];
                    }
                    else if ($prop['PROPERTY_TYPE'] === 'E') {
                        $valueProp = CIBlockElement::GetByID($prop['VALUE'])->Fetch()['NAME'];
                    }
                    else {
                        $valueProp = $prop['VALUE'];
                    }
                    $valueProp = (($valueProp == 'Y') ? 'Да' : $valueProp);
                    $propertyValues[] = [
                        'ID' => $prop['ID'],
                        'NAME' => $prop['NAME'],
                        'VALUE' => $valueProp,    
                    ];
                }
            }
        }
        return $propertyValues;
    }
    public static function getSectionName($ELEMENT_ID) : string{
        $sectionID = CIBlockElement::GetByID($ELEMENT_ID)->Fetch()['IBLOCK_SECTION_ID'];
        $sectionRes = CIBlockSection::GetByID($sectionID)->Fetch()['NAME'];
        return $sectionRes;
    }


    //ASPRO FILTER ДЛЯ ТОВАРА
    public static function generateUrlsAspro($urlsAll) {
        $urlList = [];
        foreach ($urlsAll as $item) {
            $urlList[] = "'".strval($item['URL'])."'";
        }
        return implode(",",$urlList);
    }

    public static function getIDPropertiesAspro($arData) : array {
        $arrData = [];
        global $DB;
        //Формируем URL
        $urlList = FunctionsProducts::generateUrlsAspro($arData);
        //Составляем запрос
        $urlQuery = "SELECT `b_aspro_smartseo_filter_url`.`ID` AS `IDURL`, `b_aspro_smartseo_filter_url`.`PROPERTIES` AS `PROPERTIES`,`b_aspro_smartseo_filter_url`.`NEW_URL` AS `URL` FROM `b_aspro_smartseo_filter_url` WHERE `b_aspro_smartseo_filter_url`.`NEW_URL` IN (".$urlList.")";
        //Выполняем запрос
        $results = $DB->Query($urlQuery);
        //Получаем данные
        $k = 0;
        while($row = $results->Fetch()){
            $propAspro = unserialize(strval($row['PROPERTIES']));
            foreach ($propAspro as $item) {

                $arrData[] = [
                    'ID' => $row['IDURL'],
                    'PROPERTY_ID' => $item['PROPERTY_ID'],
                    'PROPERTY_VALUE' => $item['VALUES']['DISPLAY'][0],
                    'URL' => $row['URL']
                ];
            }
            $k+=1;
        }
        return $arrData;
    }

    
    //Функция удаления ненужных брендов (логика - чтобы были URL, которые связаны ТОЛЬКО С БРЕНДОМ ТОВАРА)
    public static function removeBrands($dataElements, $brandName):array {
        $filteredElements = [];
        $filteredElementsBrands = [];
        foreach ($dataElements as $item) {
            if (intval($item['PROPERTY_ID']) === 373) {
                $filteredElements[] = '';
                $filteredElementsBrands[] = $item;
            }
            else{
                $filteredElements[] = $item;
            }
        }
        
        for ($i=0;$i<count($filteredElementsBrands);$i++) {
            if (strtoupper(strval(trim($filteredElementsBrands[$i]['PROPERTY_VALUE']))) !== $brandName) {
                $filteredElementsBrands[$i] = '';
            }
        }
        $result = array_values(array_filter(array_merge($filteredElements, $filteredElementsBrands)));
        return $result;
    }

    public static function normalizeArray($dataElements):array {
        $result = [];
        foreach ($dataElements as $item) {
            $propertyId = $item['PROPERTY_ID'];
            if (!isset($result[$propertyId])) {
                $result[$propertyId] = [];
            }
            $result[$propertyId][] = $item;
        }
        return $result;    
    }


    public static function getFinalUrlArray($dataFirst, $dataNormalize):array {
        $resultArr = [];
        foreach($dataFirst as $item) {
            $data = $dataNormalize[$item['ID']];
            foreach($data as $itemFinal) {
                if ($itemFinal['PROPERTY_VALUE'] === $item['VALUE']) {
                    $resultArr[] = $itemFinal;
                }
            }
        }
        return $resultArr;
    }

    public static function setUniqueUrls($dataAllUrls, $dataLast) {
        $result = [];
        foreach ($dataAllUrls as $item) {
            $dataID = $item['ID'];
            foreach ($dataLast as $newIt) {
                if ($newIt['ID'] === $dataID) {
                    $result[] = $newIt;
                }
            }
        }
        return array_values($result);
    }

    public static function getSurroundingElements($array, $element) {
        $array = array_keys($array);
        // Находим индекс элемента
        $index = array_search($element, $array);
        
        if ($index === false) {
            return []; // Элемент не найден
        }
        // Получаем длину массива
        $length = count($array);
        
        // Массив для хранения результатов
        $surroundingElements = [];
    
        // Получаем 5 предыдущих и 6 следующих элементов
        for ($i = 1; $i <= 6; $i++) {
            // Находим предыдущий элемент (с учетом цикличности)
            $prevIndex = ($index - $i + $length) % $length;
            // Добавляем элемент, если он не равен искомому
            if ($prevIndex !== $index) {
                $surroundingElements[] = $array[$prevIndex];
            }
        }
    
        for ($i = 1; $i <= 8; $i++) {
            // Находим следующий элемент (с учетом цикличности)
            $nextIndex = ($index + $i) % $length;
            // Добавляем элемент, если он не равен искомому
            if ($nextIndex !== $index) {
                $surroundingElements[] = $array[$nextIndex];
            }
        }
        
        return array_unique($surroundingElements);
    }
    public static function getElementsRelation($iblockSection, $idElement) {
        $arFilter = [
            'IBLOCK_ID' => 29,
            'IBLOCK_SECTION_ID' => $iblockSection,
            'ACTIVE' => 'Y',
        ];
        
        $arSelect = ['ID'];
        $res = CIBlockElement::GetList(['ID' => 'ASC'], $arFilter, false, false, $arSelect);
        $relationProducts = [];
            
        while ($item = $res->Fetch()) {
            $relationProducts[] = $item['ID'];
        }
        $key = '';
        for ($i=0;$i<count($relationProducts);$i++){
            if (intval($relationProducts[$i]) === intval($idElement)) {
                $key = $i;
                break;
            }
        }
        
        $similarKeys = self::getSurroundingElements($relationProducts, $key);
        for($i=0;$i<count($relationProducts);$i++) {
            if (!in_array($i,$similarKeys)) {
                $relationProducts[$i] = '';
            }
        }
        $relationProducts = array_values(array_filter($relationProducts));
        return $relationProducts;
    }
    public static function getPrice ($idProduct) { // функция получения оптовой цены товара
        $allProductPrices = \Bitrix\Catalog\PriceTable::getList([
            "select" => ["PRICE"],
            "filter" => [
                "=PRODUCT_ID" => intval($idProduct),
                "=CATALOG_GROUP_ID" => 2
            ],
        ])->fetch();
        return $allProductPrices['PRICE'];
    }
}
?>