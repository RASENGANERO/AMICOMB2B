<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
use \Bitrix\Main\Localization\Loc;
use Amikomnew;
$docsFilterSections = Amikomnew\BrandsDetail::GetSectionsBrands();

if($arResult["ITEMS"]){?>
	<?global $filter_exists;?>
	<?$filter_exists = "filter_exists";?>
	<?$bActiveFilter = \Aspro\Functions\CAsproMax::checkActiveFilterPage($arParams["SEF_RULE_FILTER"]);?>
	<div class="bx_cust bx_filter bx_filter_vertical n-ajax swipeignore <?=(isset($arResult['EMPTY_ITEMS']) ? 'empty-items': '');?>">
		<div class="slide-block">
			<div class="slide-block__body">
				<div class="bx-filter-section-brands bx_filter_section bordered rounded3">
					<div class="smartfilter">
						<div class="bx-filter-section-brands bx_filter_parameters">
							<?
							$isFilter=false;
							$numVisiblePropValues = 5;

							//ASPRO_FILTER_SORT
							
							//not prices
							$checkVisible = 0;
							foreach($arResult["ITEMS"] as $arItem)
							{
								if(
									empty($arItem["VALUES"])
									|| isset($arItem["PRICE"])
								)
									continue;

								if (
									$arItem["DISPLAY_TYPE"] == "A"
									&& (
										$arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0
									)
								)
									continue;
								$class="";
								if($arItem["DISPLAY_EXPANDED"]=="Y")
									$class="active";

								if($_COOKIE['MAX_filter_prop_'.strtolower((string) $arItem["CODE"])])
								{
									if($_COOKIE['MAX_filter_prop_'.strtolower((string) $arItem["CODE"])] == 2)
										$class = "";
								}
								if($arItem["IS_PROP_INLINE"]){
									$class.= "enumOne";
								}
								$isFilter=true;
								?>
								<div class="bx-filter-section-brands bx_filter_parameters_box <?=$class;?> <?=(isset($arItem['PROPERTY_SET']) && $arItem['PROPERTY_SET'] == 'Y' ? ' set' : '');?>" data-expanded="<?=($arItem["DISPLAY_EXPANDED"] ?: "N");?>" data-prop_code=<?=strtolower((string) $arItem["CODE"]);?> data-property_id="<?=$arItem["ID"]?>" data-property_name="<?=$arItem["NAME"]?>">
									<span data-f="<?=Loc::getMessage('CT_BCSF_SET_FILTER')?>" data-fi="<?=Loc::getMessage('CT_BCSF_SET_FILTER_TI')?>" data-fr="<?=Loc::getMessage('CT_BCSF_SET_FILTER_TR')?>" data-frm="<?=Loc::getMessage('CT_BCSF_SET_FILTER_TRM')?>" class="bx_filter_container_modef"></span>
									
									<?$style="";
									if($arItem["IS_PROP_INLINE"]){
										$style="style='display:block;'";
									}elseif($arItem["DISPLAY_EXPANDED"]!= "Y"){
										$style="style='display:none;'";
									}?>
									<div class="bx-filter-section-brands bx-elements-filter-show bx_filter_block  <?=(!$arItem["IS_PROP_INLINE"] && $arItem["DISPLAY_TYPE"] != 'A' ? '' : '');?> <?=(!$arItem["IS_PROP_INLINE"] && $arItem["PROPERTY_TYPE"]!="N" && ($arItem["DISPLAY_TYPE"] != "P" && $arItem["DISPLAY_TYPE"] != "R") ? "limited_block scrollblock" : "");?>" <?=$style;?>>
										<h4 class="main-text-docs-brand">Документация бренда <?=$arParams['BRAND_NAME']?></h4>
										<div class="filter-brands-elems bx_filter_parameters_box_container <?=($arItem["DISPLAY_TYPE"]=="G" ? "pict_block" : "");?>">
										<?
										$arCur = current($arItem["VALUES"]);
										$count=count($arItem["VALUES"]);
										$i=1;
										if(!$arItem["FILTER_HINT"]){
											$prop = CIBlockProperty::GetByID($arItem["ID"], $arItem["IBLOCK_ID"])->GetNext();
											$arItem["FILTER_HINT"]=$prop["HINT"];
										}
										if($arItem["IBLOCK_ID"]!=$arParams["IBLOCK_ID"] && str_contains((string) $arItem["FILTER_HINT"],'line')){
											$isSize=true;
										}else{
											$isSize=false;
										}?>
										<?$j=1;
										$isHidden = false;?>
										<?
										$valueFilter = str_replace('filterSmartBrandsDetail','filterDocsSmart',strval(array_shift($arItem["VALUES"])["CONTROL_ID"]));
										?>
										
										<?foreach($docsFilterSections as $section):?>
											<div class="section-element">
												<img class="section-img" src="/local/templates/aspro_max_custom/images/docs-brands.png"/>
												<h3 class="section-title"><?=$section['NAME']?></h3>
												<div class="docs-item-icon"><i class="icon icon-instruction"></i></div>
												<div class="section-content">
													<span class="section-description"><?=$section['DESCRIPTION']?></span>	
													<?
													$valueFilterURL = $section['CODE'].'?'.$valueFilter.'=Y&set_filter=';
													?>
													<a class="section-filter-url" href="<?=$valueFilterURL?>">Перейти в раздел</a>
												</div>
											</div>
												
										<?endforeach;?>
										</div>
									</div>
								</div>
							<?}?>
						</div>
					</div>
					
					
				</div>
			</div>
		</div>
	</div>
	<script>
		<?if($arParams['TOP_VERTICAL_FILTER_PANEL'] == 'Y'){
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/asproFilterHelper.js');?>
			window.FilterHelper = new asproFilterHelper(this, true);
			FilterHelper.show();
		<?}?>
	</script>
	<script>
		var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=$arParams["VIEW_MODE"];?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
		<?if(!$isFilter){?>
			$('.bx_filter_vertical').remove();
		<?}?>
		$(document).ready(function(){
			$('.reset-result').on('click', function(){
				location.href = $(this).data('del-url');
			})
		})
	</script>
<?}?>