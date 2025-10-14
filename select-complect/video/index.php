<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Подобор комплекта видеонаблюдения");
?>
<div class="select-complect-maincontainer">
	<div class="select-complect-container">
		<a class="complect-url-active select-complect-url" href="/select-complect/video/">Подбор комплекта видеонаблюдения</a>
		<a class="select-complect-url" href="/select-complect/intercom/">Подбор комплекта домофонии</a>
		<a class="select-complect-url" href="/select-complect/access/">Подбор системы контроля и управления доступом</a>
	</div>
	<div class="select-comp-form">
	<?$APPLICATION->IncludeComponent(
	"bitrix:form.result.new", 
	"select-complect-video-inline", 
	array(
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "Y",
		"AJAX_OPTION_SHADOW" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "",
		"EDIT_URL" => "",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"LIST_URL" => "",
		"SEF_MODE" => "N",
		"SUCCESS_URL" => "",
		"USE_EXTENDED_ERRORS" => "N",
		"WEB_FORM_ID" => "16",
		"COMPONENT_TEMPLATE" => "select-complect-video-inline",
		"VARIABLE_ALIASES" => array(
			"WEB_FORM_ID" => "WEB_FORM_ID",
			"RESULT_ID" => "RESULT_ID",
		)
	),
	false
);?>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>