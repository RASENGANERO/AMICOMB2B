<?
$_SERVER['DOCUMENT_ROOT'] = "D:/OPENSERVER/domains/AMIKOMB2BNEW";
$prolog = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php";
include($prolog);
CModule::IncludeModule('iblock');
CModule::IncludeModule('form');



CModule::IncludeModule("sale");
CModule::IncludeModule("user");
CModule::IncludeModule('highloadblock');
//print_r($jsonData);
// Декодируем JSON-строку в массив
$arrayData = json_decode($jsonData, true);
print_r($arrayData);
//use AmikomB2B;



//use \AmikomB2B\InformIblockB2B;
//$obj = new InformIblockB2B($arrayData);
//$obj->checkElement($obj::IBLOCK_PARTNER_B2B);




//use \AmikomB2B\InformHLBlockB2B;
//$obj = new InformHLBlockB2B($arrayData);
//$obj->setManagers($obj::HLIBLOCK_MANAGERS);
//$obj->setPriceGroup($obj::HLIBLOCK_PRICE_GROUP);

//$obj->setPriceMatrix($obj::HLIBLOCK_PRICE_MATRIX);

//$discountsAll = \AmikomB2B\InformIblockB2B::getDiscounts('018e027a-edf1-11ea-817d-0cc47a7821bb', 'hiwatch');





/*for ($i = 0; $i < count($lk); $i++) {
	$res = $entityClass::getList([
		'select' => ['*'],
		'filter' => ['UF_XML_ID' => $this->partnerManagers[$i]['Guid']]
	])->fetch();
	if (!empty($res)) {
		$arFieldUpdate['UF_XML_ID'] = $this->partnerManagers[$i]['Guid'];
		$arFieldUpdate['UF_NAME'] = $this->partnerManagers[$i]['Name'];
		$arFieldUpdate['UF_FIO'] = $this->partnerManagers[$i]['Name'];
		$arFieldUpdate['UF_PHONE'] = '';
		$arFieldUpdate['UF_EMAIL'] = '';
		$entityClass::update($res['ID'],$arFieldUpdate);
	}
	else {
		$arFieldHLAdd['UF_XML_ID'] = $this->partnerManagers[$i]['Guid'];
		$arFieldHLAdd['UF_NAME'] = $this->partnerManagers[$i]['Name'];
		$arFieldHLAdd['UF_FIO'] = $this->partnerManagers[$i]['Name'];
		$arFieldHLAdd['UF_PHONE'] = '';
		$arFieldHLAdd['UF_EMAIL'] = '';
		$entityClass::add($arFieldHLAdd);
	}
}*/
/*$vrList = [];
$vrData = CUser::GetList('sort','asc',['>ID'=>10],[]);
while ($ob = $vrData->Fetch()) {
	$vrList[] = ['ID'=>$ob['ID'],'NAME'=>$ob['NAME'],'LOGIN'=>$ob['LOGIN']];	
}
print_r($vrList);
*/


// Печатаем результат
//print_r($maxDiscounts);
//print_r($uniqueIds);
$res = CIBlockElement::GetList(['SORT'=>'ASC'],['IBLOCK_ID'=>22],false,false,['DETAIL_TEXT']);
while($ob = $res->Fetch()){
	print_r($ob);
}
?>