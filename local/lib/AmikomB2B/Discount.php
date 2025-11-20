<?
class Discount {
    const IBLOCK_BRANDS = 33;
    const HLBLOCK_MATRIX = 10;
    public static function getBrandDiscount ($idBrand):array {
        $propValuesList = [];
        $propValuesRes = CIBlockElement::GetProperty(self::IBLOCK_BRANDS,$idBrand,'sort','asc',['CODE' => 'B2B_DISCOUNT']);
        while ($res = $propValuesRes->Fetch()) {
            $propValuesList[] = $res['VALUE'];
        }
        return $propValuesList;
    }

    public static function getEntity($idHLBlock) {
        $arHLBlock = Bitrix\Highloadblock\HighloadBlockTable::getById($idHLBlock)->fetch();
        $obEntity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHLBlock);
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
        $resultDiscountsUser = self::checkValuesDiscountByUser($idUserUF);
        if (!empty($resultDiscountsUser)) {
            for ($i = 0; $i < count($resultDiscountsUser); $i++) {
                if (in_array($resultDiscountsUser[$i]['UF_PRICE_GROUP_ID'],$discountsBrand)) {
                    $discountsAll[] = intval($resultDiscountsUser[$i]['UF_DISCOUNT_VALUE']);
                }
            }
        }
        sort($discountsAll,SORT_NUMERIC);
        return $discountsAll;
    }

    public static function getMaxDiscount($discountValues) {
        return max($discountValues);
    }

    public static function getPartnerID($idUser){
        $res = CUser::GetByID($idUser)->Fetch()['UF_PARTNER_ID'];
        return $res;
    }  
}
?>