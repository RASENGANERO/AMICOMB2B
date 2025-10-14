<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc,
		\Aspro\Functions\CAsproMax;?>
<?if($arResult['ITEMS']):?>
	<?$isAjax = $arParams['IS_AJAX'];?>
	<?$bSlider = ($arParams['SLIDER'] && $arParams['SLIDER'] == 'Y');
	$bSliderWait = $bSlider && isset($arParams['SLIDER_WAIT']) && $arParams['SLIDER_WAIT'] == 'Y';
	$bCarousel = ($arParams['MOBILE_CAROUSEL'] && $arParams['MOBILE_CAROUSEL'] == 'Y');
	?>
	<?$basketUrl = CMax::GetFrontParametrValue('BASKET_PAGE_URL');
	$servicesMode = isset($arParams["SERVICES_MODE"]) && $arParams["SERVICES_MODE"] === 'Y';
	?>
	<?if(!$isAjax):?>
	<div class="item-views list list-type-block wide_img list-news1 <?=($servicesMode ? 'services-mode' : '')?> <?=($arParams['IMAGE_POSITION'] ? 'image_'.$arParams['IMAGE_POSITION'] : '')?><?=($arParams['LINKED_MODE'] == 'Y' ? ' compact-view' : '')?> <?=($arParams['LINKED_MODE'] != 'Y' &&  $arParams['SMALL_IMAGE_MODE'] ? ' small-image-view' : '')?> <?=$templateName;?>-template">

		<?
		$bHasSection = false;
		if($arParams['PARENT_SECTION'] && (array_key_exists('SECTIONS', $arResult) && $arResult['SECTIONS']))
		{
			if(isset($arResult['SECTIONS'][$arParams['PARENT_SECTION']]) && $arResult['SECTIONS'][$arParams['PARENT_SECTION']])
				$bHasSection = true;
		}
		if($bHasSection)
		{
			// edit/add/delete buttons for edit mode
			$arSectionButtons = CIBlock::GetPanelButtons($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['IBLOCK_ID'], 0, $arResult['SECTIONS'][$arParams['PARENT_SECTION']]['ID'], array('SESSID' => false, 'CATALOG' => true));
			$this->AddEditAction($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['IBLOCK_ID'], 'SECTION_EDIT'));
			$this->AddDeleteAction($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<div class="section" id="<?=$this->GetEditAreaId($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['ID'])?>">
			<?
		}?>
		<?if($bSlider):?>
			<div class="items row margin0 <?=($bSliderWait ? ' owl-carousel-wait loader_circle ' : ' owl-carousel ')?> owl-theme owl-sm-nav owl-bg-nav swipeignore" data-plugin-options='{"nav": true, "autoplay" : false, "rewind": true, "smartSpeed":1000, "responsiveClass": true, "responsive":{"0":{"items": 1},"600":{"items": 2}}}'>
		<?else:?>
			<?if($arParams['SHOW_TITLE'] == 'Y' && $arParams['TITLE']):?>
				<div class="ordered-block goods cur with-title1">
					<div class="ordered-block__title option-font-bold font_lg"><?=$arParams['TITLE']?></div>
			<?endif;?>
			<div class="gloss-items-row items row <?=$bCarousel ? "swipeignore mobile-overflow mobile-margin-16 mobile-compact" : ""?>">
		<?endif?>
			<?// show section items?>
	<?endif;?>
			
			<?foreach($arResult['ITEMS'] as $i => $arItem):?>		    
				<?
				$key = $i;
				
				// edit/add/delete buttons for edit mode
				$this->AddEditAction($arItem[$i]['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem[$i]['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$this->AddDeleteAction($arItem[$i]['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem[$i]['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// use detail link?
				$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
				$bImage = isset($arItem['FIELDS']['PREVIEW_PICTURE']) && strlen($arItem['PREVIEW_PICTURE']['SRC']);
				$bImageDetail = isset($arItem['FIELDS']['DETAIL_PICTURE']) && strlen($arItem['DETAIL_PICTURE']['SRC']);
				$imageSrc = ($bImage ? $arItem['PREVIEW_PICTURE']['SRC'] : false);
				$imageDetailSrc = ($bImageDetail ? $arItem['DETAIL_PICTURE']['SRC'] : false);
				// show active date period
				$bActiveDate = ( isset($arItem['DISPLAY_PROPERTIES']['PERIOD']) && strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) ) || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));
				$bDiscountCounter = ($arItem['ACTIVE_TO'] && in_array('ACTIVE_TO', $arParams['FIELD_CODE']));
				$bShowDopBlock = (isset($arItem['DISPLAY_PROPERTIES']['SALE_NUMBER']) && $arItem['DISPLAY_PROPERTIES']['SALE_NUMBER']['VALUE'] || $bDiscountCounter);
				$bHideSectionName = isset($arParams['HIDE_SECTION_NAME']) && ($arParams['HIDE_SECTION_NAME'] == "Y");
				$bShowSectionName = isset($arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]) && strlen($arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]['NAME']) && !$bHideSectionName;
				$noProps = true;
				?>
				
				<div class="key-gloss-elem">
					<div class="key-gloss-elem-val">
						<span class="key-gloss-elem-text"><?=$key?></span>
					</div>
					<div class="key-gloss-list">
					<?
					$arData = $arItem;
					foreach($arData as $elem => $arDataItem):
					?>

						<div class="gloss-item-hov col-md-12 <?=$dop_class;?> <?=($bSlider ? '' : '');?> <?=$bCarousel ? "item-width-261" : ""?>" data-ref="mixitup-target">
							<div class="gloss-item-wrap item_wrap colored_theme_hover_bg-block box-shadow rounded3 <?=($arParams['BORDERED']=='Y' ? 'bordered-block ' : '')?> <?=$bCarousel ? "item" : ""?>" >
								<div class="gloss-item item <?=$servicesMode ? 'js-notice-block' : ''?> noborder<?=($bImage ? '' : ' wti')?><?=($bActiveDate ? ' wdate' : '')?> clearfix" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
									<?if($bImage):?>
										<div class="image shine nopadding <?=$servicesMode ? 'js-notice-block__image' : ''?>">
											<?if($bDetailLink):?>
												<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
											<?endif;?>
												<img src="<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" class="img-responsive lazy" data-src="<?=$imageSrc?>" />
											<?if($bDetailLink):?>
												</a>
											<?endif;?>
										</div>
									<?endif;?>
									<div class="gloss-body-info body-info <?=$bShowSectionName? 'with-section': '';?> <?=($bDetailLink) ? 'has-link':'';?>">
										<?if(strlen($arDataItem['FIELDS']['NAME'])):?>
											<div class="gloss-item-title title <?=$servicesMode ? 'js-notice-block__title' : ''?> <?=($bSlider ? 'font_sm' : 'font_mlg');?>">
												<a class="btn-glossary" href="<?=$arDataItem['DETAIL_PAGE_URL']?>" class="dark-color"><?=$arDataItem['NAME']?></a>
											</div>
										<?endif;?>
									</div>										
								</div>
							</div>
						</div>
					<?endforeach;?>
					</div>
				</div>
			<?endforeach;?>
		<?if(!$isAjax):?>
			<?if($arParams['SHOW_TITLE'] == 'Y' && $arParams['TITLE'] && !$bSlider):?>
				</div>
			<?endif;?>
		</div>
		<?if($bHasSection):?>
			</div>
		<?endif;?>
		<?endif;?>

		
	<?if(!$isAjax):?>
	</div>
	<?endif;?>
	<?if($servicesMode){
		CAsproMax::showBonusComponentList($arResult);
	}?>
<?endif;?>