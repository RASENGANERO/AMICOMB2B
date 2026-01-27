<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?
// geting section items count and section [ID, NAME]
$arItemFilter = CMax::GetCurrentSectionElementFilter($arResult["VARIABLES"], $arParams);
$arSectionFilter = CMax::GetCurrentSectionFilter($arResult["VARIABLES"], $arParams);
$itemsCnt = CMaxCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, array());
$arSection = CMaxCache::CIblockSection_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "N")), $arSectionFilter, false, array('ID', 'DESCRIPTION', 'PICTURE', 'DETAIL_PICTURE'), true);
CMax::AddMeta(
	array(
		'og:description' => $arSection['DESCRIPTION'],
		'og:image' => (($arSection['PICTURE'] || $arSection['DETAIL_PICTURE']) ? CFile::GetPath(($arSection['PICTURE'] ? $arSection['PICTURE'] : $arSection['DETAIL_PICTURE'])) : false),
	)
);
?>
<?if(!$arSection && $arParams['SET_STATUS_404'] !== 'Y'):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_NOTFOUND")?></div>
<?elseif(!$arSection && $arParams['SET_STATUS_404'] === 'Y'):?>
	<?CMax::goto404Page();?>
<?else:?>
	
	<?$this->SetViewTarget('product_share');?>
		<?if($arParams['USE_RSS'] !== 'N'):?>
			<div class="colored_theme_hover_bg-block">
				<?=CMax::ShowRSSIcon(CComponentEngine::makePathFromTemplate($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss_section'], array_map('urlencode', $arResult['VARIABLES'])));?>
			</div>
		<?endif;?>
	<?$this->EndViewTarget();?>

	<?if(!$itemsCnt):?>
		<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
	<?endif;?>
	
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
	

<?/* start tags */?>
	<?
	$arSelect = [
		'ID', 
		'IBLOCK_ID', 
		'NAME',
		'CODE',
		'ACTIVE',
		'SECTION_ID'
	];
	$arFilter = [
		'IBLOCK_ID' => 33,
		'ACTIVE'=>'Y',
		'!PREVIEW_PICTURE' => false,
		'ID'=> $GLOBALS['filterBrandsB2B']['ID'],
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
		
		$arLettersCount = [0,0,0];//Индексируем 3 счётчика в массиве
		foreach($arLetters as $value) {
			if (preg_match('/[A-Za-z]/', $value)) {
				$arLettersCount[0] = $arLettersCount[0]+1; 
			}
			if (preg_match('/[А-Яа-яЁё]/u', $value)) {
				$arLettersCount[1] = $arLettersCount[1]+1;
			}
			if(is_numeric($value)) {
				$arLettersCount[2] = $arLettersCount[2]+1;
			}
		}
		if($arLetters)
		{
			if($arParams['USE_FILTER'] != 'N')
			{
				$bHasLetter = strval($_GET['letter']);
				$letter = $_GET['letter'];
			?>
				<?if ($arLettersCount[0] !==0 ):?>
					<div class="select_head_wrap">
						<div class="head-block top bordered-block rounded3 clearfix srollbar-custom">
							<div class="item-link font_upper_md  <?=($bHasLetter ? '' : 'active');?>">
								<div class="title">
									<?if($bHasLetter):?>
										<a class="btn-inline dark_link" href="/brands/<?=$arResult["VARIABLES"]["SECTION_CODE"]?>/"><?=GetMessage('ALL_EN');?></a>
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
												<a class="btn-inline dark_link" href="/brands/<?=$arResult["VARIABLES"]["SECTION_CODE"].'/?letter='.$value?>"><?=$value;?></a>
											<?endif;?>
										</div>
									</div>
								<?endif;?>
							<?endforeach;?>
						</div>
					</div>
				<?endif;?>
				<?if ($arLettersCount[1] !==0 ):?>
					<div class="select_head_wrap">
						<div class="head-block top bordered-block rounded3 clearfix srollbar-custom">
							<div class="item-link font_upper_md  <?=($bHasLetter ? '' : 'active');?>">
								<div class="title">
									<?if($bHasLetter):?>
										<a class="btn-inline dark_link" href="/brands/<?=$arResult["VARIABLES"]["SECTION_CODE"]?>/"><?=GetMessage('ALL_RU');?></a>
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
												<a class="btn-inline dark_link" href="/brands/<?=$arResult["VARIABLES"]["SECTION_CODE"].'/?letter='.$value?>"><?=$value;?></a>
											<?endif;?>
										</div>
									</div>
								<?endif;?>
							<?endforeach;?>
						</div>
					</div>
				<?endif;?>
				<?if ($arLettersCount[2] !==0 ):?>
					<div class="select_head_wrap">
						<div class="head-block top bordered-block rounded3 clearfix srollbar-custom">
							<div class="item-link font_upper_md  <?=($bHasLetter ? '' : 'active');?>">
								<div class="title">
									<?if($bHasLetter):?>
										<a class="btn-inline dark_link" href="/brands/<?=$arResult["VARIABLES"]["SECTION_CODE"]?>/"><?=GetMessage('ALL_NUMBER');?></a>
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
												<a class="btn-inline dark_link" href="/brands/<?=$arResult["VARIABLES"]["SECTION_CODE"].'/?letter='.$value?>"><?=$value;?></a>
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
					$GLOBALS[$arParams["FILTER_NAME"]][] = array(
						"NAME" => $letter . '%' 
					);
				}?>
			<?}
		}
	}?>
	<?


	// edit/add/delete buttons for edit mode
	$arSectionButtons = CIBlock::GetPanelButtons($arSection['IBLOCK_ID'], 0, $arSection['ID'], array('SESSID' => false, 'CATALOG' => true));
	$this->AddEditAction($arSection['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_EDIT'));
	$this->AddDeleteAction($arSection['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="main-section-wrapper" id="<?=$this->GetEditAreaId($arSection['ID'])?>">
		<?global $arTheme;?>
		<?// section elements?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"brands-list-b2b",
			Array(
				"COUNT_IN_LINE" => $arParams["COUNT_IN_LINE"],
				"SHOW_SECTION_PREVIEW_DESCRIPTION" => $arParams["SHOW_SECTION_PREVIEW_DESCRIPTION"],
				"SHOW_SECTION_NAME" => "N",
				"IMG_PADDING" => "Y",
				"VIEW_TYPE" => $arParams["VIEW_TYPE"],
				"SHOW_TABS" => $arParams["SHOW_TABS"],
				"IMAGE_POSITION" => $arParams["IMAGE_POSITION"],
				"IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
				"IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
				"NEWS_COUNT"	=>	$arParams['NEWS_COUNT'],
				"SORT_BY1"	=>	$arParams["SORT_BY1"],
				"SORT_ORDER1"	=>	$arParams["SORT_ORDER1"],
				"SORT_BY2"	=>	$arParams["SORT_BY2"],
				"SORT_ORDER2"	=>	$arParams["SORT_ORDER2"],
				"FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
				"PROPERTY_CODE"	=>	$arParams["LIST_PROPERTY_CODE"],
				"DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
				"SET_TITLE"	=>	$arParams["SET_TITLE"],
				"SET_STATUS_404" => $arParams["SET_STATUS_404"],
				"INCLUDE_IBLOCK_INTO_CHAIN"	=>	$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
				"ADD_SECTIONS_CHAIN"	=>	$arParams["ADD_SECTIONS_CHAIN"],
				"CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
				"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
				"CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"DISPLAY_TOP_PAGER"	=>	$arParams["DISPLAY_TOP_PAGER"],
				"DISPLAY_BOTTOM_PAGER"	=>	$arParams["DISPLAY_BOTTOM_PAGER"],
				"PAGER_TITLE"	=>	$arParams["PAGER_TITLE"],
				"PAGER_TEMPLATE"	=>	$arParams["PAGER_TEMPLATE"],
				"PAGER_SHOW_ALWAYS"	=>	$arParams["PAGER_SHOW_ALWAYS"],
				"PAGER_DESC_NUMBERING"	=>	$arParams["PAGER_DESC_NUMBERING"],
				"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
				"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
				"DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
				"DISPLAY_NAME"	=>	$arParams["DISPLAY_NAME"],
				"DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
				"DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
				"PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
				"ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
				"USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
				"GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
				"FILTER_NAME"	=>	$arParams["FILTER_NAME"],
				"HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
				"CHECK_DATES"	=>	$arParams["CHECK_DATES"],
				"PARENT_SECTION"	=>	$arResult["VARIABLES"]["SECTION_ID"],
				"PARENT_SECTION_CODE"	=>	$arResult["VARIABLES"]["SECTION_CODE"],
				"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
				"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"IBLOCK_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
				"INCLUDE_SUBSECTIONS" => "Y",
				"SHOW_DETAIL_LINK" => $arParams["SHOW_DETAIL_LINK"],
				'DISCOUNT_VALUES' => $arParams['DISCOUNT_VALUES'],
				'COUNT_ALL_DISCOUNT' => $arParams['COUNT_ALL_DISCOUNT'],
			),
			$component
		);?>
	</div>
<?endif;?>