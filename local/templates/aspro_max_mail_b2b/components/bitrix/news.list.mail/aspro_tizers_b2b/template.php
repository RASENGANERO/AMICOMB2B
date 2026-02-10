<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
//$this->setFrameMode(true);
use CIBlockElement;

$itemIdList = [];
$arFilter = [
	'PROPERTY_SHOW_ON_INDEX_PAGE_VALUE' => 'Y',
	'ACTIVE' => 'Y',
	'IBLOCK_ID' => 13,
];
$resMail = CIBlockElement::GetList(['SORT'=>'ASC'],$arFilter,false,['nTopCount'=>4],['ID']);
while($ob = $resMail->Fetch()) {
	$itemIdList[] = $ob['ID'];
}
\Bitrix\Main\Mail\EventMessageThemeCompiler::includeComponent(
	"bitrix:catalog.show.products.mail",
	"aspro_tizers_b2b",
	Array(
		"LIST_ITEM_ID" => $itemIdList,
		"SITE_ADDRESS" => $arParams["SITE_ADDRESS"],
		"FROM_TEMPLATE" => $arParams["FROM_TEMPLATE"],
	)
);