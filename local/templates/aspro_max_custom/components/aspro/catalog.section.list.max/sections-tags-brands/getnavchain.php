<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
header('Content-type: application/json');
CModule::IncludeModule('iblock');
function getNavChain($value, $brandCode){
    $arSelect = [
        'ID',
        'CODE',
        'IBLOCK_ID'
    ];

    $list = CIBlockSection::GetNavChain(29,intval($value), $arSelect , true);
    
    $arrData = [];
    foreach ($list as $arSectionPath){
        $arrData[] = $arSectionPath['CODE'];
    }
    
    $arrData = '/catalog/'.implode('/',$arrData).'/filter/brand-is-'.$brandCode.'/apply/';
    $arrData = str_replace('\\', '',  $arrData);
	return $arrData;	
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $value = $_POST['section_id'];
    $brandCode = $_POST['brand_code'];
	$data = getNavChain($value,$brandCode);
	echo json_encode(['URL' => $data]);
}
?>