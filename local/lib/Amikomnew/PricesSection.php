<?
namespace Amikomnew;
require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
use CIBlockSection;
use CIBlockElement;
class PricesSection{
    public static function removeSectNotElem($sectionArr){
        $arSelect = [
            'ID', 
            'IBLOCK_ID',
        ];
        $arFilter = [
            'IBLOCK_ID' => 33,
            '!PROPERTY_BRAND_PRICE'=>false,
            'ACTIVE'=>'Y',
        ];
        $sectSectan = $sectionArr['SECTIONS'];
        $arSections['SECTIONS'][] = $sectSectan[0];
        for($i=1; $i<count($sectSectan); $i++) {
            $arFilter['SECTION_ID'] =$sectSectan[$i]['ID'];
            $count = CIBlockElement::GetList([], $arFilter, false, false,$arSelect)->SelectedRowsCount();
            if ($count !== 0){
                $arSections['SECTIONS'][] = $sectSectan[$i];
            }
        }
        return $arSections;
    }
    public static function checkFilter($arrDataFilter) {
        $pregValues = [
            '/[A-Za-z]/',
            '/[А-Яа-яЁё]/u',
            '/d/'
        ];
        $statusVals = [];
        foreach ($pregValues as $pattern) {
            $check = false;
            foreach ($arrDataFilter as $value) {
                if (preg_match($pattern, $value)) {
                    $check = true;
                    break;
                }
            }
            $statusVals[] = $check ? 1 : 0;
        }
        return $statusVals;
    }
}

?>