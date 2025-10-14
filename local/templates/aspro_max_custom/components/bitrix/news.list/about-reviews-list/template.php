<?
use \Bitrix\Main\Localization\Loc;

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Page\Asset;
Asset::getInstance()->addCss('/local/templates/aspro_max_custom/components/bitrix/news.list/about-reviews-list/swiper/swiper.css');
Asset::getInstance()->addJs('/local/templates/aspro_max_custom/components/bitrix/news.list/about-reviews-list/swiper/swiper.js');

global $arTheme;
$this->setFrameMode(true);

$arParams['ALL_BLOCK_BG'] = $arParams['ALL_BLOCK_BG'] ?? 'N';
$arParams['HALF_BLOCK'] = $arParams['HALF_BLOCK'] ?? 'N';
$arParams['USE_SECTIONS_TABS'] = $arParams['USE_SECTIONS_TABS'] ?? 'N';
$arParams['USE_DATE_MIX_TABS'] = $arParams['USE_DATE_MIX_TABS'] ?? 'N';
$arParams['USE_BG_IMAGE_ALTERNATE'] = $arParams['USE_BG_IMAGE_ALTERNATE'] ?? 'N';
$arParams['SHOW_DETAIL_LINK'] = $arParams['SHOW_DETAIL_LINK'] ?? 'Y';
?>
<?if($arResult['ITEMS']):?>
	<?
	$count = count($arResult['ITEMS']);
	$bBordered = ($arParams['BORDERED'] == 'Y');
	$bgSmallPlate = ($arParams['ALL_BLOCK_BG']=='Y' && $arParams['FON_BLOCK_2_COLS'] != 'Y' && $arParams['TITLE_SHOW_FON'] != 'Y' && $arParams['USE_BG_IMAGE_ALTERNATE']!="Y");
	
	if($arTheme['HIDE_SUBSCRIBE']['VALUE'] == 'Y'){
		$arParams['SHOW_SUBSCRIBE'] = "N";
	}
	?>
	<?$sTemplateMobile = (isset($arParams['MOBILE_TEMPLATE']) ? $arParams['MOBILE_TEMPLATE'] : '')?>
	<?$bSlider = ($sTemplateMobile === 'normal')?>
	<?$bHasBottomPager = $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" && $arResult["NAV_STRING"];?>
	<?if(!$arParams['IS_AJAX']):?>
		<div class="content_wrapper_block <?=$templateName;?> content_news2 <?=$arResult['NAV_STRING'] ? '' : 'without-border'?>">
		<div class="maxwidth-theme only-on-front">
		<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
			<?if($arParams['INCLUDE_FILE']):?>
				<div class="with-text-block-wrapper">
					<div class="row">
						<div class="col-md-3">
							<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
								<?=Aspro\Functions\CAsproMax::showTitleH($arParams['TITLE_BLOCK']);?>
								<?// intro text?>
								<?if($arParams['INCLUDE_FILE']):?>
									<div class="text_before_items font_xs">
										<?$APPLICATION->IncludeComponent(
											"bitrix:main.include",
											"",
											Array(
												"AREA_FILE_SHOW" => "file",
												"PATH" => SITE_DIR."include/mainpage/inc_files/".$arParams['INCLUDE_FILE'],
												"EDIT_TEMPLATE" => ""
											)
										);?>
									</div>
								<?endif;?>
								<div class="block-links">
									<span>
										<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="btn btn-transparent-border-color btn-sm"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
									</span>
									<?if($arParams['SHOW_SUBSCRIBE'] == 'Y' && $arParams['TITLE_SUBSCRIBE']):?>
										<span>
											<span class="btn btn-transparent-border-color btn-sm animate-load" data-event="jqm" data-param-type="subscribe" data-name="subscribe">
												<?=CMax::showIconSvg("subscribe colored", SITE_TEMPLATE_PATH."/images/svg/subscribe_thin.svg", "", "", true, false);?>
											</span>
										</span>
									<?endif;?>
								</div>
							<?endif;?>
						</div>
						<div class="col-md-9">
			<?else:?>
				<div class="top_block clearfix">
				<?=Aspro\Functions\CAsproMax::showTitleH($arParams['TITLE_BLOCK'], 'pull-left');?>
					<?if($arParams['SHOW_SUBSCRIBE'] == 'Y' && $arParams['TITLE_SUBSCRIBE']):?>
						<span class="pull-left subscribe">
							<span class="font_upper muted dark_link animate-load" data-event="jqm" data-param-type="subscribe" data-name="subscribe">
								<?=CMax::showIconSvg("subscribe", SITE_TEMPLATE_PATH."/images/svg/subscribe_small_footer.svg", "", "", true, false);?>
								<span><?=$arParams['TITLE_SUBSCRIBE'] ;?></span>
							</span>
						</span>
					<?endif;?>
					<?if($arParams['TITLE_BLOCK_ALL']):?>
						<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="pull-right font_upper muted"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
					<?endif;?>
				</div>
			<?endif;?>
		<?endif;?>
		
	<?endif;?>
			<?
			$indexItem = 0;
			$useImageAlternate = ($arParams['USE_BG_IMAGE_ALTERNATE']=="Y");			
			?>
			<div class="about-clients-top-block top_block clearfix">
				<span class="about-clients-maintext">Отзывы клиентов</span>
			</div>
			<div class="item-views news2 md with-border">
			<div class="items<?=(!$arParams['INCLUDE_FILE'] ? '' : ' list');?> s_<?=$arParams['SIZE_IN_ROW'];?>">
			<div class="swiper rev-swiper">
  				<div class="swiper-wrapper">
			<?foreach($arResult['ITEMS'] as $i => $arItem):?>
				<?
				
				// edit/add/delete buttons for edit mode
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// use detail link?
				$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
				// preview image
				$imageSrc = ($arItem['FIELDS']['PREVIEW_PICTURE'] ? $arItem['FIELDS']['PREVIEW_PICTURE']['SRC'] : '');
				$bImage = ($imageSrc ? true : false);
				$noImageSrc = SITE_TEMPLATE_PATH.'/images/svg/noimage_content.svg';
				
				$shortBigBlock = ($arParams['ALL_BLOCK_BG']!='Y' && $arParams['FON_BLOCK_2_COLS'] == 'Y' && $arParams['TITLE_SHOW_FON'] != 'Y');

				$bShowSection = ($arParams['SHOW_SECTION_NAME'] == 'Y' && ($arItem['IBLOCK_SECTION_ID'] && $arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]));

				if($useImageAlternate){					
					
					if($arParams['SIZE_IN_ROW'] == 4 || $arParams['SIZE_IN_ROW'] == 3){
						$bBgImage = ($indexItem == 0 || $indexItem%5 == 0 ? true : false);
					} else {
						$bBgImage = ($indexItem == 0 || $indexItem == (((int)$arParams['SIZE_IN_ROW'])*2 - 3) ? true : false);
					}
					
					if($bBgImage){
						$arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] = 'fon_text_fon';
					}
					
				}else{
					if(!$indexItem && $arParams['FON_BLOCK_2_COLS'] == 'Y') {
						$arItem['PROPERTIES']['TYPE_BLOCK']['VALUE'] = true;
						$arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] = 'fon_text_fon';
					}
					$bBgImage = ($arItem['PROPERTIES']['TYPE_BLOCK']['VALUE'] != '' && $arParams['HALF_BLOCK'] != 'Y');
				}
				

				$position = ($arParams['BG_POSITION'] ? ' set-position '.$arParams['BG_POSITION'] : '');
				$bBigBlock = false;

				if( $bBgImage && $arParams['FON_BLOCK_2_COLS'] == 'Y' )
				{
					$bBigBlock = true;
					$col = '3 merged';
					if($arParams['SIZE_IN_ROW'] == 5)
						$col = '6 merged col-lg-40';
					elseif($arParams['SIZE_IN_ROW'] == 4)
						$col = '8 merged col-lg-6';
					elseif($arParams['SIZE_IN_ROW'] == 3)
						$col = '8 merged col-lg-8';
					else
					{
						$col .= ' col-lg-20';
						$bBigBlock = false;
					}
				}
				else
				{
					
					$col = '3 col-lg-20';
					
				}

				$bLineImg = false;
				if($arParams['HALF_BLOCK'] == 'Y')
				{
					if($arParams['IS_AJAX'] != 'Y')
					{
						if(!$indexItem)
						{
							$arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] = 'fon_text_fon';
							$bBgImage = true;
						}
						else
						{
							$arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] = 'line_img';
							$bLineImg = true;
						}
						$col = 6;
					}
					else
					{
						$col = 12;
						$bLineImg = true;
					}
				}

				if($bLineImg)
					$bBordered = false;

				$bHalfWrapper = false;
				
				if($arParams['ALL_BLOCK_BG']=='Y'){
					$bBgImage = true;
					$arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] = 'fon_text_fon';
				}
				

				// show active date period
				$bActiveDate = strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE'] ?? '') || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));
				$img = CFile::GetByID($arItem['DETAIL_PICTURE']['ID'])->Fetch()['SRC'];
				?>
				    
				<div class="swiper-slide">
						<div class="reviews-about-container-item">
						
							<div class="reviews-about-detailes">
								<img class="reviews-about-img" src="<?=$img?>"/>
								<div class="detail-text-revabout-container">
									<div class="detail-text-rev-about"><?=$arItem['DETAIL_TEXT']?></div>
									<span><?=$arItem['PROPERTIES']['AUTHOR_WORK']['VALUE']?></span>
									<span><?=$arItem['PROPERTIES']['AUTHOR_FIO']['VALUE']?></span>
								</div>
							</div>
						</div>
						</div>
					

			<?endforeach;?>
			</div>			
				<!-- Кнопки навигации -->
				<div class="swiper-button-rev-about-prev swiper-button-prev"></div>
				<div class="swiper-button-rev-about-next swiper-button-next"></div>
				<!-- Пагинация -->
				<div class="swiper-pagination-rev-about swiper-pagination"></div>
			</div>
			</div>
			</div>
			</div>
			</div>
			


<?endif;?>