<?
use \Bitrix\Main\Localization\Loc;

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $arTheme;
$this->setFrameMode(true);

$arParams['ALL_BLOCK_BG'] ??= 'N';
$arParams['HALF_BLOCK'] ??= 'N';
$arParams['USE_SECTIONS_TABS'] ??= 'N';
$arParams['USE_DATE_MIX_TABS'] ??= 'N';
$arParams['USE_BG_IMAGE_ALTERNATE'] ??= 'N';
$arParams['SHOW_DETAIL_LINK'] ??= 'Y';
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
	<?$sTemplateMobile = ($arParams['MOBILE_TEMPLATE'] ?? '')?>
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
											[
												"AREA_FILE_SHOW" => "file",
												"PATH" => SITE_DIR."include/mainpage/inc_files/".$arParams['INCLUDE_FILE'],
												"EDIT_TEMPLATE" => ""
											]
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
		<div class="item-views news2 <?=$arParams['TYPE_IMG'];?><?=(!$bBordered ? '' : ' with-border');?><?=($arParams['HALF_BLOCK'] == 'Y' ? ' half-block' : '');?> <?=($bgSmallPlate ? 'small-bg-plate' : '');?> <?=$sTemplateMobile;?>">
			<div class="items<?=(!$arParams['INCLUDE_FILE'] ? '' : ' list');?> s_<?=$arParams['SIZE_IN_ROW'];?>">
				<div class="price-row-flexbox row flexbox <?=$sTemplateMobile;?><?=($bSlider ? ' swipeignore mobile-overflow mobile-margin-16 mobile-compact' : '');?><?=$bHasBottomPager ? ' has-bottom-nav' : ''?>">
	<?endif;?>
			<?
			$indexItem = 0;
			$useImageAlternate = ($arParams['USE_BG_IMAGE_ALTERNATE']=="Y");			
			?>
			<?foreach($arResult['ITEMS'] as $i => $arItem):?>
				<?
				$key = $i;
				// edit/add/delete buttons for edit mode
				$this->AddEditAction($arItem[$i]['ID'], $arItem[$i]['EDIT_LINK'], CIBlock::GetArrayByID($arItem[$i]['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$this->AddDeleteAction($arItem[$i]['ID'], $arItem[$i]['DELETE_LINK'], CIBlock::GetArrayByID($arItem[$i]['IBLOCK_ID'], 'ELEMENT_DELETE'), ['CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
				// use detail link?
				$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen((string) $arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
				// preview image
				$imageSrc = ($arItem['FIELDS']['PREVIEW_PICTURE'] ? $arItem['FIELDS']['PREVIEW_PICTURE']['SRC'] : '');
				$bImage = ($imageSrc ? true : false);
				$noImageSrc = SITE_TEMPLATE_PATH.'/images/svg/noimage_content.svg';
				
				$shortBigBlock = ($arParams['ALL_BLOCK_BG']!='Y' && $arParams['FON_BLOCK_2_COLS'] == 'Y' && $arParams['TITLE_SHOW_FON'] != 'Y');

				$bShowSection = ($arParams['SHOW_SECTION_NAME'] == 'Y' && ($arItem['IBLOCK_SECTION_ID'] && $arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]));

				
				

				
				
				

				$bActiveDate = strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE'] ?? '') || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));
				
				
				
				?>
				    
				<div class="key-price-elem">
					<div class="key-price-elem-val">
						<span class="key-price-elem-text"><?=$key?></span>
					</div>
					<div class="key-price-list">
					<?
					$arData = $arItem;
					foreach($arData as $arDataItem):
					?>

				
					<div class="price-list-item item-wrapper col-md-<?=$col;?> col-sm-6 col-xs-6 col-xxs-12 clearfix <?=$arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'];?> <?=$dop_class;?><?=($bSlider ? ' item-width-261' : '');?>" data-ref="mixitup-target">
				
						<div class="half-wrapper scrollblock">
						<?$bHalfWrapper = true;?>
				
					
					<div class="price-container-item item <?=$bHalfWrapper ? '' : 'bg-white'?> <?=($bBordered ? ' bordered box-shadow rounded3' : '');?><?=(!$bImage ? ' no-img' : '');?><?=(($arResult['HAS_TITLE_FON'] == 'Y' || $useImageAlternate) ? ' long' : '');?> clearfix" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<div class="image shine<?=($bLineImg ? ' pull-left' : '');?>">
							<a class="price-elem" data-element="<?=$arDataItem['CODE']?>">
								<div class="<?=(!$bBordered ? 'rounded3' : '');?> bg-fon-img item-brand">
									<?if (!empty($arDataItem['DETAIL_PICTURE']['ID'])) {?>
										<img class="price-prev-img" src="<?=CFile::GetByID($arDataItem['DETAIL_PICTURE']['ID'])->Fetch()['SRC'];?>"/>
									<?
									}
									else{
									?>
										<img class="price-prev-img" src="/local/templates/aspro_max_custom/images/svg/noimage_brand.svg"/>
									<?}?>
									
								</div>
							</a>
						</div>
						<div class="inner-text<?=($bShowSection ? ' with-section' : '');?><?=($bActiveDate ? ' with-date' : '');?><?=($arParams['TITLE_SHOW_FON'] == 'Y' ? ' with-fon' : '');?>">
							<div class="inner-block-text">
								<div class="title<?=(($bBigBlock || $arParams['ALL_BLOCK_BG']=='Y') && $arParams['TITLE_SHOW_FON'] != 'Y' ? ' font_mlg' : '' /*(!$bBordered || $arParams['SIZE_IN_ROW'] == 5 ? '' : ' font_md')*/);?>">
									<a class="price-elem" data-element="<?=$arDataItem['CODE']?>"><?="Прайс ".$arDataItem['NAME'];?></a>
								</div>
							</div>
							
						</div>
					</div>
					
					</div>
					</div>
					<?endforeach;?>
					</div>
				<?if($arParams['HALF_BLOCK'] != 'Y' || ($arParams['HALF_BLOCK'] == 'Y' && ($arParams['IS_AJAX'] == 'Y' || ($arParams['IS_AJAX'] != 'Y' && !$indexItem)))):?>
				</div>
				<?endif;?>
				<?
				//$indexItem = ($indexItem == $alternateNumber ? 1 : ++$indexItem);
				$indexItem++; 
				?>
			<?endforeach;?>

			<?if ($bSlider && $bHasBottomPager):?>
				<?if($arParams['IS_AJAX']):?>
					<div class="wrap_nav bottom_nav_wrapper">
				<?endif;?>
					<?$bHasNav = (str_contains((string) $arResult["NAV_STRING"], 'more_text_ajax'));?>
						<div class="bottom_nav mobile_slider animate-load-state block-type<?=($bHasNav ? '' : ' hidden-nav');?>" data-parent=".item-views" data-scroll-class=".swipeignore.mobile-overflow" data-append="<?=($arParams['HALF_BLOCK'] != 'Y' ? '.items > .row' : '.items > .row > .item-wrapper.line_img .half-wrapper');?>" <?=($arParams["IS_AJAX"] ? "style='display: none; '" : "");?>>
						<?if ($bHasNav):?>
							<?=CMax::showIconSvg('bottom_nav-icon colored_theme_svg', SITE_TEMPLATE_PATH.'/images/svg/mobileBottomNavLoader.svg');?>
							<?=$arResult["NAV_STRING"]?>
						<?endif;?>
						</div>

				<?if($arParams['IS_AJAX']):?>
					</div>
				<?endif;?>
			<?endif;?>
				<?// close the div if there is only one element?>
			<?if($arParams['HALF_BLOCK'] == 'Y' && $arParams['IS_AJAX'] != 'Y' && count($arResult["ITEMS"])>1):?>
					</div>
				</div>
			<?endif;?>

	<?if(!$arParams['IS_AJAX']):?>
			</div>
		</div>
	<?endif;?>
		
		<?// bottom pagination?>
		<div class="bottom_nav_wrapper<?=($bSlider ? ' hidden-slider-nav' : '');?>">
			<div class="bottom_nav animate-load-state<?=($arResult['NAV_STRING'] ? ' has-nav' : '');?>" <?=($arParams['IS_AJAX'] ? "style='display: none; '" : "");?> data-parent=".item-views" data-scroll-class=".swipeignore.mobile-overflow" data-append="<?=($arParams['HALF_BLOCK'] != 'Y' ? '.items > .row' : '.items > .row > .item-wrapper.line_img .half-wrapper .mCSB_container');?>">
				<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
					<?=$arResult['NAV_STRING']?>
				<?endif;?>
			</div>
		</div>

	<?if(!$arParams['IS_AJAX']):?>
		</div>
		<?if($arParams['INCLUDE_FILE']):?>
			</div></div></div>
		<?endif;?>
	</div></div>
	<?endif;?>
<?endif;?>