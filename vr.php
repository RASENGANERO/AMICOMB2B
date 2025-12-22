<?
$_SERVER['DOCUMENT_ROOT'] = "D:/OPENSERVER/domains/AMIKOMB2BNEW";
$prolog = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php";
include($prolog);
CModule::IncludeModule('iblock');
CModule::IncludeModule('form');



CModule::IncludeModule("sale");
CModule::IncludeModule("user");
CModule::IncludeModule('highloadblock');
$jsonData = file_get_contents('answer.txt');
//print_r($jsonData);
// Декодируем JSON-строку в массив
$arrayData = json_decode($jsonData, true);
print_r($arrayData);
//use AmikomB2B;



//use \AmikomB2B\InformIblockB2B;
//$obj = new InformIblockB2B($arrayData);
//$obj->checkElement($obj::IBLOCK_PARTNER_B2B);


$rsTemplates = CSite::GetTemplateList("s1");
while($arTemplate = $rsTemplates->Fetch())
{
	$result[]  = $arTemplate;
}
echo "<pre>"; print_r($result); echo "</pre>";
$res = CUser::GetByID($USER->GetID())->Fetch();
print_r($res);

//use \AmikomB2B\InformHLBlockB2B;
//$obj = new InformHLBlockB2B($arrayData);
//$obj->setManagers($obj::HLIBLOCK_MANAGERS);
//$obj->setPriceGroup($obj::HLIBLOCK_PRICE_GROUP);

//$obj->setPriceMatrix($obj::HLIBLOCK_PRICE_MATRIX);

//$discountsAll = \AmikomB2B\InformIblockB2B::getDiscounts('018e027a-edf1-11ea-817d-0cc47a7821bb', 'hiwatch');




?>