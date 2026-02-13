<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();?>
<?$this->setFrameMode(true);?>
<?
if(!function_exists("ShowSubItems")){
	function ShowSubItems($arItem){
		?>
		<?if($arItem["SELECTED"] && isset($arItem["CHILD"]) && $arItem["CHILD"]):?>
			<?$noMoreSubMenuOnThisDepth = false;?>
			<div class="submenu-wrapper">
				<ul class="submenu">
					<?foreach($arItem["CHILD"] as $arSubItem):?>
						<li class="<?=($arSubItem["SELECTED"] ? "active" : "")?><?=($arSubItem["CHILD"] ? " child" : "")?>">
							<a href="<?=$arSubItem["LINK"]?>"><?=$arSubItem["TEXT"]?></a>
							<?if(!$noMoreSubMenuOnThisDepth):?>
								<?ShowSubItems($arSubItem);?>
							<?endif;?>
						</li>
						<?$noMoreSubMenuOnThisDepth |= CMax::isChildsSelected($arSubItem["CHILD"]);?>
					<?endforeach;?>
				</ul>
			</div>
		<?endif;?>
		<?
	}
}
?>

<?if($arResult):?>
	<ul class="dropdown-menu">
		<?foreach($arResult as $arItem):?>
			<?
			if (strpos($_SERVER['REQUEST_URI'],'/b2b/') !== false) {

				if ($arItem['LINK'] === $_SERVER['REQUEST_URI']) {
					$arItem['SELECTED'] = 1;
				}
				else {
					$arItem['SELECTED'] = 0;
				}
			}
			?>
			<li class="<?=($arItem["SELECTED"] === 1 ? "active" : "")?> <?=(isset($arItem["CHILD"]) && $arItem["CHILD"] ? "child" : "")?>">
				<?
				if( isset($arItem["PARAMS"]["class"]) && $arItem["PARAMS"]["class"] === 'exit' ){
					$arItem["LINK"].= '&'.bitrix_sessid_get();
				}
				?>
				<a href="<?=$arItem["LINK"]?>" class="dark-color"><?=(isset($arItem["PARAMS"]["BLOCK"]) && $arItem["PARAMS"]["BLOCK"] ? $arItem["PARAMS"]["BLOCK"] : "");?><?=$arItem["TEXT"]?></a>
				<?ShowSubItems($arItem);?>
			</li>
		<?endforeach;?>
	</ul>
<?endif;?>