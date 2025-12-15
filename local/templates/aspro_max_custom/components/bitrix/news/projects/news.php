<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?// intro text?>

<?global $isHideLeftBlock, $arTheme;?>
<?
if(isset($arParams["TYPE_LEFT_BLOCK"]) && $arParams["TYPE_LEFT_BLOCK"]!='FROM_MODULE'){
	$arTheme['LEFT_BLOCK']['VALUE'] = $arParams["TYPE_LEFT_BLOCK"];
}

if(isset($arParams["SIDE_LEFT_BLOCK"]) && $arParams["SIDE_LEFT_BLOCK"]!='FROM_MODULE'){
	$arTheme['SIDE_MENU']['VALUE'] = $arParams["SIDE_LEFT_BLOCK"];
}
?>
<?
if(!$isHideLeftBlock && $APPLICATION->GetProperty("HIDE_LEFT_BLOCK_LIST") == "Y"){
	$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y");
}
?>
<?
if(isset($arParams["TYPE_HEAD_BLOCK"]) && $arParams["TYPE_HEAD_BLOCK"]=='FROM_MODULE'){
	if($arTheme["PROJECTS_SHOW_HEAD_BLOCK"]['VALUE'] == 'N'){
		$arParams['TYPE_HEAD_BLOCK'] = 'none';
	}else{
		$arParams['TYPE_HEAD_BLOCK'] = $arTheme["PROJECTS_SHOW_HEAD_BLOCK"]["DEPENDENT_PARAMS"]["SHOW_HEAD_BLOCK_TYPE"]['VALUE'];
	}
}
?>

<?$bIsHideLeftBlock = ($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") == "Y");?>

<div class="text_before_items"><?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		[
			"AREA_FILE_SHOW" => "page",
			"AREA_FILE_SUFFIX" => "inc",
			"EDIT_TEMPLATE" => ""
		]
	);?></div>
<?
$arItemFilter = CMax::GetIBlockAllElementsFilter($arParams);

if($arParams['CACHE_GROUPS'] == 'Y')
{
	$arItemFilter['CHECK_PERMISSIONS'] = 'Y';
	$arItemFilter['GROUPS'] = $GLOBALS["USER"]->GetGroups();
}

$itemsCnt = CMaxCache::CIblockElement_GetList(["CACHE" => ["TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"])]], $arItemFilter, []);?>

