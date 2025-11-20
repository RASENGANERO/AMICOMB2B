<?
CModule::IncludeModule('iblock');
class Sections{
    public static function GetCurrentSection($val){
        $sectionID = 0;
        if ($val === "/docs/") {
            return 0;
        }
        else{
            $sectionCode = str_replace('docs','',$val);
            $sectionCode = str_replace('/','',$sectionCode);
           
            $filter = [
                'IBLOCK_ID' => 10,
                'CODE' => $sectionCode,
                'ACTIVE' => 'Y'
            ];
            $sectionID = CIBlockSection::GetList(['SORT'=>'ASC'], $filter)->Fetch()['ID'];
        }
        return strval($sectionID);
    }
}

?>