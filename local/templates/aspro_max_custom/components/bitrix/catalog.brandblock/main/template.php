<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

if (empty($arResult["BRAND_BLOCKS"]))
	return;
$strRand = $this->randString();
$strObName = 'obIblockBrand_'.$strRand;
$blockID = 'bx_IblockBrand_'.$strRand;
$mouseEvents = 'onmouseover="'.$strObName.'.itemOver(this);" onmouseout="'.$strObName.'.itemOut(this)"';


?>
<div class="bx_item_detail_inc_two">
	<div class="tizers_block" data-slice="Y">
		<div class="row">
		<?
		$handlerIDS = [];
		$count = count($arResult["BRAND_BLOCKS"]);
		$class_md = match ($count) {
            5 => 2,
            4 => 3,
            3 => 4,
            2 => 6,
            default => 12,
        };
		foreach ($arResult["BRAND_BLOCKS"] as $arBB)
		{
			$brandID = 'brand_'.$arResult['ID'].'_'.$strRand;
			$popupID = $brandID.'_popup';

			$usePopup = $arBB['FULL_DESCRIPTION'] !== false;
			$useLink = $arBB['LINK'] !== false;
			if ($useLink)
				$arBB['LINK'] = htmlspecialcharsbx($arBB['LINK']);
			$bImage = $arBB['PICT']['SRC'];
			$arImage = ($bImage ? CFile::ResizeImageGet($arBB['PICT'], ['width' => 60, 'height' => 60], BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : []);
			$imageSrc = ($bImage ? $arImage['src'] : false);

			switch ($arBB['TYPE'])
			{
				default:?>
					<div class="col-md-<?=$class_md;?>">
						<div class="item_block">
							<div class="inner_wrapper item <?=($bImage ? '' : ' wti')?> noborder clearfix" data-slice-block="Y" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
								<?if($bImage):?>
									<div class="img">
										<?if($useLink):?><a href="<?=$arBB['LINK']?>"><?endif;?>
										<img src=<?=$imageSrc?> />
										<?if($useLink):?></a><?endif;?>
									</div>
								<?endif;?>
								<div class="title">
									<?if($useLink):?><a href="<?=$arBB['LINK']?>"><?endif;?>
										<?=$arBB['NAME']?>
									<?if($useLink):?></a><?endif;?>
								</div>
							</div>
						</div>
					</div>
			<?}
		}?>
		</div>
	</div>
</div>