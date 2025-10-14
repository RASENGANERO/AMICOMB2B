<?php
class Brands{
    const HL_BRAND = 8;
    const IBLOCK_BRAND = 33;
    const IBLOCK_CATALOG = 29;
    const PROP_CML2 = 'CML2_TRAITS';
    const DESCRP = 'Марка';
    
    
    public static function brandsAdd($arFields) {
        if (intval($arFields['IBLOCK_ID']) === 29 && intval($arFields['CREATED_BY']) === 2) {
            self::checkNewBrand($arFields);
        }
    }
    public static function brandsUpdate($arFields) {
        if (intval($arFields['IBLOCK_ID']) === 29 && intval($arFields['MODIFIED_BY']) === 2) {
            self::checkNewBrand($arFields, true);
        }
    }
    public static function getHLBrandName($UF_XML) {   
        $arHLBlock = Bitrix\Highloadblock\HighloadBlockTable::getById(8)->fetch();
        $obEntity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHLBlock);
        $strEntityDataClass = $obEntity->getDataClass();
        $rsData = $strEntityDataClass::getList([
            'select' => ['ID', 'UF_NAME', 'UF_XML_ID'],
            'order' => ['ID'=>'ASC'],
            'filter' => ['UF_XML_ID'=>$UF_XML]
        ]);
        return $rsData->Fetch()['UF_NAME'];
    }
    public static function getXMLID($IBLOCK,$ELEM){
        $db_props = CIBlockElement::GetProperty($IBLOCK, $ELEM, array("sort" => "asc"), Array("CODE"=>"CML2_TRAITS"));
		while ($ob = $db_props->GetNext()) {
			if($ob['DESCRIPTION']=='Марка'){
				$val = $ob['VALUE'];
				break;
			}
		}
		return $val;
    }
    public static function addBrandIBlock($idProduct,$brand){
        $element = new CIBlockElement;
        $arParamUtil = [
            'replace_space' => '_',
            'replace_util' => '_',
        ];
        $arAdd = [
            'CREATED_BY' => 1,
            'MODIFIED_BY' => 1,
            'IBLOCK_ID' => 33,
            'ACTIVE' => 'N',
            'NAME' => $brand,
            'CODE' => CUtil::translit($brand, 'en', $arParamUtil),
        ];
        if($idNewBrand = $element->Add($arAdd)) {
            self::setBrand($idProduct,$idNewBrand);
        }
    }
    public static function checkBrandInBlock($name){
        $arFilter = [
            'IBLOCK_ID' => 33,
            'NAME' => $name,
            ];
        $selectedFields = ['ID','NAME'];
        $valuesBrand = CIBlockElement::GetList(
            ['ID'=>'ASC'],
            $arFilter,
            false,
            false,
            $selectedFields)->Fetch();
        if ($valuesBrand === false) {
            return '';
        }
        else{
            return $valuesBrand;
        }
    }
    public static function setBrand($idElement, $idBrand){
        CIBlockElement::SetPropertyValueCode($idElement, 'BRAND',$idBrand);
    }

    public static function checkNewBrand($arFields, $addIfNotFound = false) {
        $XML_ID = self::getXMLID($arFields['IBLOCK_ID'],$arFields['ID']);
        $brandName = self::getHLBrandName($XML_ID);
        if ($brandName !== '') {
            $infoBrand = self::checkBrandInBlock($brandName); // Проверяем, есть ли элемент в инфоблоке брендов
            if ($infoBrand !== '') {
                self::setBrand($arFields['ID'], $infoBrand['ID']);
            } elseif ($addIfNotFound) {
                self::addBrandIBlock($arFields['ID'], $brandName);
            }
        }
    }
}