<?

$_SERVER['DOCUMENT_ROOT'] = "D:/OPENSERVER/domains/AMIKOMNEW";
$prolog = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php";
include($prolog);
CModule::IncludeModule('iblock');

$arSelect = [
    'ID', 
    'IBLOCK_ID', 
    'NAME',
    'PREVIEW_PICTURE',
    'DETAIL_PICTURE',
    'ACTIVE',
    'CODE',
    'DETAIL_TEXT',
    'DETAIL_TEXT_TYPE',
    'PREVIEW_TEXT',
    'DETAIL_TEXT_TYPE',
    'SORT',
    'ACTIVE_FROM',
    'ACTIVE_TO',
    'XML_ID',
    'IBLOCK_SECTION_ID',
    'PROPERTY_BRAND_PRICE'
];
$arFilter = [
    'IBLOCK_ID' => 33,
    'ACTIVE' => 'N'
];


$dtBlock = [];
//$dtFiles = [];
$k =0;
$valPath = '';
$arras = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
while ($ob = $arras->GetNextElement()) {
    $valuesFields = $ob->GetFields();
    $valuesFields['PATH_DETAIL'] = CFile::GetByID($valuesFields['DETAIL_PICTURE'])->Fetch()['SRC'];
    $valuesFields['PATH_PREV'] = CFile::GetByID($valuesFields['PREVIEW_PICTURE'])->Fetch()['SRC'];
    $valuesFields['PATH_BRAND'] = CFile::GetByID($valuesFields['PROPERTY_BRAND_PRICE_VALUE'])->Fetch()['SRC']; 
    $dtBlock[]=$valuesFields;
    $k+=1;
}

print_r($dtBlock);