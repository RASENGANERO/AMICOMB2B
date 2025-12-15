<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Page\Asset;
Asset::getInstance()->addCss('/local/templates/aspro_max_custom/components/bitrix/news.list/about-brands-list/swiper/swiper.css');
Asset::getInstance()->addJs('/local/templates/aspro_max_custom/components/bitrix/news.list/about-brands-list/swiper/swiper.js');
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
								
							<?endif;?>
						</div>
						<div class="col-md-9">
			<?else:?>
				<div class="about-brands-top-block top_block clearfix">
					<a href="/brands/" class="about-brands-maintext">Бренды</a>
				</div>
			<?endif;?>
		<?endif;?>
		
		
	<?endif;?>
			
			
			<div class="item-views news2 md with-border">
			<div class="items<?=(!$arParams['INCLUDE_FILE'] ? '' : ' list');?> s_<?=$arParams['SIZE_IN_ROW'];?>">
			<div class="swiper brands-swiper">
			<div class="swiper-wrapper">	
			
			<?foreach($arResult['ITEMS'] as $arItem):?>
				

					<div class="swiper-slide">
						<div class="brand-container-item">
							<div class="image shine<?=($bLineImg ? ' pull-left' : '');?>">
								<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
									<div class="<?=(!$bBordered ? 'rounded3' : '');?> bg-fon-img item-brand">
										<?if (!empty($arItem['DETAIL_PICTURE']['ID'])) {?>
											<img class="brand-prev-img" src="<?=CFile::GetByID($arItem['DETAIL_PICTURE']['ID'])->Fetch()['SRC'];?>"/>
										<?
										}
										else{
										?>
											<img class="brand-prev-img" src="/local/templates/aspro_max_custom/images/svg/noimage_brand.svg"/>
										<?}?>
									</div>
								</a>
							</div>
						</div>
					</div>
					

				
			<?endforeach;?>
			</div>
			<div class="swiper-button-brand-about-prev swiper-button-prev"></div>
			<div class="swiper-button-brand-about-next swiper-button-next"></div>
			<!-- Пагинация -->
			<div class="swiper-pagination-brand-about swiper-pagination"></div>
			</div>
			</div>
			</div>
			</div>
			</div>

<?endif;?>
