<?
$_SERVER['DOCUMENT_ROOT'] = "D:/OPENSERVER/domains/AMIKOMB2BNEW";
$prolog = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php";
include($prolog);
CModule::IncludeModule('iblock');
CModule::IncludeModule('form');


$arValuesDiscount = [];
// Получаем значения свойства
$propValuesRes = CIBlockElement::GetProperty(33, 310731, 'sort', 'asc', ['CODE' => 'B2B_DISCOUNT']);

while ($res = $propValuesRes->Fetch()) {
    $arValuesDiscount[] = $res['VALUE'];
}
print_r($arValuesDiscount);

$res = CUser::GetByID(2306)->Fetch()['UF_PARTNER_ID'];
return $res;
print_r($res)
?>