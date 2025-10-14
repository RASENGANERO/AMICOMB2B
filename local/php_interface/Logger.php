<?
\Bitrix\Main\Loader::includeModule('artdragon.stat');
class Logger {
    public static function addElement ($arFields) {
        if (intval($arFields['IBLOCK_ID']) === 29) {
            \artdragon\stat\AddStatistics::addData($arFields, false);
        }
    }
    public static function updateElement ($arFields) {
        if (intval($arFields['IBLOCK_ID']) === 29) {
            \artdragon\stat\AddStatistics::addData($arFields, true);
        }
    }
    public static function updateElementNewPrice ($arFields) {
        $fields = $arFields->getParameter('fields');
        \artdragon\stat\AddStatistics::addNewPrice(intval($fields['PRODUCT_ID']), floatval($fields['PRICE']));
    }
}
?>