<?if(!$itemsCnt):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
<?else:?>

	<?$this->SetViewTarget('product_share');?>
		<?if($arParams['USE_RSS'] !== 'N'):?>
			<div class="colored_theme_hover_bg-block">
				<?=CMax::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);?>
			</div>
		<?endif;?>
	<?$this->EndViewTarget();?>

	
	<?/* start tags */?>
	<?
	if(isset($arItemFilter['CODE']))
	{
		unset($arItemFilter['CODE']);
		unset($arItemFilter['SECTION_CODE']);
	}
	if(isset($arItemFilter['ID']))
	{
		unset($arItemFilter['ID']);
		unset($arItemFilter['SECTION_ID']);
	}
	?>
	<?// show top map?>
	<?include('include/top-map.php')?>
	<?
	$arTags = [];
	
	$arElements = CMaxCache::CIblockElement_GetList(['CACHE' => ['TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y']], $arItemFilter, false, false, ['ID', 'TAGS']);

	foreach($arElements as $arElement)
	{
		if($arElement['TAGS'])
		{
			$arTags[] = explode(',', (string) $arElement['TAGS']);
		}
	}
	?>
	<?$this->__component->__template->SetViewTarget('under_sidebar_content');?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:search.tags.cloud",
			"main",
			[
				"CACHE_TIME" => "86400",
				"CACHE_TYPE" => "A",
				"CHECK_DATES" => "Y",
				"COLOR_NEW" => "3E74E6",
				"COLOR_OLD" => "C0C0C0",
				"COLOR_TYPE" => "N",
				"TAGS_ELEMENT" => $arTags,
				"FILTER_NAME" => $arParams["FILTER_NAME"],
				"FONT_MAX" => "50",
				"FONT_MIN" => "10",
				"PAGE_ELEMENTS" => "150",
				"PERIOD" => "",
				"PERIOD_NEW_TAGS" => "",
				"SHOW_CHAIN" => "N",
				"SORT" => "NAME",
				"TAGS_INHERIT" => "Y",
				"URL_SEARCH" => SITE_DIR."search/index.php",
				"WIDTH" => "100%",
				"arrFILTER" => ["iblock_aspro_max_content"],
				"arrFILTER_iblock_aspro_max_content" => [$arParams["IBLOCK_ID"]]
			], $component, ['HIDE_ICONS' => 'Y']
		);?>
	<?$this->__component->__template->EndViewTarget();?>
	<?/* end tags */?>
		

	<?global $isMenu;?>
	
	<?if(!$isMenu):?>
		<div class="sub_container fixed_wrapper">
		<div class="row">
			<div class="<?=($arParams["SHOW_ASK_BLOCK"]=='Y') ? 'col-md-9' : 'col-md-12';?>">
	<?endif;?>
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower((string) $_REQUEST['ajax']) == 'y'))
	{
		$APPLICATION->RestartBuffer();
	}?>
	<?// section elements?>
	<?if($arParams['TYPE_HEAD_BLOCK']=='sections_mix' || $arParams['TYPE_HEAD_BLOCK']=='years_mix'):?>
		<div class="mixitup-container">
	<?endif;?>
		    
	<?if($arParams['TYPE_HEAD_BLOCK']=='sections_links'){
		$useSectionsLink = true;
	}elseif($arParams['TYPE_HEAD_BLOCK']=='years_links'){
		$useDateLink = true;
	}?>
	
	<?@include_once('include/head_block.php');?>
	<?$sViewElementsTemplate = ($arParams["SECTION_ELEMENTS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["PROJECTS_PAGE"]["VALUE"] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"]);?>
	<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>
	<?if($arParams['TYPE_HEAD_BLOCK']=='sections_mix' || $arParams['TYPE_HEAD_BLOCK']=='years_mix'):?>
		</div>
	<?endif;?>
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower((string) $_REQUEST['ajax']) == 'y'))
	{
		die();
	}?>
			    
	<?if($arParams["SHOW_ASK_BLOCK"]=='Y'):?>
		<?// ask block?>
		<?ob_start();?>
			<div class="ask_a_question">
				<div class="inner">
					<div class="text-block">
						<?$APPLICATION->IncludeComponent(
							 'bitrix:main.include',
							 '',
							 [
								  'AREA_FILE_SHOW' => 'page',
								  'AREA_FILE_SUFFIX' => 'ask',
								  'EDIT_TEMPLATE' => ''
							 ]
						);?>
					</div>
				</div>
				<div class="outer">
					<span><span class="btn btn-default btn-lg white animate-load" data-event="jqm" data-param-form_id="ASK" data-name="question"><span><?=(strlen((string) $arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : GetMessage('S_ASK_QUESTION'))?></span></span></span>
				</div>
			</div>
		<?$html = ob_get_contents();?>
		<?ob_end_clean();?>
	<?endif;?>

	<?if(!$isMenu):?>
			</div>
			<?if($arParams["SHOW_ASK_BLOCK"]=='Y'):?>
			<div class="col-md-3  with-padding-left hidden-xs hidden-sm">
				<div class="fixed_block_fix"></div>
				<div class="ask_a_question_wrapper">
					<?=$html;?>
				</div>
			</div>
			<?endif;?>
		</div>
		</div>
	<?else:?>
		<?$this->SetViewTarget('under_sidebar_content');?>
			<?=$html;?>
		<?$this->EndViewTarget();?>
	<?endif;?>
<?endif;?>

