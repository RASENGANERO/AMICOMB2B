<?
use \Bitrix\Main\Localization\Loc;

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Page\Asset;
Asset::getInstance()->addCss('/local/templates/aspro_max_custom/components/bitrix/news.list/about-clients-list/swiper/swiper.css');
Asset::getInstance()->addJs('/local/templates/aspro_max_custom/components/bitrix/news.list/about-clients-list/swiper/swiper.js');

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
			
			<div class="about-clients-top-block top_block clearfix">
				<a href="/clients/" class="about-clients-maintext">Свою безопасность нам доверили</a>
			</div>
			<div class="item-views news2 md with-border">
			<div class="items<?=(!$arParams['INCLUDE_FILE'] ? '' : ' list');?> s_<?=$arParams['SIZE_IN_ROW'];?>">
			<div class="swiper clients-swiper">
				<div class="swiper-wrapper">
				<?
				$class = "";
				?>
				<?foreach($arResult['ITEMS'] as $index => $arItem):?>
					<div class="swiper-slide-about swiper-slide">
						<div class="client-container-item">
							<div class="image shine<?=($bLineImg ? ' pull-left' : '');?>">
								<a target="_blank" href="<?=$arItem['PROPERTIES']['URL_CLIENT']['VALUE']?>">
									<?if ($arItem['NAME'] === 'ВТБ') {
										$class="image-clients-about-vtb image-clients-about";
									}
									else if ($arItem['NAME'] === 'Fix Price') {
										$class="image-clients-about-fix image-clients-about";
									}
									else {
										$class="image-clients-about";
									}
									?>
									<img class="<?=$class?>" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>"/>
								</a>
							</div>
						</div>
					</div>
					
					

				<?endforeach;?>
				</div>
				<div class="swiper-button-client-about-prev swiper-button-prev"></div>
				<div class="swiper-button-client-about-next swiper-button-next"></div>
				<!-- Пагинация -->
				<div class="swiper-pagination-client-about swiper-pagination"></div>
			</div>
			</div>
			</div>
			</div>
			</div>
			</div>
			


<?endif;?>