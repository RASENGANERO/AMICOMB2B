<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?// intro text?>
<?
use Amikomnew;
$sectionsPrices = Amikomnew\GetSections::getAllSectionsIblock(33,'prices');
$sectionsPrices = Amikomnew\PricesSection::removeSectNotElem($sectionsPrices);
?>
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

<div class="text_before_items"><?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		Array(
			"AREA_FILE_SHOW" => "page",
			"AREA_FILE_SUFFIX" => "inc",
			"EDIT_TEMPLATE" => ""
		)
	);?></div>
<?
$arItemFilter = CMax::GetIBlockAllElementsFilter($arParams);

if($arParams['CACHE_GROUPS'] == 'Y')
{
	$arItemFilter['CHECK_PERMISSIONS'] = 'Y';
	$arItemFilter['GROUPS'] = $GLOBALS["USER"]->GetGroups();
}

$itemsCnt = CMaxCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, array());?>

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
	<?
	$arTags = array();
	
	$arElements = CMaxCache::CIblockElement_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y')), $arItemFilter, false, false, array('ID', 'TAGS'));

	foreach($arElements as $arElement)
	{
		if($arElement['TAGS'])
		{
			$arTags[] = explode(',', $arElement['TAGS']);
		}
	}
	?>
	<?
	global $NavNum; 
	$context = \Bitrix\Main\Application::getInstance()->getContext();
	if($NavNum){
		$pagen = $NavNum;
	}else{
		$pagen = 2;
	}
	$numPage = $context->getRequest()->get("PAGEN_".$pagen) ?? 1;
	$arGroup =  array("iNumPage" => $numPage, "nPageSize" => $arParams['NEWS_COUNT']);
	$arSelect = array('ID', 'NAME','PREVIEW_TEXT', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'DATE_CREATE');
	$arElement = CMaxCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "Y"), $arParams['SORT_BY1'] => $arParams['SORT_ORDER1'], $arParams['SORT_BY2'] => $arParams['SORT_ORDER2']), $arItemFilter, false, $arGroup, $arSelect);
	if($pagen != $NavNum){
		$NavNum = $pagen - 1;
	}

	$arSite = \CSite::GetByID(SITE_ID)->Fetch();

	$CURRENT_PAGE = (CMain::IsHTTPS()) ? "https://" : "http://";
	$CURRENT_PAGE .= $_SERVER["HTTP_HOST"];
	$SITE_DOMAIN = $CURRENT_PAGE;
	$CURRENT_PAGE .= $APPLICATION->GetCurUri();

	foreach($arElement as $key => $element):
		$arSchema[] = array(
			"@context" => "https://schema.org",
			"@type" => "NewsArticle",
			"url" => $CURRENT_PAGE,
			"publisher" => array(
				"@type" => "Organization",
      			"name" => $arSite['NAME']
			),
			"headline" => $element['NAME'],
			"articleBody" => $element['PREVIEW_TEXT'],
			"datePublished" => $element['DATE_CREATE']
		);
		if($element['PREVIEW_PICTURE']){
			$arSchema[$key]['image'][] = $SITE_DOMAIN.CFile::GetPath($element['PREVIEW_PICTURE']);
		}
		if($element['DETAIL_PICTURE']){
			$arSchema[$key]['image'][] = $SITE_DOMAIN.CFile::GetPath($element['DETAIL_PICTURE']);
		}
	endforeach;
	?>
	<script type="application/ld+json"><?=str_replace("'", "\"", CUtil::PhpToJSObject($arSchema, false, true));?></script>
	<?$this->__component->__template->SetViewTarget('under_sidebar_content');?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:search.tags.cloud",
			"main",
			Array(
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
				"arrFILTER" => array("iblock_aspro_max_content"),
				"arrFILTER_iblock_aspro_max_content" => array($arParams["IBLOCK_ID"])
			), $component, array('HIDE_ICONS' => 'Y')
		);?>
	<?$this->__component->__template->EndViewTarget();?>
	<?/* end tags */?>
	
	<?// group elements by sections?>
	<div class="prices-section-elements">
	<?foreach($sectionsPrices['SECTIONS'] as $SID => $arSection):?>
		<?if (!$bIsAjax):?>
		<div id="<?=$this->GetEditAreaId($arSection['ID'])?>" class="tab-pane <?=(!$si++ || !$arSection['ID'] ? 'active' : '')?>">
	
			<a class="<?=$arSection['ACTIVE_PAGE_CLASS']?> title prices-font pull-left section-btn btn-lg has-ripple" href="<?=$arSection['SECTION_PAGE_URL']?>"><?=$arSection['NAME']?></a>
			<?// section description text/html?>
			<?if(strlen($arSection['DESCRIPTION']) && strpos($_SERVER['REQUEST_URI'], 'PAGEN') === false):?>
				<div class="text_before_items">
					<?=$arSection['DESCRIPTION']?>
				</div>
				<?if($arParams['SHOW_SECTION_DESC_DIVIDER'] == 'Y'):?>
					<hr class="sect-divider" />
				<?endif;?>
			<?endif;?>


			<?// show section items?>
			<?if($arParams['VIEW_TYPE'] !== 'accordion'):?>
				<div class="row sid items <?=($arParams['VIEW_TYPE'] == 'table' ? 'flexbox' : '')?><?=($arParams['MOBILE_SCROLLED'] === 'Y' ? ' mobile-overflow mobile-margin-16 mobile-compact swipeignore' : '');?>">
			<?endif;?>
		<?endif;?>
		<?if (!$bIsAjax):?>
			<?if($arParams['VIEW_TYPE'] !== 'accordion'):?>
				</div>
			<?endif;?>
		</div>
		<?endif;?>
	<?endforeach;?>
	</div>
	
	<?/* start tags */?>
	<?
	$arSelect = [
		'ID', 
		'IBLOCK_ID', 
		'NAME',
		'CODE',
		'ACTIVE',
		'PROPERTY_BRAND_PRICE',
	];
	$arFilter = [
		'IBLOCK_ID' => 33,
		'!PROPERTY_BRAND_PRICE'=>false,
		'ACTIVE'=>'Y'
	];
	$arItems = [];
	$arras = CIBlockElement::GetList([], $arFilter, false, false,$arSelect);
	while ($ob = $arras->GetNextElement()) {
		$valuesFields = $ob->GetFields();
		$arItems[] = $valuesFields;
	}
	$arLetters = [];
	if($arItems)
	{
		foreach($arItems as $arItem)
		{
			$letterValue = strtoupper(mb_substr($arItem['NAME'], 0, 1));
			if(!in_array($letterValue,$arLetters))
			{
				$arLetters[] = $letterValue;
			}
		}
		usort($arLetters, function($a, $b) {
			// Проверяем тип символов
			$isAEnglish = preg_match('/[A-Za-z]/', $a);
			$isBEnglish = preg_match('/[A-Za-z]/', $b);
			$isANumber = preg_match('/d/', $a);
			$isBNumber = preg_match('/d/', $b);
			$isARussian = preg_match('/[А-Яа-яЁё]/u', $a);
			$isBRussian = preg_match('/[А-Яа-яЁё]/u', $b);

			if ($isAEnglish && !$isBEnglish) {
				return -1; // a перед b
			} elseif (!$isAEnglish && $isBEnglish) {
				return 1; // b перед a
			} elseif ($isARussian && !$isBRussian) {
				return -1; // a перед b
			} elseif (!$isARussian && $isBRussian) {
				return 1; // b перед a
			} elseif ($isANumber && !$isBNumber) {
				return 1; // b перед a (числа в конце)
			} elseif (!$isANumber && $isBNumber) {
				return -1; // a перед b (числа в конце)
			} else {
				return strcmp($a, $b); // Сравниваем по алфавиту
			}
		});

		if($arLetters)
		{
			if($arParams['USE_FILTER'] != 'N')
			{
				$bHasLetter = strval($_GET['letter']);
				$letter = $_GET['letter'];?>
				<div class="select_head_wrap">
					<div class="head-block top bordered-block rounded3 clearfix srollbar-custom">
						<div class="item-link font_upper_md  <?=($bHasLetter ? '' : 'active');?>">
							<div class="title">
								<?if($bHasLetter):?>
									<a class="btn-inline dark_link" href="<?=$arResult['FOLDER'];?>"><?=GetMessage('ALL_EN');?></a>
								<?else:?>
									<span class="btn-inline darken"><?=GetMessage('ALL_EN');?></span>
								<?endif;?>
							</div>
						</div>
						<?foreach($arLetters as $value):
							if (preg_match('/[A-Za-z]/', $value)):
								$bSelected = ($bHasLetter && $value == $letter);
								?>
								<div class="item-link font_upper_md <?=($bSelected ? 'active' : '');?>">
									<div class="title btn-inline darken">
										<?if($bSelected):?>
											<span class="btn-inline darken"><?=$value;?></span>
										<?else:?>
											<a class="btn-inline dark_link" href="/prices/?<?='letter='.$value?>"><?=$value;?></a>
										<?endif;?>
									</div>
								</div>
							<?endif;?>
						<?endforeach;?>
					</div>
				</div>

				<div class="select_head_wrap">
					<div class="head-block top bordered-block rounded3 clearfix srollbar-custom">
						<div class="item-link font_upper_md  <?=($bHasLetter ? '' : 'active');?>">
							<div class="title">
								<?if($bHasLetter):?>
									<a class="btn-inline dark_link" href="<?=$arResult['FOLDER'];?>"><?=GetMessage('ALL_RU');?></a>
								<?else:?>
									<span class="btn-inline darken"><?=GetMessage('ALL_RU');?></span>
								<?endif;?>
							</div>
						</div>
						<?foreach($arLetters as $value):
							if (preg_match('/[А-Яа-яЁё]/u', $value)):
								$bSelected = ($bHasLetter && $value == $letter);
								?>
								<div class="item-link font_upper_md <?=($bSelected ? 'active' : '');?>">
									<div class="title btn-inline darken">
										<?if($bSelected):?>
											<span class="btn-inline darken"><?=$value;?></span>
										<?else:?>
											<a class="btn-inline dark_link" href="/prices/?<?='letter='.$value?>"><?=$value;?></a>
										<?endif;?>
									</div>
								</div>
							<?endif;?>
						<?endforeach;?>
					</div>
				</div>

				<div class="select_head_wrap">
					<div class="head-block top bordered-block rounded3 clearfix srollbar-custom">
						<div class="item-link font_upper_md  <?=($bHasLetter ? '' : 'active');?>">
							<div class="title">
								<?if($bHasLetter):?>
									<a class="btn-inline dark_link" href="<?=$arResult['FOLDER'];?>"><?=GetMessage('ALL_NUMBER');?></a>
								<?else:?>
									<span class="btn-inline darken"><?=GetMessage('ALL_NUMBER');?></span>
								<?endif;?>
							</div>
						</div>
						<?foreach($arLetters as $value):
							if (is_numeric($value)):
								$bSelected = ($bHasLetter && $value == $letter);
								?>
								<div class="item-link font_upper_md <?=($bSelected ? 'active' : '');?>">
									<div class="title btn-inline darken">
										<?if($bSelected):?>
											<span class="btn-inline darken"><?=$value;?></span>
										<?else:?>
											<a class="btn-inline dark_link" href="/prices/?<?='letter='.$value?>"><?=$value;?></a>
										<?endif;?>
									</div>
								</div>
							<?endif;?>
						<?endforeach;?>
					</div>
				</div>
				
				<?
				if($bHasLetter)
				{
					$GLOBALS[$arParams["FILTER_NAME"]][] = array(
						"NAME" => $letter . '%' 
					);
				}?>
			<?}
		}
	}?>
	

	
		<div class="sub_container fixed_wrapper">
		<div class="row">
			<div class="col-md-12">
			    
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
	{
		$APPLICATION->RestartBuffer();
	}?>
	<?// section elements?>
	<?$sViewElementsTemplate = ($arParams["SECTION_ELEMENTS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["NEWS_PAGE"]["VALUE"] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"]);?>
	<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
	{
		die();
	}?>

	
			</div>
			
		</div>
		</div>
	
<?endif;?>