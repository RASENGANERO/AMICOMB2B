<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
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
<?
$arItemFilter = CMax::GetCurrentSectionElementFilter($arResult["VARIABLES"], $arParams);//var_dump($arResult["VARIABLES"]);
$arSectionFilter = CMax::GetCurrentSectionFilter($arResult["VARIABLES"], $arParams);
//var_dump($arSectionFilter);die();
if($arParams['CACHE_GROUPS'] == 'Y')
{
	$arSectionFilter['CHECK_PERMISSIONS'] = 'Y';
	$arSectionFilter['GROUPS'] = $GLOBALS["USER"]->GetGroups();
}

//$title_news = GetMessage('CURRENT_NEWS', array('#YEAR#' => $arResult['VARIABLES']['YEAR']));
?>

<?
$arSection = CMaxCache::CIblockSection_GetList(["CACHE" => ["TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "N"]], $arSectionFilter, false, ['ID', 'DESCRIPTION', 'PICTURE', 'DETAIL_PICTURE'], true);
CMax::AddMeta(
	[
		'og:description' => $arSection['DESCRIPTION'],
		'og:image' => (($arSection['PICTURE'] || $arSection['DETAIL_PICTURE']) ? CFile::GetPath(($arSection['PICTURE'] ?: $arSection['DETAIL_PICTURE'])) : false),
	]
);

$bFoundSection = false;
//$arYears = array();

if($arSection)
{
	$bFoundSection = true;
	$itemsCnt = CMaxCache::CIblockElement_GetList(["CACHE" => ["TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"])]], $arItemFilter, []);
}
?>

<?$arItems = CMaxCache::CIBLockElement_GetList(['SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => ['TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID'])]], $arItemFilter, false, false, ['ID', 'NAME', 'ACTIVE_FROM']);
	$arYears = [];
	if($arItems)
	{
		foreach($arItems as $arItem)
		{
			if($arItem['ACTIVE_FROM'])
			{
				if($arDateTime = ParseDateTime($arItem['ACTIVE_FROM'], FORMAT_DATETIME))
					$arYears[$arDateTime['YYYY']] = $arDateTime['YYYY'];
			}
		}
		if($arYears)
		{
			if($arParams['USE_FILTER'] != 'N')
			{
				rsort($arYears);
				$bHasYear = (isset($_GET['year']) && (int)$_GET['year']);
				$year = ($bHasYear ? (int)$_GET['year'] : 0);?>
				<div class="select_head_wrap">
					<div class="menu_item_selected font_upper_md rounded3 bordered visible-xs font_xs darken"><span></span>
						<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '', true, false);?>
					</div>
					<div class="head-block top bordered-block rounded3 clearfix srollbar-custom">
						<div class="item-link font_upper_md  <?=($bHasYear ? '' : 'active');?>">
							<div class="title">
								<?if($bHasYear):?>
									<a class="btn-inline dark_link" href="<?=$arResult['FOLDER'];?>"><?=GetMessage('ALL_TIME');?></a>
								<?else:?>
									<span class="btn-inline darken"><?=GetMessage('ALL_TIME');?></span>
								<?endif;?>
							</div>
						</div>
						<?foreach($arYears as $value):
							$bSelected = ($bHasYear && $value == $year);?>
							<div class="item-link font_upper_md <?=($bSelected ? 'active' : '');?>">
								<div class="title btn-inline darken">
									<?if($bSelected):?>
										<span class="btn-inline darken"><?=$value;?></span>
									<?else:?>
										<a class="btn-inline dark_link" href="<?=$APPLICATION->GetCurPageParam('year='.$value, ['year']);?>"><?=$value;?></a>
									<?endif;?>
								</div>
							</div>
						<?endforeach;?>
					</div>
				</div>
				<?
				if($bHasYear)
				{
					$GLOBALS[$arParams["FILTER_NAME"]][] = [
						">=DATE_ACTIVE_FROM" => ConvertDateTime("01.01.".$year, "DD.MM.YYYY"),
						"<DATE_ACTIVE_FROM" => ConvertDateTime("01.01.".($year+1), "DD.MM.YYYY"),
					];
				}?>
			<?}
		}
	}?>

<?if(!$bFoundSection && $arParams['SET_STATUS_404'] !== 'Y'):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_NOTFOUND")?></div>
<?elseif(!$bFoundSection && $arParams['SET_STATUS_404'] === 'Y'):?>
	<?CMax::goto404Page();?>
<?else:?>

	<?$this->SetViewTarget('product_share');?>
		<?if($arParams['USE_RSS'] !== 'N'):?>
			<div class="colored_theme_hover_bg-block">
				<?=CMax::ShowRSSIcon(CComponentEngine::makePathFromTemplate($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss_section'], array_map(urlencode(...), $arResult['VARIABLES'])));?>
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

	<?// group elements by sections?>
	<div class="prices-section-elements">
	<?foreach($sectionsPrices['SECTIONS'] as $SID => $arSection):?>
		<?if (!$bIsAjax):?>
		<div id="<?=$this->GetEditAreaId($arSection['ID'])?>" class="tab-pane <?=(!$si++ || !$arSection['ID'] ? 'active' : '')?>">
	
			<a class="<?=$arSection['ACTIVE_PAGE_CLASS']?> title prices-font pull-left section-btn btn-lg has-ripple" href="<?=$arSection['SECTION_PAGE_URL']?>"><?=$arSection['NAME']?></a>
			<?// section description text/html?>
			<?if(strlen((string) $arSection['DESCRIPTION']) && !str_contains((string) $_SERVER['REQUEST_URI'], 'PAGEN')):?>
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
		'ACTIVE'=>'Y',
		'SECTION_ID'=>$arResult['VARIABLES']['SECTION_ID']
		
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
			$letterValue = strtoupper(mb_substr((string) $arItem['NAME'], 0, 1));
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
			$pricesFilter = \Amikomnew\PricesSection::checkFilter($arLetters);//Проверка, чтобы не выводить значния по фильтру, если элементов в них нет
			$sectionUrlFilter = $arResult['FOLDER'].$arResult['VARIABLES']['SECTION_CODE'].'/';
			if($arParams['USE_FILTER'] != 'N')
			{
				$bHasLetter = strval($_GET['letter']);
				$letter = $_GET['letter'];?>
				<?if ($pricesFilter[0] === 1):?>
				<div class="select_head_wrap">
					<div class="head-block top bordered-block rounded3 clearfix srollbar-custom">
						<div class="item-link font_upper_md  <?=($bHasLetter ? '' : 'active');?>">
							<div class="title">
								<?if($bHasLetter):?>
									<a class="btn-inline dark_link" href="<?=$sectionUrlFilter;?>"><?=GetMessage('ALL_EN');?></a>
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
											<a class="btn-inline dark_link" href="<?=$sectionUrlFilter.'?'.'letter='.$value?>"><?=$value;?></a>
										<?endif;?>
									</div>
								</div>
							<?endif;?>
						<?endforeach;?>
					</div>
				</div>
				<?endif;?>
				<?if ($pricesFilter[1] === 1):?>

				<div class="select_head_wrap">
					<div class="head-block top bordered-block rounded3 clearfix srollbar-custom">
						<div class="item-link font_upper_md  <?=($bHasLetter ? '' : 'active');?>">
							<div class="title">
								<?if($bHasLetter):?>
									<a class="btn-inline dark_link" href="<?=$sectionUrlFilter;?>"><?=GetMessage('ALL_RU');?></a>
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
											<a class="btn-inline dark_link" href="<?=$sectionUrlFilter.'?'.'letter='.$value?>"><?=$value;?></a>
										<?endif;?>
									</div>
								</div>
							<?endif;?>
						<?endforeach;?>
					</div>
				</div>

				<?endif;?>
				<?if ($pricesFilter[2] === 1):?>

				<div class="select_head_wrap">
					<div class="head-block top bordered-block rounded3 clearfix srollbar-custom">
						<div class="item-link font_upper_md  <?=($bHasLetter ? '' : 'active');?>">
							<div class="title">
								<?if($bHasLetter):?>
									<a class="btn-inline dark_link" href="<?=$sectionUrlFilter;?>"><?=GetMessage('ALL_NUMBER');?></a>
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
											<a class="btn-inline dark_link" href="<?=$sectionUrlFilter.'?'.'letter='.$value?>"><?=$value;?></a>
										<?endif;?>
									</div>
								</div>
							<?endif;?>
						<?endforeach;?>
					</div>
				</div>
				<?endif;?>
				<?
				if($bHasLetter)
				{
					$GLOBALS[$arParams["FILTER_NAME"]][] = [
						"NAME" => $letter . '%' 
					];
				}?>
			<?}
		}
	}?>
		<div class="sub_container fixed_wrapper">
		<div class="row">
			<div class="col-md-12">
	

	<?// section elements?>
	
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower((string) $_REQUEST['ajax']) == 'y'))
	{
		$APPLICATION->RestartBuffer();
	}?>
	<?$sViewElementsTemplate = ($arParams["SECTION_ELEMENTS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["NEWS_PAGE"]["VALUE"] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"]);?>
	<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower((string) $_REQUEST['ajax']) == 'y'))
	{
		die();
	}?>
	

	
			</div>
			
		</div>
		</div>
	

	<?//$APPLICATION->SetTitle($title_news);?>
	<?//$APPLICATION->AddChainItem($title_news);?>
<?endif;?>