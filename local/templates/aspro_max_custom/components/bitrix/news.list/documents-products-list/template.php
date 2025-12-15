<?
use \Bitrix\Main\Localization\Loc;

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $arRegion;
$this->setFrameMode(true);

$arParams['INCLUDE_FILE'] ??= '';
$arParams['SIZE_IN_ROW'] ??= 1;
$arParams['COMPACT'] ??= 'N';
$arParams['NOT_SLIDER'] ??= '';
$arParams['LINKED_MODE'] ??= '';

if($arRegion):
	$adress = strip_tags($arRegion['PROPERTY_ADDRESS_VALUE']['TEXT'] ?? '');
	$phone = implode(', ', array_column($arRegion['PHONES'], 'PHONE'));
else:
	$adress = strip_tags(file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_DIR."include/contacts-site-address.php"));
	$phone = strip_tags(file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_DIR."include/contacts-site-phone-one.php"));
endif;?>
<?if($arResult['ITEMS']):?>
	<div class="content_wrapper_block <?=$templateName;?> <?=(isset($arParams['SIZE_IN_ROW']) && $arParams['SIZE_IN_ROW'] > 1 ? 'with-border' : '')?>">
	<div class="maxwidth-theme only-on-front">
		<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL'] || ($arParams['SHOW_ADD_REVIEW'] == 'Y' && $arParams['TITLE_ADD_REVIEW'])):?>
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
										<span><a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="btn btn-transparent-border-color btn-sm"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a></span>
									<?if($arParams['SHOW_ADD_REVIEW'] == 'Y' && $arParams['TITLE_ADD_REVIEW']):?>
										<span><span class="btn btn-transparent-border-color btn-sm animate-load" data-event="jqm" data-param-form_id="REVIEW" data-name="send_review" title="<?=$arParams['TITLE_ADD_REVIEW'] ;?>"><?=CMax::showIconSvg("resume colored", SITE_TEMPLATE_PATH."/images/svg/leaveareview.svg", "", "", true, false);?></span></span>
									<?endif;?>
								</div>
							<?endif;?>
						</div>
						<div class="col-md-9">
			<?else:?>
				<div class="top_block">
					<?=Aspro\Functions\CAsproMax::showTitleH($arParams['TITLE_BLOCK']);?>
					<?if($arParams['TITLE_BLOCK_ALL']):?>
						<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="pull-right font_upper muted"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
					<?endif;?>
					<?if($arParams['SHOW_ADD_REVIEW'] == 'Y' && $arParams['TITLE_ADD_REVIEW']):?>
						<span class="pull-right documents"><span class="pull-right font_upper muted dark_link animate-load" data-event="jqm" data-param-form_id="REVIEW" data-name="send_review"><?=CMax::showIconSvg("resume", SITE_TEMPLATE_PATH."/images/svg/leaveareview.svg", "", "", true, false);?><span><?=$arParams['TITLE_ADD_REVIEW'] ;?></span></span></span>
					<?endif;?>
				</div>
			<?endif;?>
		<?endif;?>
		<?global $arTheme;
		$slideshowSpeed = abs(intval($arTheme['PARTNERSBANNER_SLIDESSHOWSPEED']['VALUE']));
		$animationSpeed = abs(intval($arTheme['PARTNERSBANNER_ANIMATIONSPEED']['VALUE']));
		$bAnimation = (bool)$slideshowSpeed;
		$col = ($arParams['SIZE_IN_ROW'] ?: 1);
		$bCompact = ($arParams['COMPACT'] == 'Y');
		$bOneItem = ($col == 1);
		$bMoreItem = ($col > 2 || $arParams['INCLUDE_FILE']);
		$notSlider = ($arParams["NOT_SLIDER"] == "Y");
		?>
		<div class="item-views documents<?=($bCompact ? ' compact' : '');?><?=($bMoreItem ? ' more-item' : '');?> <?=($arParams['LINKED_MODE'] == 'Y' ? ' linked ' : '');?> <?=($notSlider ? ' list-mode ' : '');?>" itemscope itemtype="https://schema.org/Review">
			<div itemprop="itemReviewed" itemscope itemtype="https://schema.org/Organization">
				<meta itemprop="name" content="<?=$arResult['SITE_NAME']?>">
				<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
					<span itemprop="streetAddress" content="<?=$adress?>"></span>
				</div>
				<span itemprop="telephone" content="<?=$phone?>"></span>
			</div>
			<?if($notSlider):?>
				<div class="appear-block loading_state<?=(!$bOneItem ? ' shadow' : '');?>" >
			<?else:?>
				<div class="owl-carousel owl-theme owl-bg-nav short-nav hidden-dots visible-nav swipeignore wsmooth1 appear-block loading_state<?=(!$bOneItem ? ' shadow' : '');?>" data-plugin-options='{"nav": true, "dots": false, "loop": false, "marginMove": true, "autoplay": false, <?=($animationSpeed >= 0 ? '"smartSpeed": '.$animationSpeed.',' : '')?> "useCSS": true, "responsive": {"0":{"items": 1, "autoWidth": true, "lightDrag": true, "margin":-1}, "601":{"items": 1, "autoWidth": false, "lightDrag": false, "margin":0}, "1200":{"items": <?=$col?>, "margin":-1}}}'>
			<?endif;?>
				<?foreach($arResult['ITEMS'] as $arItem):?>
					<?
					// edit/add/delete buttons for edit mode
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), ['CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
					// use detail link?
					$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen((string) $arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
					
					// preview image
					$bImage = strlen((string) $arItem['FIELDS']['PREVIEW_PICTURE']['SRC']);
					$arImage = ($bImage ? CFile::ResizeImageGet($arItem['FIELDS']['PREVIEW_PICTURE']['ID'], ['width' => 70, 'height' => 10000], BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : []);
					$imageSrc = ($bImage ? $arImage['src'] : '');

					$bLogo = false;						
					if(!$imageSrc && strlen((string) $arItem['FIELDS']['DETAIL_PICTURE']['SRC']))
					{
						$bImage = strlen((string) $arItem['FIELDS']['DETAIL_PICTURE']['SRC']);
						$arImage = ($bImage ? CFile::ResizeImageGet($arItem['FIELDS']['DETAIL_PICTURE']['ID'], ['width' => 80, 'height' => 10000], BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : []);
						$imageSrc = ($bImage ? $arImage['src'] : '');
						$bLogo = ($imageSrc ? true : false);
					}
					?>
					<?
					$fileUrl = CFile::GetByID($arItem['PROPERTIES']['FILE_DOCUMENT']['VALUE'])->Fetch()['SRC'];
					?>
					<div class="item-wrapper col-xs-12<?=(!$bOneItem ?  ' bg-fill-white bordered1 box-shadow1' : '');?> documents-itemses">
						<div class="item clearfix <?=($bLogo ? ' wlogo' : '')?> <?=(!$bImage ? 'no_img' : '')?> <?=(!$bOneItem ? ' bordered box-shadow' : '');?> <?=($notSlider ? ' rounded2 bordered' : '');?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
							<div class="top_wrapper clearfix">
								<div class="top-info">
									<a target="_blank" class="product-doc-url has-ripple" href="<?=$fileUrl?>"><?=$arItem['NAME'];?></a>									
								</div>
							</div>
						</div>
						
					</div>
				<?endforeach;?>
			</div>
			<?// bottom pagination?>
			<?if($notSlider && $arParams['DISPLAY_BOTTOM_PAGER']):?>
				<div class="bottom_nav_wrapper">
					<div class="bottom_nav animate-load-state<?=($arResult['NAV_STRING'] ? ' has-nav' : '');?>" <?=($isAjax ? "style='display: none; '" : "");?> data-parent=".item-views" data-append=".items.row">
						<?=$arResult['NAV_STRING']?>
					</div>
				</div>
			<?endif;?>
		</div>
		<?if($arParams['INCLUDE_FILE']):?>
			</div></div></div>
		<?endif;?>
	</div></div>
<?endif;?>