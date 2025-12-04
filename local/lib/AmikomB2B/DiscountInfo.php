<?
namespace AmikomB2B;
use CIBlockElement;
use Bitrix\Highloadblock\HighloadBlockTable;
use CUser;
class DiscountInfo {
    const IBLOCK_BRANDS = 33;
    const IBLOCK_PRODUCTS = 29;
    const HLBLOCK_MATRIX = 10;

    public static function getBrandID ($productID) {
        $propValue = CIBlockElement::GetProperty(self::IBLOCK_PRODUCTS,$productID,'sort','asc',['CODE' => 'BRAND'])->Fetch()['VALUE'];
        return $propValue;
    }
    public static function getBrandDiscount ($idBrand):array {
        $propValuesList = [];
        $propValuesRes = CIBlockElement::GetProperty(self::IBLOCK_BRANDS,$idBrand,'sort','asc',['CODE' => 'B2B_DISCOUNT']);
        while ($res = $propValuesRes->Fetch()) {
            $propValuesList[] = $res['VALUE'];
        }
        return $propValuesList;
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


    public static function getDiscounts($idUserUF, $discountsBrand) {
        $discountsAll = [];
        if (!empty($idUserUF)) {
            $resultDiscountsUser = self::checkValuesDiscountByUser($idUserUF);
            if (!empty($resultDiscountsUser)) {
                for ($i = 0; $i < count($resultDiscountsUser); $i++) {
                    if (in_array($resultDiscountsUser[$i]['UF_PRICE_GROUP'],$discountsBrand)) {
                        $discountsAll[] = intval($resultDiscountsUser[$i]['UF_DISCOUNT_VALUE']);
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