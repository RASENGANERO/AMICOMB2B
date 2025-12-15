<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


		<?foreach($arResult["ELEMENTS"] as $arItem):?>
			<div style="display: flex;">
				<div class="title-search-all" colspan="<?=($arParams["SHOW_PREVIEW"]=="Y") ? '3' : '2'?>" >
					<a href="<?=$arItem["URL"]?>"><span class="text"><?=$arItem["NAME"]?></span><span class="icon"><i></i></span></a>
				</div>
				
			</div>
		<?endforeach;?>

