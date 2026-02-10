<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

use Bitrix\Main\Localization\Loc;
$skuTemplate = array();
?>
<?
/*
$valuesEmail = $arResult['ITEMS'];
for ($i = 0; $i < count($valuesEmail); $i++) {
	$pictImgID = CIBlockElement::GetProperty(13, $valuesEmail[$i]['ID'], 'sort', 'asc', ['CODE' => 'IMAGE_EMAIL'])->Fetch()['VALUE'];
	//$pictImgSrc = CFile::GetByID($pictImgID)->Fetch()['SRC'];
	//$size = getimagesize('https://test-103817.webtm.ru'.$pictImgSrc);
	//$img = 'data:' . $size['mime'] . ';base64,' . base64_encode(file_get_contents('https://test-103817.webtm.ru'.$pictImgSrc));
	$valuesEmail[$i]['PICT_EMAIL'] = CFile::GetPath(current($pictImgID));
}
$arResult['ITEMS'] = $valuesEmail;*/
?>
<? if(!empty($arResult['ITEMS'])):?>
	<div class="tizers_block" style="font-size:0px;margin:20px 0px 0px;text-align:center;border-top:1px solid #dedede;padding-top:8px;<?=($arParams["FROM_TEMPLATE"] == "Y" ? 'padding-left:30px;padding-right:30px;' : '')?>">
		<?foreach($arResult['ITEMS'] as $arItem):?>
			<?$name = strip_tags($arItem["~NAME"], "<br><br/>");?>
			<?$name_img = strip_tags($arItem["~NAME"]);?>
			<div class="item" style="display: inline-block;vertical-align: middle;font-size: 0px;width: 100%;max-width:155px;color: #000;box-sizing: border-box;padding: 0px 5px 0px 0px;-moz-transition: all 0.1s ease;-o-transition: all 0.1s ease;-ms-transition: all 0.1s ease;transition: all 0.1s ease;margin: 0px 0px 59px;margin: 15px 0px 15px;white-space: nowrap;">
				<?if($arItem["PROPERTIES"]["LINK"]["VALUE"]){?>
					<?
					$url = $arItem["PROPERTIES"]["LINK"]["VALUE"];
					if(strpos($arItem["PROPERTIES"]["LINK"]["VALUE"], "http") === false &&  strpos($arItem["PROPERTIES"]["LINK"]["VALUE"], "https") === false)
						$url = $arParams["SITE_ADDRESS"]."/".$arItem["PROPERTIES"]["LINK"]["VALUE"];
					?>
					<a class="name" href="<?=str_replace(array("//", ":/"), array("/", "://"), $url);?>" style="font-size: 12px;text-decoration: none;color: #000;line-height: 16px;display: block;">
				<?}?>
				<?
					$resImg = CIBlockElement::GetProperty(13,$arItem['ID'],'sort','asc',['CODE'=>'IMAGE_EMAIL'])->Fetch()['VALUE'];
					$path = CFile::GetByID($resImg)->Fetch()['SRC'];
					$arItem["PREVIEW_PICTURE"]["SRC"] = 'https://test-103817.webtm.ru'.$path;
				?>
				<div style="display: block;align-items: center;flex-direction: column;gap: 12px;">
					<div class="img" style="white-space: normal;display: block;vertical-align: middle;margin-left: 22%;margin-right: auto;margin-bottom: 15px;">
						<img style="display: flex;width: auto;height: 41px;" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$name_img;?>" title="<?=$name_img;?>"/>
					</div>				
					<div class="title" style="text-align: left;margin: -6px 0px 0px;white-space: normal;font-size: 12px;vertical-align: middle;max-width: 107px;text-align: center;">
						<?=$name;?>
					</div>
				</div>
				
				<?if($arItem["PROPERTIES"]["LINK"]["VALUE"]){?>
					</a>
				<?}?>
			</div>
		<?endforeach;?>
	</div>	
<? endif ?>