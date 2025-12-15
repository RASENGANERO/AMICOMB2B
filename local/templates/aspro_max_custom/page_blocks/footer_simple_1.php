<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme;
$bPrintButton = isset($arTheme['PRINT_BUTTON']) && $arTheme['PRINT_BUTTON']['VALUE'] == 'Y' ? true : false;
?>
<div class="footer-v1">
	<div class="footer-inner light">
		<div class="footer_bottom">
			<div class="maxwidth-theme">
				<div class="footer-bottom__items-wrapper">
					<div class="footer-bottom__item copy font_xs">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/copy/copyright.php", [], [
								"MODE" => "php",
								"NAME" => "Copyright",
								"TEMPLATE" => "include_area.php",
							]
						);?>
					</div>
					<?=\Aspro\Functions\CAsproMax::showDeveloperBlock('light');?>
				</div>
			</div>
		</div>
	</div>
</div>