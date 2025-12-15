<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<div class="item-views-wrapper <?=$templateName;?>">
	
	<?if($arResult['SECTIONS']):?>
		<div class="maxwidth-theme">
			<div class="row">
				<div class="col-md-12">
					<div class="contacts-stores shops-list1">
						<?foreach($arResult['SECTIONS'] as $arSection):?>
							
							<?foreach($arSection['ITEMS'] as $arItem):?>
								<?
								// edit/add/delete buttons for edit mode
								$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
								$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), ['CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
								// use detail link?
								$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen((string) $arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
								// preview picture
								$bImage = (isset($arItem['FIELDS']['PREVIEW_PICTURE']) && strlen((string) $arItem['PREVIEW_PICTURE']['SRC']));
								//$imageSrc = ($bImage ? $arItem['PREVIEW_PICTURE']['SRC'] : false);
								$imageDetailSrc = ($bImage ? $arItem['DETAIL_PICTURE']['SRC'] : false);
								$address = ($arItem['PROPERTIES']['ADDRESS']['VALUE'] ? ", ".$arItem['PROPERTIES']['ADDRESS']['VALUE'] : "");
								$typeStore = ($arItem['PROPERTIES']['TYPE_STORE']['VALUE'] ?: "");
								?>


								<div class="item bordered box-shadow<?=(!$bImage ? ' wti' : '')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
									<div class="row">
										<div class="typestore-element title font_mxs darken">
											<span class="darken"><?=$typeStore;?></span>
										</div>
										<div class="col-md-6 col-sm-8 col-xs-12 left-block-contacts">
											
											<div class="top-wrap">
												

												<div class="title font_mxs darken">
													<a href="<?=$arItem["DETAIL_PAGE_URL"];?>" class="darken">
														<?=$arItem['NAME'];?><?=$address;?>
													</a>
												</div>
												<div class="middle-prop">
													<?if($arItem['PROPERTIES']['MAP']['VALUE']):?>
														<div class="show_on_map font_upper colored_theme_text">
															<span class="text_wrap" data-coordinates="<?=$arItem['PROPERTIES']['MAP']['VALUE'];?>">
																<?=CMax::showIconSvg("on_map colored", SITE_TEMPLATE_PATH.'/images/svg/show_on_map.svg');?>
																<span class="text"><?=GetMessage('SHOW_ON_MAP')?></span>
															</span>
														</div>
													<?endif;?>
													
													<?if($arItem['PROPERTIES']['METRO']['VALUE']):?>
														<?foreach($arItem['PROPERTIES']['METRO']['VALUE'] as $metro):?>
															<div class="metro font_upper"><?=CMax::showIconSvg("metro colored", SITE_TEMPLATE_PATH."/images/svg/Metro.svg");?><span class="text muted777"><?=$metro;?></span></div>
														<?endforeach;?>
													<?endif;?>
												</div>
												<?if($arItem['PROPERTIES']['SCHEDULE']['~VALUE']['TEXT'] ?? ''):?>
													<div class="schedule"><?=CMax::showIconSvg("clock colored", SITE_TEMPLATE_PATH."/images/svg/WorkingHours.svg");?><span class="text font_xs muted777"><?=$arItem['PROPERTIES']['SCHEDULE']['~VALUE']['TEXT'];?></span></div>
												<?endif;?>
												<?if($arItem['DISPLAY_PROPERTIES']):?>
													<div>
														<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
															<?if($arProperty["DISPLAY_VALUE"]):?>
																<div class="muted custom_prop <?=strtolower((string) $pid);?>">
																	<div class="icons-text schedule grey s25">
																		<i class="fa"></i>
																		<span class="text_custom">
																			<span class="name"><?=$arProperty["NAME"]?>:&nbsp;</span>
																			<span class="value">
																				<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
																					<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
																				<?else:?>
																					<?=$arProperty["DISPLAY_VALUE"];?>
																				<?endif?>
																			</span>
																		</span>
																	</div>
																</div>
															<?endif?>
														<?endforeach;?>
													</div>
												<?endif;?>
											</div>
										</div>
										<div class="col-md-6 col-sm-4 col-xs-12 right-block-contacts">
											<div class="item-body">
												<div class="contacts-lst-main row">
													<?if($arItem['PROPERTIES']['PHONE']['VALUE']):?>
														<div class="phones">
															<?foreach($arItem['PROPERTIES']['PHONE']['VALUE'] as $phone):?>
																<div class="phone-cust phone font_sm darken">
																	<a href="tel:<?=str_replace([' ', ',', '-', '(', ')'], '', $phone);?>" class="black"><?=$phone;?></a>
																</div>
															<?endforeach;?>
														</div>
													<?endif?>
													<?if($arItem['PROPERTIES']['EMAIL']['VALUE']):?>
														<div class="emails">
															<div class="email-cust email font_sm">
																<a class="dark-color" href="mailto:<?=$arItem['PROPERTIES']['EMAIL']['VALUE'];?>"><?=$arItem['PROPERTIES']['EMAIL']['VALUE'];?></a>
															</div>
														</div>
													<?endif?>
												</div>
											</div>
										</div>

									</div>
								</div>
							<?endforeach;?>
						<?endforeach;?>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>
</div>