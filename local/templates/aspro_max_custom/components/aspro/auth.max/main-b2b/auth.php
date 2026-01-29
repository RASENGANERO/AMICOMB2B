<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $USER, $arTheme;
\Bitrix\Main\Localization\Loc::loadMessages(__FILE__);
$APPLICATION->SetTitle(GetMessage("TITLE"));
$APPLICATION->SetPageProperty("TITLE_CLASS", "center");
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/phoneorlogin.min.js');

$bPopupAuth = (isset($_POST['POPUP_AUTH']) && $_POST['POPUP_AUTH'] === 'Y');
?>
<?if(!$bPopupAuth):?>
	<style>
		.left-menu-md, body .container.cabinte-page .maxwidth-theme .left-menu-md, .right-menu-md, body .container.cabinte-page .maxwidth-theme .right-menu-md{display:none !important;}
		.content-md{width:100%;}
		.border_block{border:none;}
	</style>
<?endif;?>
<?if(!$USER->IsAuthorized()):?>
	<?
	$arResult['SEF_FOLDER'] =  '/b2b/';
	?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:system.auth.form",
		"main",
		[
			"AUTH_URL" => $arResult["SEF_FOLDER"].$arResult["URL_TEMPLATES"]["auth"],
			"REGISTER_URL" => $arResult["SEF_FOLDER"].$arResult["URL_TEMPLATES"]["registration"],
			"FORGOT_PASSWORD_URL" => $arResult["SEF_FOLDER"].$arResult["URL_TEMPLATES"]["forgot_password"],
			"CHANGE_PASSWORD_URL" => $arResult["SEF_FOLDER"].$arResult["URL_TEMPLATES"]["change_password"],
			"PROFILE_URL" => $arParams["SEF_FOLDER"],
			"SHOW_ERRORS" => "Y",
			"POPUP_AUTH" => $bPopupAuth ? 'Y' : 'N',
		]
	);?>
<?elseif(strlen((string) $_REQUEST['backurl'])):?>
	<?LocalRedirect("/b2b/");?>
<?else:?>
	<?$url = $arResult['SEF_FOLDER'];
	LocalRedirect("/b2b/");
	?>
	<?if (
		!str_contains((string) $_SERVER['HTTP_REFERER'], (string) $url) &&
		!str_contains((string) $_SERVER['HTTP_REFERER'], SITE_DIR.'ajax/form.php')
	):?>
		<?LocalRedirect("/b2b/");?>
	<?endif;?>
<?endif;?>