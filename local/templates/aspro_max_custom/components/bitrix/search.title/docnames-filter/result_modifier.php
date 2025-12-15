<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult["ELEMENTS"] = [];
$arSelect = [
	"ID",
	"IBLOCK_ID",
	"PREVIEW_TEXT",
	"PREVIEW_PICTURE",
	"DETAIL_PICTURE",
	"NAME"
];
$arFilter = [
	"IBLOCK_ID" => 10,
	"ACTIVE" => "Y",
	"NAME" => '%'.$_REQUEST['q'].'%'
];
$rsElements = CIBlockElement::GetList(['SORT'=>'ID'], $arFilter, false, ['nTopCount'=>10], $arSelect);
while($arElement = $rsElements->Fetch())
{
	$arResult["ELEMENTS"][$arElement["ID"]] = $arElement;	
	
}

?>