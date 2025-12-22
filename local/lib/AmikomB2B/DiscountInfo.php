<?
namespace AmikomB2B;
use CIBlockElement;
use Bitrix\Highloadblock\HighloadBlockTable;
use CUser;
class DiscountInfo {
    const IBLOCK_PRODUCTS = 29;
    const HLBLOCK_MATRIX = 10;
    
    public static function getPriceGroupID($idProduct) {
        $priceGrpVal = '';
        $dbPriceGroup = CIBlockElement::GetProperty(self::IBLOCK_PRODUCTS, $idProduct, ['sort' => 'asc'], ['CODE' => 'CML2_TRAITS']);
        while ($ob = $dbPriceGroup->GetNext()) {
            if(strval($ob['DESCRIPTION']) === 'ЦеноваяГруппа'){
                $priceGrpVal = $ob['VALUE'];
                break; 
            }
        }
        return $priceGrpVal;
    }
    public static function getDiscountUser($idPriceGroup,$idUserUF) {
        $arDataDiscounts = [];
        $arHLBlock = HighloadBlockTable::getById(self::HLBLOCK_MATRIX)->fetch();
        $obEntity = HighloadBlockTable::compileEntity($arHLBlock);
        $strEntityDataClass = $obEntity->getDataClass();
        $rsData = $strEntityDataClass::getList([
            'select' => ['UF_DISCOUNT_VALUE'],
            'filter' => ['UF_PRICE_GROUP' => $idPriceGroup, 'UF_PARTNER_ID'=> $idUserUF],
            'count_total' => 1,
        ]);
        if ($rsData->getCount() > 0) {
            while($obDiscount = $rsData->fetch()) {
                $arDataDiscounts[] = intval($obDiscount['UF_DISCOUNT_VALUE']);
            }
        }
        return $arDataDiscounts;
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