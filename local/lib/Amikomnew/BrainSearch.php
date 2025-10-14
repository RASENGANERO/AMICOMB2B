<?

namespace Amikomnew;
use CIBlockElement;
class BrainSearch {
    public static function getElementsIsBrand ($brandSearch) {
        $brands = self::getBrandID($brandSearch);
        if (empty($brands)) {
            return [];
        }
        $arFilter = [
            'IBLOCK_ID' => 29,
            'ACTIVE' => 'Y',
            'PROPERTY_BRAND' => $brands,
        ];
        $arSelect = [
            'ID',
            'IBLOCK_ID',
            'IBLOCK_SECTION_ID',
        ];
        $elements = [];
        $res = CIBlockElement::GetList(['SORT'=>'ASC'],$arFilter,false,false,$arSelect);
        while ($ob = $res->Fetch()) {
            $elements[] =  $ob['ID'];
        }
        return $elements;
    }
    public static function getBrandID ($brandSearch) {
        $arFilter = [
            'IBLOCK_ID' => 33,
            'ACTIVE' => 'Y',
            'NAME' => '%'.$brandSearch.'%',
        ];
        $brands = [];
        $res = CIBlockElement::GetList(['SORT'=>'ASC'],$arFilter,false,false,['ID']);
        while ($ob = $res->Fetch()) {
            $brands[] = $ob['ID'];
        }
        return $brands;
    }
    public static function getElementsArticle($searchArticle) {
        $arFields = [
            'ID',
            'IBLOCK_ID',
            'IBLOCK_SECTION_ID',
        ];
        $arFilter = [
            'IBLOCK_ID' => 29,
            'ACTIVE' => 'Y',
            'PROPERTY_CML2_ARTICLE' => '%'.strtoupper($searchArticle).'%',
        ];
        $elements = [];
        $res = CIBlockElement::GetList(['SORT'=>'ASC'],$arFilter,false,false,$arFields);
        while ($ob = $res->Fetch()) {
            $elements[] = $ob['ID'];
        }
        return $elements;
    }
}
?>