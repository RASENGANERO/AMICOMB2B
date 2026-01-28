<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
use Amikomnew;
\Amikomnew\CookieCRM::setCookieCRM();
?>
<?if($_GET["debug"] == "y")
	error_reporting(E_ERROR | E_PARSE);
IncludeTemplateLangFile(__FILE__);
use Bitrix\Main\Page\Asset;
global $APPLICATION, $arRegion, $arSite, $arTheme, $bIndexBot, $bIframeMode;
$arSite = CSite::GetByID(SITE_ID)->Fetch();
$htmlClass = ($_REQUEST && isset($_REQUEST['print']) ? 'print' : false);
$bIncludedModule = (\Bitrix\Main\Loader::includeModule("aspro.max"));?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" <?=($htmlClass ? 'class="'.$htmlClass.'"' : '')?> <?=($bIncludedModule ? CMax::getCurrentHtmlClass() : '')?>>
<head>
	<title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->ShowMeta("viewport");?>
	<?$APPLICATION->ShowMeta("HandheldFriendly");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
	<?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
	<?$APPLICATION->ShowHead();?>
	
	<?Asset::getInstance()->addString('<link rel="canonical" href="http://site.ru' . str_replace('index.php', '', $APPLICATION->GetCurPage(true)) . '" />');?>
	<?Asset::getInstance()->addJs("/local/templates/aspro_max_custom_b2b/ajax/jquery.js");?>
	<?Asset::getInstance()->addJs("/local/templates/aspro_max_custom_b2b/ajax/brandDownload.js");?>
	<?Asset::getInstance()->addJs("/local/templates/aspro_max_custom_b2b/ajax/setCookie.js");?>
	<?Asset::getInstance()->addJs("/local/templates/aspro_max_custom_b2b/ajax/b2b/Organization.js");?>
	<?$APPLICATION->AddHeadString('<script>BX.message('.CUtil::PhpToJSObject( $MESS, false ).')</script>', true);?>
	<?if($bIncludedModule)
		CMax::Start(SITE_ID);?>
	<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/header_include/head.php'));?>
</head>
<?$bIndexBot = $bIncludedModule ? CMax::checkIndexBot() : false;?>
<body class="<?=($bIndexBot ? "wbot" : "");?> site_<?=SITE_ID?> <?=($bIncludedModule ? CMax::getCurrentBodyClass() : '')?>" id="main" data-site="<?=SITE_DIR?>">

	<?if(!$bIncludedModule):?>
		<?$APPLICATION->SetTitle(GetMessage("ERROR_INCLUDE_MODULE_ASPRO_MAX_TITLE"));?>
		<center><?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?></center></body></html><?die();?>
	<?endif;?>
	
	<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/header_include/body_top.php'));?>

	<?$arTheme = $APPLICATION->IncludeComponent("aspro:theme.max", ".default", array("COMPONENT_TEMPLATE" => ".default"), false, array("HIDE_ICONS" => "Y"));?>
	<?include_once('defines.php');?>
	<?CMax::SetJSOptions();?>

	<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'local/include/header-seminar/seminar-wrapper.php'));?>
	
	<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/header_include/under_wrapper1.php'));?>
	<div class="wrapper1 <?=($isIndex && $isShowIndexLeftBlock ? "with_left_block" : "");?> <?=CMax::getCurrentPageClass();?> <?$APPLICATION->AddBufferContent(array('CMax', 'getCurrentThemeClasses'))?>  ">
		<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/header_include/top_wrapper1.php'));?>

		<div class="wraps hover_<?=$arTheme["HOVER_TYPE_IMG"]["VALUE"];?>" id="content">

			<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/header_include/top_wraps.php'));?>

			<?if($isIndex):?>
				<?$APPLICATION->ShowViewContent('front_top_big_banner');?>
				<div class="wrapper_inner front <?=($isShowIndexLeftBlock ? "" : "wide_page");?> <?=$APPLICATION->ShowViewContent('wrapper_inner_class')?>">
			<?elseif(!$isWidePage):?>
				<div class="wrapper_inner <?=($isHideLeftBlock ? "wide_page" : "");?> <?=$APPLICATION->ShowViewContent('wrapper_inner_class')?>">
				<?if ($APPLICATION->GetCurPage() === '/b2b/'):?>
				<?
				$GLOBALS['filterBannersB2B'] = [
					'PROPERTY_SHOW_B2B_PAGE_VALUE' => 'Y'
				];
				$APPLICATION->IncludeComponent(
	"aspro:com.banners.max", 
	"top_big_banner_3_2_b2b", 
			array(
				"IBLOCK_TYPE" => "aspro_max_adv",
				"IBLOCK_ID" => "30",
				"TYPE_BANNERS_IBLOCK_ID" => "4",
				"SET_BANNER_TYPE_FROM_THEME" => "N",
				"NEWS_COUNT" => CIBlock::GetElementCount(30),
				"NEWS_COUNT2" => CIBlock::GetElementCount(30),
				"SORT_BY1" => "SORT",
				"SORT_ORDER1" => "ASC",
				"SORT_BY2" => "ID",
				"SORT_ORDER2" => "DESC",
				"PROPERTY_CODE" => array(
					0 => "TEXT_POSITION",
					1 => "TARGETS",
					2 => "TEXTCOLOR",
					3 => "URL_STRING",
					4 => "BUTTON1TEXT",
					5 => "BUTTON1LINK",
					6 => "BUTTON2TEXT",
					7 => "BUTTON2LINK",
					8 => "SHOW_B2B_PAGE",
				),
				"CHECK_DATES" => "Y",
				"AJAX_OPTION_STYLE" => "Y",
				"CACHE_GROUPS" => "N",
				"WIDE_BANNER" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"BANNER_TYPE_THEME" => "TOP",
				"COMPONENT_TEMPLATE" => "top_big_banner_3_2_b2b",
				"FILTER_NAME" => "filterBannersB2B",
				"USE_FILTER" => "Y",
				"BANNER_TYPE_THEME_CHILD" => "",
				"SECTION_ID" => "",
				"SHOW_MEASURE" => "Y",
				"PRICE_CODE" => array(
				),
				"STORES" => array(
					0 => "",
					1 => "",
					2 => "",
				),
				"CONVERT_CURRENCY" => "N"
			),
			false
		);
				?>
				<?endif;?>
			<?endif;?>
				
				<div class="container_inner flexbox flexbox--row-reverse flexbox--gap flexbox--gap-32 flexbox--align-start flexbox--justify-space-between <?=$APPLICATION->ShowViewContent('container_inner_class')?>">
				<?if(($isIndex && ($isShowIndexLeftBlock || $bActiveTheme)) || (!$isIndex && !$isHideLeftBlock)):?>
					<div class="right_block <?=(defined("ERROR_404") ? "error_page" : "");?> wide_<?=CMax::ShowPageProps("HIDE_LEFT_BLOCK");?> <?=$APPLICATION->ShowViewContent('right_block_class')?>">
				<?endif;?>
					<div class="middle <?=($is404 ? 'error-page' : '');?> <?=$APPLICATION->ShowViewContent('middle_class')?>">
						<?CMax::get_banners_position('CONTENT_TOP');?>
						<?if(!$isIndex):?>
							<div class="container">
								<?//h1?>
								<?if($isHideLeftBlock && !$isWidePage):?>
									<div class="maxwidth-theme">
								<?endif;?>
						<?endif;?>
						<?CMax::checkRestartBuffer();?>