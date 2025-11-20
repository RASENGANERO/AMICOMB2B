<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
header('Content-type: application/json');
CModule::IncludeModule('iblock');
function getResultsSearch($value){
	$arrData = [];
	$arSelect = [
		'ID',
		'IBLOCK_ID',
		'NAME',
		'PROPERTY_FILE_DOCUMENT',
		'PROPERTY_BRAND_DOCUMENT',
	];
	$arFilter = [
		"IBLOCK_ID" => 10,
		"ACTIVE" => 'Y',
		"NAME" => '%'.$value.'%'
	];
	$rsElements = CIBlockElement::GetList(['SORT'=>'NAME'], $arFilter, false, ['nTopCount'=>30], $arSelect);
	while($arElement = $rsElements->Fetch())
	{
		$documentPath = CFile::GetByID($arElement['PROPERTY_FILE_DOCUMENT_VALUE'])->Fetch()['SRC'];
		$documentBrand = CIBlockElement::GetByID($arElement['PROPERTY_BRAND_DOCUMENT_VALUE'])->Fetch();
		$arrData[] = [
			'NAME' => $arElement['NAME'],
			'DOC_PATH' => $documentPath,
			'BRAND_NAME' => $documentBrand['NAME'],
			'BRAND_URL' => '/brands/'.$documentBrand['CODE'].'/',
		];		
	}
	return $arrData;	
}
if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = getResultsSearch($_POST['query']);
	echo json_encode(['values'=>$data]);
}
?>