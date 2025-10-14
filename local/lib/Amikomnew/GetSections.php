<?
namespace Amikomnew;

require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
use CIBlockSection;
class GetSections{
    public static function checkShowProducts($catalogUrl):bool {
        $catalogUrl = end(array_filter(explode('/',$catalogUrl)));
        if ($catalogUrl === 'catalog') {
            return true;
        }
        else {
            $checkSection = CIBlockSection::GetList(['SORT'=>'ASC'], ['CODE' => $catalogUrl])->SelectedRowsCount();
            if (intval($checkSection) !== 0) {
                return false;
            }
            else {
                return true;
            }
        }
    }
    public static function getClearURL($val){
        $clearURL = '';
        if (strpos('?',$val)!==-1) {
            $clearURL = explode('?',strval($val))[0];
        }
        else {
            $clearURL = $val;
        }
        return $clearURL;
    }
    public static function GetCurrentSection($val, $name, $iBlockID){
        $sectionID = 0;
        if (strpos($val,'?') !== false){
            $valFilterSection = explode('?',$val)[0];
            $valFilterSection = array_values(array_filter(explode('/',$valFilterSection)));
            $filter = [
                'IBLOCK_ID' => $iBlockID,
                'CODE' => $valFilterSection[1],
                'ACTIVE' => 'Y'
            ];
            $sectionID = CIBlockSection::GetList(['SORT'=>'ASC'], $filter)->Fetch()['ID'];
        }
        if ($val === "/".$name."/") {
            $sectionID = 0;
        }
        else {
            $sectionCode = str_replace($name,'',$val);
            $sectionCode = str_replace('/','',$sectionCode);
            $filter = [
                'IBLOCK_ID' => $iBlockID,
                'CODE' => $sectionCode,
                'ACTIVE' => 'Y'
            ];
            $sectionID = CIBlockSection::GetList(['SORT'=>'ASC'], $filter)->Fetch()['ID'];
        }
        return strval($sectionID);
    }
    public static function getAllSectionsIblock($IBLOCK_ID, $name){
        global $APPLICATION;
        $arSections['SECTIONS'] = array();
        $res = CIBlockSection::GetList(
            ['SORT' => 'ASC'],
            ['IBLOCK_ID' => $IBLOCK_ID],
            false,
            ['ID','NAME','CODE','SORT'],
        );
        while ($section = $res->GetNext()) {
            $section['SECTION_PAGE_URL'] = "/".$name."/".$section['CODE'].'/';
            $arSections['SECTIONS'][] = $section; // Добавляем секции в массив результата
        }

        array_unshift($arSections['SECTIONS'] , [
            'SORT' => 1,
            'NAME' => 'Все',
            'LIST_PAGE_URL' => "/".$name."/",
            'SECTION_PAGE_URL' => "/".$name."/",
        ]);

        $arSections['SECTIONS'] = array_values($arSections['SECTIONS']);

        $urlActive = $APPLICATION->GetCurPage(false);

        usort($arSections['SECTIONS'], function($arrasFirst, $arrasLast) {
            return $arrasFirst['SORT'] <=> $arrasLast['SORT'];
        });

        for($i=0;$i<count($arSections['SECTIONS']);$i++){
            $arSections['SECTIONS'][$i]['ACTIVE_PAGE_CLASS'] = ($arSections['SECTIONS'][$i]['SECTION_PAGE_URL'] === $urlActive) ? 'section-iblock-active' : 'section-iblock-not-active';
        }
        return $arSections;
    }
    public static function getMainSectionsCatalog() {
        $arFields = [
            'IBLOCK_ID',
            'NAME',
            'ID',
            'CODE',
            'PREVIEW_PICTURE'
        ];
        $arFilter = [
            'IBLOCK_ID' => 29,
            'DEPTH_LEVEL' => 1
        ];
        $vrData = [];
        
        $brands = CIBlockSection::GetList(['SORT'=>'ASC'],$arFilter,false,$arFields, ['nTopCount'=>8]);
        while($res = $brands->Fetch()){
            $res['CODE'] = '/catalog/'.$res['CODE'].'/';
            $vrData[] = $res;
        }
        return $vrData;
    }
}
?>