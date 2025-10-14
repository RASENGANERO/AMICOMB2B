<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?
use Amikomnew;
$sectionSupport = \Amikomnew\BrandsDetail::GetSectionsBrands();
?>



			
<div class="group-content-support">
	<div class="section-format-support">
		<h2 class="main-text-support">Документация</h2>
		<div class="list-items-support">
		<?foreach($sectionSupport as $supItem):?>
			<div class="section-element-support">
				<img data-lazyload="" class="section-img-support ls-is-cached lazyloaded" src="/local/templates/aspro_max_custom/images/docs-brands.png" data-src="/local/templates/aspro_max_custom/images/docs-brands.png">
				<h3 class="section-title-support"><?=$supItem['NAME']?></h3>
				<div class="section-content-support">
					<span class="section-description-support"><?=$supItem['DESCRIPTION']?></span>	
					<a class="section-url-support" href="<?=$supItem['CODE']?>">Перейти в раздел</a>
				</div>
			</div>
		<?endforeach;?>
		</div>
	</div>
</div> 