<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?
use Bitrix\Main\Page\Asset;
Asset::getInstance()->addCss('/local/templates/aspro_max_custom/components/bitrix/news.detail/shops_custom/style.css');
	$arShop = $arResult;

	global $APPLICATION;
	$mapLAT = $mapLON = 0;
	$arPlacemarks = array();
	$arPhotos = array();
	if(is_array($arShop)){
		if(isset($arShop['IBLOCK_ID'])){
			$arShop['LIST_URL'] = $arShop['LIST_PAGE_URL'];
			$arShop['TITLE'] = (in_array('NAME', $arParams['FIELD_CODE']) ? strip_tags($arShop['~NAME']) : '');
			$arShop['ADDRESS'] = $arShop['DISPLAY_PROPERTIES']['ADDRESS']['VALUE'];
			$arShop['ADDRESS'] = $arShop['TITLE'].((strlen($arShop['TITLE']) && strlen($arShop['ADDRESS'])) ? ', ' : '').$arShop['ADDRESS'];
			$arShop['PHONE'] = $arShop['DISPLAY_PROPERTIES']['PHONE']['VALUE'];
			$arShop['EMAIL'] = $arShop['DISPLAY_PROPERTIES']['EMAIL']['VALUE'];
			if(
				isset($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['VALUE']['TYPE']) &&
				strToLower($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['VALUE']['TYPE']) == 'html'
			){
				$arShop['SCHEDULE'] = htmlspecialchars_decode($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['~VALUE']['TEXT']);
			}
			else{
				$arShop['SCHEDULE'] = nl2br($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['~VALUE']['TEXT'] ?? '');
			}
			$arShop['URL'] = $arShop['DETAIL_PAGE_URL'];
			$arShop['METRO_PLACEMARK_HTML'] = '';
			if($arShop['METRO'] = $arShop['DISPLAY_PROPERTIES']['METRO']['VALUE']){
				if(!is_array($arShop['METRO'])){
					$arShop['METRO'] = array($arShop['METRO']);
				}
				foreach($arShop['METRO'] as $metro){
					$arShop['METRO_PLACEMARK_HTML'] .= '<div class="metro"><i></i>'.$metro.'</div>';
				}
			}
			$arShop['DESCRIPTION'] = $arShop['DETAIL_TEXT'];
			$imageID = ((in_array('DETAIL_PICTURE', $arParams['FIELD_CODE']) && $arShop["DETAIL_PICTURE"]['ID']) ? $arShop["DETAIL_PICTURE"]['ID'] : false);
			if($imageID){
				$arShop['IMAGE'] = CFile::ResizeImageGet($imageID, array('width' => 600, 'height' => 600), BX_RESIZE_IMAGE_PROPORTIONAL);
				$arPhotos[] = array(
					'ID' => $arShop["DETAIL_PICTURE"]['ID'],
					'ORIGINAL' => ($arShop["DETAIL_PICTURE"]['SRC'] ? $arShop["DETAIL_PICTURE"]['SRC'] : $arShop['IMAGE']),
					'PREVIEW' => $arShop['IMAGE'],
					'DESCRIPTION' => (strlen($arShop["DETAIL_PICTURE"]['DESCRIPTION']) ? $arShop["DETAIL_PICTURE"]['DESCRIPTION'] : $arShop['ADDRESS']),
				);
			}
			if(is_array($arShop['DISPLAY_PROPERTIES']['MORE_PHOTOS']['VALUE'])) {
				foreach($arShop['DISPLAY_PROPERTIES']['MORE_PHOTOS']['VALUE'] as $i => $photoID){
					$arPhotos[] = array(
						'ID' => $photoID,
						'ORIGINAL' => CFile::GetPath($photoID),
						'PREVIEW' => CFile::ResizeImageGet($photoID, array('width' => 600, 'height' => 600), BX_RESIZE_IMAGE_PROPORTIONAL),
						'DESCRIPTION' => $arShop['DISPLAY_PROPERTIES']['MORE_PHOTOS']['DESCRIPTION'][$i],
					);
				}
			}

			$arShop['GPS_S'] = 0;
			$arShop['GPS_N'] = 0;

			if(
				isset($arShop['DISPLAY_PROPERTIES']['MAP']['VALUE']) &&
				strlen($arShop['DISPLAY_PROPERTIES']['MAP']['VALUE']) &&
				$arStoreMap = explode(',', $arShop['DISPLAY_PROPERTIES']['MAP']['VALUE'])
			){
				$arShop['GPS_S'] = $arStoreMap[0];
				$arShop['GPS_N'] = $arStoreMap[1];
			}

			if($arShop['GPS_S'] && $arShop['GPS_N']){
				$mapLAT += $arShop['GPS_S'];
				$mapLON += $arShop['GPS_N'];
				$str_phones = '';
				if($arShop['PHONE'])
				{
					foreach($arShop['PHONE'] as $phone)
					{
						$str_phones .= '<div class="phone"><a rel="nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $phone).'">'.$phone.'</a></div>';
					}
				}

				$html = CMax::prepareItemMapHtml($arShop);

				$arPlacemarks[] = array(
					"ID" => $arShop["ID"],
					"LAT" => $arShop['GPS_S'],
					"LON" => $arShop['GPS_N'],
					// "TEXT" => $arShop["TITLE"],
					"TEXT" => $html
				);
			}
		}
		else{
			$arShop["TITLE"] = strip_tags(htmlspecialchars_decode($arShop["TITLE"]));
			$arShop["ADDRESS"] = htmlspecialchars_decode($arShop["ADDRESS"]);
			$arShop["ADDRESS"] = (strlen($arShop["TITLE"]) ? $arShop["TITLE"].', ' : '').$arShop["ADDRESS"];
			$arShop["DESCRIPTION"] = htmlspecialchars_decode($arShop['DESCRIPTION']);
			$arShop['SCHEDULE'] = htmlspecialchars_decode($arShop['SCHEDULE']);
			if($arShop["IMAGE_ID"]  && $arShop["IMAGE_ID"] != "null"){
				$arShop['IMAGE'] = CFile::ResizeImageGet($arShop["IMAGE_ID"], array('width' => 600, 'height' => 600), BX_RESIZE_IMAGE_PROPORTIONAL );
				$arPhotos[] = array(
					'ID' => $arShop["PREVIEW_PICTURE"]['ID'],
					'ORIGINAL' => CFile::GetPath($arShop["IMAGE_ID"]),
					'PREVIEW' => $arShop['IMAGE'],
					'DESCRIPTION' => (strlen($arShop["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arShop["PREVIEW_PICTURE"]['DESCRIPTION'] : $arShop["ADDRESS"]),
				);
			}
			if(is_array($arShop['MORE_PHOTOS'])) {
				foreach($arShop['MORE_PHOTOS'] as $photoID){
					$arPhotos[] = array(
						'ID' => $photoID,
						'ORIGINAL' => CFile::GetPath($photoID),
						'PREVIEW' => CFile::ResizeImageGet($photoID, array('width' => 600, 'height' => 600), BX_RESIZE_IMAGE_PROPORTIONAL ),
						'DESCRIPTION' => $arShop["ADDRESS"],
					);
				}
			}

			$str_phones = '';
			if($arShop['PHONE'])
			{
				foreach($arShop['PHONE'] as $phone)
				{
					$str_phones .= '<div class="phone"><a rel="nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $phone).'">'.$phone.'</a></div>';
				}
			}
			if($arShop['GPS_S'] && $arShop['GPS_N']){
				$mapLAT += $arShop['GPS_N'];
				$mapLON += $arShop['GPS_S'];

				$html = self::prepareItemMapHtml($arShop, "Y");

				$arPlacemarks[] = array(
					"ID" => $arShop["ID"],
					"LON" => $arShop['GPS_S'],
					"LAT" => $arShop['GPS_N'],
					"TEXT" => $html,
					"HTML" => $html
				);
			}
		}
		?>


		<?/*<div class="wrapper_inner shop-detail1">*/?>

			<div class="item item-shop-detail1  <?=($showMap ? 'col-md-6' : 'col-md-12')?>">
				<div class="left_block_store <?=($showMap ? '' : 'margin0')?>">
					<?//if(in_array('NAME', $arParams['LIST_FIELD_CODE']) || in_array('PREVIEW_PICTURE', $arParams['LIST_FIELD_CODE']) && $arItem['PREVIEW_PICTURE']):?>
						
					<?//endif;?>
					<div class="bottom_block">
						<div class="top_cust top_block">
							<?if(strlen($arShop['ADDRESS'])):?>
								<div class="address_cust address">
									<span class="title font_upper muted"><?=GetMessage('ADDRESS')?></span>
									<span class="addr-cust value darken"><?=$arShop['ADDRESS']?></span>
								</div>
							<?endif;?>
						</div>
						<div class="properties clearfix">
							<div class="col-md-6 col-sm-6">
								<?if($arShop["METRO"]):?>
									<?foreach($arShop["METRO"] as $metro):?>
										<div class="property metro">
											<div class="title font_upper"><?=GetMessage('METRO')?></div>
											<div class="value darken"><?=$metro;?></div>
										</div>
									<?endforeach;?>
								<?endif;?>
								<?if($arShop["SCHEDULE"]):?>
									<div class="property schedule">
										<div class="title font_upper"><?=GetMessage('SCHEDULE')?></div>
										<div class="value darken"><?=$arShop["SCHEDULE"];?></div>
									</div>
								<?endif;?>
							</div>
							<div class="col-md-6 col-sm-6">
								<?if($arShop["PHONE"]):?>
									<div class="property phone">
										<div class="title font_upper"><?=GetMessage('PHONE')?></div>
										<?foreach($arShop["PHONE"] as $phone):?>
											<div class="value phone darken">
												<a href="tel:<?=str_replace(array(' ', ',', '-', '(', ')'), '', $phone);?>" rel="nofollow" class="black"><?=$phone;?></a>
											</div>
										<?endforeach;?>
									</div>
								<?endif?>
								<?if(strlen($arShop["EMAIL"])):?>
									<div class="property email">
										<div class="title font_upper">Email</div>
										<div class="value darken"><a class="dark-color" rel="nofollow" href="mailto:<?=$arShop["EMAIL"];?>"><?=$arShop["EMAIL"];?></a></div>
									</div>
								<?endif;?>
							</div>

						</div>
						
						<div class="feedbacks-cust feedback item">
							<div class="wrap">
								<?$GLOBALS['filterDetailContact'] = array('!ID'=>$arShop['ID']);?>
								<?
								$APPLICATION->IncludeComponent(
									"bitrix:news.list",
									"contacts-list",
									Array(
									'COUNT_IN_LINE' => 3,
									'USE_FILTER'=>'Y',
									'FILTER_NAME'=>'filterDetailContact',
									'SHOW_SECTION_PREVIEW_DESCRIPTION' => 'Y',
									'VIEW_TYPE' => 'list',
									'SHOW_TABS' => 'Y',
									'IMAGE_POSITION' => 'left',
									'IBLOCK_TYPE' => 'aspro_max_content',
									'IBLOCK_ID' => 14,
									'NEWS_COUNT' => 20,
									'SORT_BY1' => 'ACTIVE_FROM',
									'SORT_ORDER1' => 'DESC',
									'SORT_BY2' => 'SORT',
									'SORT_ORDER2' => 'ASC',
									'FIELD_CODE' => array(
										'NAME',
										'PREVIEW_TEXT',
										'PREVIEW_PICTURE',
										'DATE_ACTIVE_FROM',
									),
									'PROPERTY_CODE' => array(
										'',
										'PERIOD',
										'REDIRECT',
										'',
									),
									'DISPLAY_PANEL' => 'N',
									'SET_TITLE' => 'N',
									'SET_STATUS_404' => 'N',
									'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
									'ADD_SECTIONS_CHAIN' => 'N',
									'CACHE_TYPE' => 'A',
									'CACHE_TIME' => 100000,
									'CACHE_FILTER' => 'Y',
									'CACHE_GROUPS' => 'N',
									'DISPLAY_TOP_PAGER' => 'N',
									'DISPLAY_BOTTOM_PAGER' => 'Y',
									'PAGER_TITLE' => 'Новости',
									'PAGER_TEMPLATE' => '.default',
									'PAGER_SHOW_ALWAYS' => 'N',
									'PAGER_DESC_NUMBERING' => 'N',
									'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
									'PAGER_SHOW_ALL' => 'N',
									'DISPLAY_DATE' => 'N',
									'DISPLAY_NAME' => 'N',
									'DISPLAY_PICTURE' => 'N',
									'DISPLAY_PREVIEW_TEXT' => 'N',
									'PREVIEW_TRUNCATE_LEN' => 0,
									'ACTIVE_DATE_FORMAT' => 'j F Y',
									'USE_PERMISSIONS' => 'N',
									'GROUP_PERMISSIONS' => array(1),
									'HIDE_LINK_WHEN_NO_DETAIL' => 'N',
									'CHECK_DATES' => 'Y',
									'PARENT_SECTION' => '',
									'PARENT_SECTION_CODE' => '',
									'DETAIL_URL' => '/contacts/stores/#ELEMENT_ID#/',
									'SECTION_URL' => '/contacts/',
									'IBLOCK_URL' => '/contacts/',
									'INCLUDE_SUBSECTIONS' => 'Y',
									'SHOW_DETAIL_LINK' => 'Y'
								));?>
								<?//if(1 || $bUseFeedback):?>
									<div class="button_wrap">
										<span>
											<span class="btn  btn-transparent-border-color white  animate-load" data-event="jqm" data-param-form_id="ASK" data-name="contacts">Задать вопрос</span>
										</span>
									</div>
								<?//endif;?>
							</div>
						</div>
						<div class="clearboth"></div>
						<!-- noindex-->
						<div class="bottom-links-block">
							<a class="muted back-url url-block" href="javascript:history.back();">
								<?=CMax::showIconSvg("return_to_the_list", SITE_TEMPLATE_PATH."/images/svg/return_to_the_list.svg", "");?>
							<span class="font_upper back-url-text"><?=GetMessage('BACK_STORE_LIST')?></span></a>

							<?//if($arParams["USE_SHARE"] == "Y"):?>
								<?//\Aspro\Functions\CAsproMax::showShareBlock('bottom')?>
							<?//endif;?>
						</div>
						<!-- /noindex-->
					</div>
					
				</div>

			</div>
			<?if($showMap == "Y"):?>
				<div class="item col-md-6 map-full padding0">
					<div class="right_block_store contacts_map">
						<?if(abs($mapLAT) > 0 && abs($mapLON) > 0 && $showMap=="Y"):?>
							<?//<div class="contacts_map">?>
								<?if($arParams["MAP_TYPE"] != "0"):?>
									<?$APPLICATION->IncludeComponent(
										"bitrix:map.google.view",
										"",
										array(
											"INIT_MAP_TYPE" => "ROADMAP",
											"MAP_DATA" => serialize(array("google_lat" => $mapLAT, "google_lon" => $mapLON, "google_scale" => 16, "PLACEMARKS" => $arPlacemarks)),
											"MAP_WIDTH" => "100%",
											"MAP_HEIGHT" => "100%",
											"CONTROLS" => array(
											),
											"OPTIONS" => array(
												0 => "ENABLE_DBLCLICK_ZOOM",
												1 => "ENABLE_DRAGGING",
											),
											"MAP_ID" => "",
											"ZOOM_BLOCK" => array(
												"POSITION" => "right center",
											),
											"COMPONENT_TEMPLATE" => "map",
											"API_KEY" => $arParams["GOOGLE_API_KEY"],
											"COMPOSITE_FRAME_MODE" => "A",
											"COMPOSITE_FRAME_TYPE" => "AUTO"
										),
										false, array("HIDE_ICONS" =>"Y")
									);?>
								<?else:?>
									<?$APPLICATION->IncludeComponent(
										"bitrix:map.yandex.view",
										"map",
										array(
											"INIT_MAP_TYPE" => "ROADMAP",
											"MAP_DATA" => serialize(array("yandex_lat" => $mapLAT, "yandex_lon" => $mapLON, "yandex_scale" => 17, "PLACEMARKS" => $arPlacemarks)),
											"MAP_WIDTH" => "100%",
											"MAP_HEIGHT" => "100%",
											"CONTROLS" => array(
												0 => "ZOOM",
												1 => "SMALLZOOM",
												3 => "TYPECONTROL",
												4 => "SCALELINE",
											),
											"OPTIONS" => array(
												0 => "ENABLE_DBLCLICK_ZOOM",
												1 => "ENABLE_DRAGGING",
											),
											"MAP_ID" => "",
											"ZOOM_BLOCK" => array(
												"POSITION" => "right center",
											),
											"COMPONENT_TEMPLATE" => "map",
											"API_KEY" => $arParams["GOOGLE_API_KEY"],
											"COMPOSITE_FRAME_MODE" => "A",
											"COMPOSITE_FRAME_TYPE" => "AUTO"
										),
										false, array("HIDE_ICONS" =>"Y")
									);?>
								<?endif;?>
							<?//</div>?>
						<?endif;?>
					</div>
				</div>
			<?endif;?>




		<?/*</div>
		<div class="clearboth"></div>
		*/?>
		<?
	}
	else{
		LocalRedirect(SITE_DIR.'contacts/');
	}