<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?
use Amikomnew;
$sectionsBrands = Amikomnew\GetSections::getAllSectionsIblock(33,'brands');
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

<?if(!$isHideLeftBlock && $APPLICATION->GetProperty("HIDE_LEFT_BLOCK_LIST") == "Y"){
	$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y");
}?>

<?$sViewElementsTemplate = ($arParams["SECTION_ELEMENTS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["PARTNERS_PAGE"]["VALUE"] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"]);?>

<?$bViewWithGroups = strpos($sViewElementsTemplate, 'with_group') !== false;?>

<?// intro text?>
<div class="brands-text-before-items">
	<?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		Array(
			"AREA_FILE_SHOW" => "page",
			"AREA_FILE_SUFFIX" => "inc",
			"EDIT_TEMPLATE" => ""
		)
	);?>
</div>

<?$this->SetViewTarget('product_share');?>
	<?if($arParams['USE_RSS'] !== 'N'):?>
		<div class="colored_theme_hover_bg-block">
			<?=CMax::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);?>
		</div>
	<?endif;?>
<?$this->EndViewTarget();?>
<?


?>
	<?// group elements by sections?>
	<div class="brands-section-elements">
	<?foreach($sectionsBrands['SECTIONS'] as $SID => $arSection):?>
		<?if (!$bIsAjax):?>
		<div id="<?=$this->GetEditAreaId($arSection['ID'])?>" class="tab-pane <?=(!$si++ || !$arSection['ID'] ? 'active' : '')?>">

			<?if($arParams['SHOW_SECTION_PREVIEW_DESCRIPTION'] == 'Y'):?>

				<?if($arParams['SHOW_SECTION_NAME'] != 'N'):?>
					<?// section name?>
					<?if(strlen($arSection['NAME'])):?>
						<a class="<?=$arSection['ACTIVE_PAGE_CLASS']?> title brands-font pull-left section-btn btn-lg has-ripple" href="<?=$arSection['SECTION_PAGE_URL']?>"><?=$arSection['NAME']?></a>
					<?endif;?>
				<?endif;?>

				<?// section description text/html?>
				<?if(strlen($arSection['DESCRIPTION']) && strpos($_SERVER['REQUEST_URI'], 'PAGEN') === false):?>
					<div class="text_before_items">
						<?=$arSection['DESCRIPTION']?>
					</div>
					<?if($arParams['SHOW_SECTION_DESC_DIVIDER'] == 'Y'):?>
						<hr class="sect-divider" />
					<?endif;?>
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
	];
	$arFilter = [
		'IBLOCK_ID' => 33,
		'ACTIVE'=>'Y',
		'!PREVIEW_PICTURE' => false,
	//	'SECTION_ID'=> $GLOBALS[$arParams["FILTER_NAME"]]['SECTION_ID']
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
				return strcmp($a, $b);
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
											<a class="btn-inline dark_link" href="/brands/?<?='letter='.$value?>"><?=$value;?></a>
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
											<a class="btn-inline dark_link" href="/brands/?<?='letter='.$value?>"><?=$value;?></a>
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
											<a class="btn-inline dark_link" href="/brands/?<?='letter='.$value?>"><?=$value;?></a>
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




<?if (!$bViewWithGroups):?>
	<?$arItemFilter = CMax::GetIBlockAllElementsFilter($arParams);
	$itemsCnt = CMaxCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, array());?>

	<?if(!$itemsCnt):?>
		<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
	<?else:?>
		<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
		{
			$APPLICATION->RestartBuffer();
		}?>

		<?// section elements?>
		<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>

		<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
		{
			die();
		}?>
	<?endif;?>
<?else:?>
	<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>
<?endif;?>