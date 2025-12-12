<?
namespace AmikomB2B;
use CIBlockElement;
use Bitrix\Highloadblock\HighloadBlockTable;
use CUser;
class DiscountInfo {
    const IBLOCK_BRANDS = 33;
    const IBLOCK_PRODUCTS = 29;
    const HLBLOCK_MATRIX = 10;
    const HLBLOCK_TS_GROUP = 7;

    public static function getBrandID ($productID) {
        $propValue = CIBlockElement::GetProperty(self::IBLOCK_PRODUCTS,$productID,'sort','asc',['CODE' => 'BRAND'])->Fetch()['VALUE'];
        return $propValue;
    }
    public static function getBrandName ($idBrand):string {
        $nameBrand = CIBlockElement::GetByID($idBrand)->Fetch()['NAME'];
        $nameBrand = trim(strtolower($nameBrand));
        return $nameBrand;
    }

    public static function getEntity($idHLBlock) {
        $arHLBlock = HighloadBlockTable::getById($idHLBlock)->fetch();
        $obEntity = HighloadBlockTable::compileEntity($arHLBlock);
        $strEntityDataClass = $obEntity->getDataClass();
        return $strEntityDataClass;
    }

    public static function checkValuesDiscountByUser($idUserUF):array {
        $strEntityDataClass = self::getEntity(self::HLBLOCK_MATRIX);
        $rsData = $strEntityDataClass::getList([
            'select' => ['ID', 'UF_XML_ID', 'UF_DISCOUNT_VALUE', 'UF_PRICE_GROUP', 'UF_PARTNER_ID'],
            'filter' => ['UF_PARTNER_ID'=> $idUserUF],
            'count_total' => 1,
        ]);
        if ($rsData->getCount() > 0) {
            return $rsData->fetchAll();
        }
        return [];
    }

    public static function generateUFPriceGroupXML($arrXML) {
        $listXML = [];
        for ($i = 0; $i < count($arrXML); $i++) {
            $listXML[] = $arrXML[$i]['UF_PRICE_GROUP'];
        }
        return $listXML;
    }
    public static function getBrandsPriceGroup($UF_XML) {
        $strEntityDataClass = self::getEntity(self::HLBLOCK_TS_GROUP);
        $rsData = $strEntityDataClass::getList([
            'select' => ['ID', 'UF_XML_ID', 'UF_NAME'],
            'filter' => ['UF_XML_ID' => $UF_XML],
            'count_total' => 1,
        ]);
        if ($rsData->getCount() > 0) {
            return $rsData->fetchAll();
        }
        return [];
    }
    public static function getXMLBrand($arrBrands, $brName){
        $res = '';
        for ($i = 0; $i < count($arrBrands); $i++) {
            if (trim(strtolower($arrBrands[$i]['UF_NAME'])) === $brName) {
                $res = $arrBrands[$i]['UF_XML_ID'];
                break;
            }
        }
        return $res;
    }


    public static function getDiscounts($idUserUF, $brandName) {
        $discountsAll = [];
        if (!empty($idUserUF)) {
            $resultDiscountsUser = self::checkValuesDiscountByUser($idUserUF);
            if (!empty($resultDiscountsUser)) {
                $UFXML_ID = self::generateUFPriceGroupXML($resultDiscountsUser);
                if (!empty($UFXML_ID)) {
                    $brandsNameAll = self::getBrandsPriceGroup($UFXML_ID);
                    $xmlBrand = self::getXMLBrand($brandsNameAll,$brandName);
                    for ($i = 0; $i < count($resultDiscountsUser); $i++) {
                        if ($resultDiscountsUser[$i]['UF_PRICE_GROUP'] === $xmlBrand) {
                            $discountsAll[] = intval($resultDiscountsUser[$i]['UF_DISCOUNT_VALUE']);
                        }
                    }
                }
            }
        }        
        return $discountsAll;
    }

    public static function getMaxDiscount($discountValues):int {
        if (!empty($discountValues)) {
            return intval(max($discountValues));
        }
        return 0;
    }

    public static function getPartnerID($idUser):string{
        return strval(CUser::GetByID($idUser)->Fetch()['UF_PARTNER_ID']);
    }  
}
?>