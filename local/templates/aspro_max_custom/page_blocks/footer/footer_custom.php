<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme;
$bPrintButton = isset($arTheme['PRINT_BUTTON']) ? ($arTheme['PRINT_BUTTON']['VALUE'] == 'Y' ? true : false) : false;
?>
<div class="footer-v1">
	<div class="footer-inner">
		<div class="footer_top">
			<div class="maxwidth-theme">
				<div class="row">
					<div class="col-md-2 col-sm-3">
						<div class="fourth_bottom_menu">
							<?$APPLICATION->IncludeComponent(
							"bitrix:menu", 
							"bottom2_custom", 
							array(
								"ROOT_MENU_TYPE" => "bottom",
								"MENU_CACHE_TYPE" => "A",
								"MENU_CACHE_TIME" => "3600000",
								"MENU_CACHE_USE_GROUPS" => "N",
								"MENU_CACHE_GET_VARS" => array(
								),
								"MAX_LEVEL" => "1",
								"CHILD_MENU_TYPE" => "",
								"USE_EXT" => "N",
								"DELAY" => "N",
								"ALLOW_MULTI_SELECT" => "Y",
								"COMPONENT_TEMPLATE" => "bottom2_custom"
							),
							false
						);?>
						</div>
					</div>
					<div class="col-md-2 col-sm-3">
						<div class="first_bottom_menu">
							<?$APPLICATION->IncludeComponent(
							"bitrix:menu", 
							"bottom_custom", 
							array(
								"ROOT_MENU_TYPE" => "bottom_company",
								"MENU_CACHE_TYPE" => "A",
								"MENU_CACHE_TIME" => "3600000",
								"MENU_CACHE_USE_GROUPS" => "N",
								"MENU_CACHE_GET_VARS" => array(
								),
								"MAX_LEVEL" => "2",
								"CHILD_MENU_TYPE" => "left",
								"USE_EXT" => "N",
								"DELAY" => "N",
								"ALLOW_MULTI_SELECT" => "Y",
								"COMPONENT_TEMPLATE" => "bottom_custom"
							),
							false
						);?>
						</div>
					</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<div class="second_bottom_menu">
							<?$APPLICATION->IncludeComponent(
							"bitrix:menu", 
							"bottom_custom", 
							array(
								"ROOT_MENU_TYPE" => "bottom_info",
								"MENU_CACHE_TYPE" => "A",
								"MENU_CACHE_TIME" => "3600000",
								"MENU_CACHE_USE_GROUPS" => "N",
								"MENU_CACHE_GET_VARS" => array(
								),
								"MAX_LEVEL" => "2",
								"CHILD_MENU_TYPE" => "left",
								"USE_EXT" => "N",
								"DELAY" => "N",
								"ALLOW_MULTI_SELECT" => "Y",
								"COMPONENT_TEMPLATE" => "bottom_custom"
							),
							false
							);?>
							
						</div>
					</div>
					<div class="contact-block-custom col-md-3 col-sm-12 contact-block">
						<div class="info">
							<div class="row">
								<?if(\Bitrix\Main\Loader::includeModule('subscribe') && $arTheme['HIDE_SUBSCRIBE']['VALUE'] != 'Y'):?>
									<div class="col-md-12 col-sm-12">
										<div class="subscribe_button">
											<span class="btn" data-event="jqm" data-param-id="subscribe" data-param-type="subscribe" data-name="subscribe"><?=GetMessage('SUBSCRIBE_TITLE')?><?=CMax::showIconSvg('subscribe', SITE_TEMPLATE_PATH.'/images/svg/subscribe_small_footer.svg')?></span>
										</div>
									</div>
								<?endif;?>
								<div class="col-md-12 col-sm-12">
									<div class="phone-custom blocks">
										<i class="svg inline svg-inline-phone" aria-hidden="true">
											<svg width="5" height="13"><use xlink:href="/local/templates/aspro_max_custom/images/svg/header_icons_srite.svg#phone_footer"></use></svg>
										</i>
										<?$APPLICATION->IncludeFile(SITE_DIR."/local/include/footer/site-phone.php", array(), array(
												"MODE" => "html",
												"NAME" => "Phone",
												"TEMPLATE" => "include_area.php",
											)
										);?>
									</div>
								</div>
								<div class="col-md-12 col-sm-12">
									<div class="email blocks">
										<i class="svg inline svg-inline-email" aria-hidden="true">
											<svg xmlns="http://www.w3.org/2000/svg" width="11" height="9" viewBox="0 0 11 9">
												<path
													data-name="Rectangle 583 copy 16"
													class="cls-1"
													d="M367,142h-7a2,2,0,0,1-2-2v-5a2,2,0,0,1,2-2h7a2,2,0,0,1,2,2v5A2,2,0,0,1,367,142Zm0-2v-3.039L364,139h-1l-3-2.036V140h7Zm-6.634-5,3.145,2.079L366.634,135h-6.268Z"
													transform="translate(-358 -133)"
												></path>
											</svg>
										</i>
										<?$APPLICATION->IncludeFile(SITE_DIR."/local/include/footer/site-email.php", array(), array(
												"MODE" => "html",
												"NAME" => "Email",
												"TEMPLATE" => "include_area.php",
											)
										);?>
									</div>
								</div>
								<div class="col-md-12 col-sm-12">
									<div class="address blocks">
										<i class="svg inline svg-inline-addr" aria-hidden="true">
											<svg xmlns="http://www.w3.org/2000/svg" width="9" height="12" viewBox="0 0 9 12">
												<path
													class="cls-1"
													d="M959.135,82.315l0.015,0.028L955.5,87l-3.679-4.717,0.008-.013a4.658,4.658,0,0,1-.83-2.655,4.5,4.5,0,1,1,9,0A4.658,4.658,0,0,1,959.135,82.315ZM955.5,77a2.5,2.5,0,0,0-2.5,2.5,2.467,2.467,0,0,0,.326,1.212l-0.014.022,2.181,3.336,2.034-3.117c0.033-.046.063-0.094,0.093-0.142l0.066-.1-0.007-.009a2.468,2.468,0,0,0,.32-1.2A2.5,2.5,0,0,0,955.5,77Z"
													transform="translate(-951 -75)"
												></path>
											</svg>
										</i>
										<?$APPLICATION->IncludeFile(SITE_DIR."/local/include/footer/site-address.php", array(), array(
												"MODE" => "html",
												"NAME" => "Address",
												"TEMPLATE" => "include_area.php",
											)
										);?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="footer_middle">
			<div class="maxwidth-theme">
				<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="social-block">
							<?$APPLICATION->IncludeComponent(
	"aspro:social.info.max", 
	"socialicons-custom", 
	array(
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"CACHE_GROUPS" => "N",
		"COMPONENT_TEMPLATE" => "socialicons-custom",
		"TITLE_BLOCK" => "Присоединяйтесь к нам в социальных сетях!"
	),
	false
);?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="footer_bottom">
			<div class="maxwidth-theme">
				<div class="footer-bottom__items-wrapper">
					<div class="footer-bottom__item copy font_xs">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/copy/copyright.php", Array(), Array(
								"MODE" => "php",
								"NAME" => "Copyright",
								"TEMPLATE" => "include_area.php",
							)
						);?>
					</div>
					<div id="bx-composite-banner"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();
   for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
   k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(12070630, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true,
        ecommerce:"dataLayer"
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/12070630" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->