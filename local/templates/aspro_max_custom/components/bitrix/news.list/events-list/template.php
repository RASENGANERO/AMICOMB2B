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
			<div class="items <?=$bCarousel ? "swipeignore mobile-overflow mobile-margin-16 mobile-compact" : ""?>">
		<?endif?>
			<?// show section items?>
	<?endif;?>
			<?
				$count=count($arResult['ITEMS']);
				$current=0;
			?>
			<?foreach($arResult['ITEMS'] as $i => $arItem):?>
				<div class="rem-block col-md-12" data-ref="mixitup-target">
					<div class="item_wrap colored_theme_hover_bg-block">
						<div class="body-info  ">
							<div class="seminar-item-container">
								<div class="title-main-seminar title">
									<?=$arItem['~PREVIEW_TEXT']?>
								</div>
								<button class="remove-element__close close jqmClose">
									<i class="svg inline  svg-inline-" aria-hidden="true">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
											<path data-name="Rounded Rectangle 114 copy 3" class="cccls-1" d="M334.411,138l6.3,6.3a1,1,0,0,1,0,1.414,0.992,0.992,0,0,1-1.408,0l-6.3-6.306-6.3,6.306a1,1,0,0,1-1.409-1.414l6.3-6.3-6.293-6.3a1,1,0,0,1,1.409-1.414l6.3,6.3,6.3-6.3A1,1,0,0,1,340.7,131.7Z" transform="translate(-325 -130)"></path>
										</svg>
									</i>
								</button>
							</div>
						</div>							
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

		<?// bottom pagination?>
		<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
			<div class="bottom_nav_wrapper">
				<div class="bottom_nav animate-load-state<?=($arResult['NAV_STRING'] ? ' has-nav' : '');?>" <?=($isAjax ? "style='display: none; '" : "");?> data-parent=".item-views" data-append=".items.row">
					<?=$arResult['NAV_STRING']?>
				</div>
			</div>
		<?endif;?>
	<?if(!$isAjax):?>
	</div>
	<?endif;?>
	<?if($servicesMode){
		CAsproMax::showBonusComponentList($arResult);
	}?>
<?endif;?>