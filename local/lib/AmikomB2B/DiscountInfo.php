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
    public static function getPriceMain ($idProduct) { // функция получения цены товара (BASE)
        $allProductPrices = \Bitrix\Catalog\PriceTable::getList([
            "select" => ["PRICE"],
            "filter" => [
                "=PRODUCT_ID" => intval($idProduct),
                "=CATALOG_GROUP_ID" => 2
            ],
        ])->fetch();
        return $allProductPrices['PRICE'];
    }
    
    public static function getPrintValueMain($printValue) {
        return number_format($printValue, 0, ',', ' ');
    }
    public static function generateDiscountsHTML($valuesPrices) {
        $valHTML = '';
        if (intval($valuesPrices['BASE']['VALUE']) === 0 || intval($valuesPrices['BASE']['DISCOUNT_VALUE']) === 0 ) {
            $valHTML ='<div class="cost prices clearfix">
                        <div class="with_matrix price_matrix_wrapper">
                            <div class="prices-wrapper">
                                <div class="price font-bold font_mxs">По запросу</div>
                            </div>
                        </div>
                    </div>';
        }
        if (empty($valuesPrices['BASE']['DISCOUNT_DIFF_PERCENT'])) {
            $valHTML = '<div class="cost prices clearfix">
                            <div class="with_matrix price_matrix_wrapper">
                                <div class="prices-wrapper">
                                    <div class="price font-bold font_mxs">
                                        <div class="price_value_block values_wrapper">
                                            <span class="price_value">'.self::getPrintValueMain($valuesPrices['BASE']['VALUE']).'</span><span class="price_currency"> ₽</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
        }
        if (!empty($valuesPrices['BASE']['DISCOUNT_DIFF_PERCENT'])) {
            $valHTML = '<div class="cost prices clearfix">
                            <div class="with_matrix price_matrix_wrapper">
                                <div class="prices-wrapper">
                                    <div class="price font-bold font_mxs">
                                        <div class="price_value_block values_wrapper">
                                            <span class="price_value">'.$valuesPrices['BASE']['PRINT_DISCOUNT_VALUE'].'</span>
                                        </div>
                                    </div>
                                    <div class="price discount">
                                        <span class="discount values_wrapper font_xs muted">
                                        <span class="price_value">'.self::getPrintValueMain($valuesPrices['BASE']['VALUE']).'</span><span class="price_currency"> ₽</span></span>
                                    </div>
                                </div>
                                <div class="sale_block matrix">
                                    <div class="sale_wrapper font_xxs">
                                        <div class="sale-number rounded2">
                                            <div class="value">-<span>'.$valuesPrices['BASE']['DISCOUNT_DIFF_PERCENT'].'</span>%</div>
                                            <div class="inner-sale rounded1">
                                                <div class="text">
                                                    <span class="title">Экономия</span>
                                                    <span class="values_wrapper">
                                                        <span class="price_value">'.self::getPrintValueMain($valuesPrices['BASE']['DISCOUNT_DIFF']).'</span>
                                                        <span class="price_currency"> ₽</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                    </div>';
        }
        return $valHTML;


    }

}
?>