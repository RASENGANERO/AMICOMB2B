<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
	die();
}




/** @var CBitrixPersonalOrderListComponent $component */
/** @var array $arParams */
/** @var array $arResult */

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use AmikomB2B;
use Bitrix\Sale\Delivery\Services\Manager;

global $USER;
$userID = \AmikomB2B\DiscountInfo::getPartnerID($USER->GetID());
$companyInfo = \AmikomB2B\DataB2BUser::getNameSeller($userID);


\Bitrix\Main\UI\Extension::load([
	'ui.design-tokens',
	'ui.fonts.opensans',
	'clipboard',
	'fx',
]);

Asset::getInstance()->addJs("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/script.js");
Asset::getInstance()->addCss("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/style.css");
$this->addExternalCss("/bitrix/css/main/bootstrap.css");

Loc::loadMessages(__FILE__);

if (!empty($arResult['ERRORS']['FATAL']))
{
	foreach($arResult['ERRORS']['FATAL'] as $error)
	{
		ShowError($error);
	}
	$component = $this->__component;
	if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED]))
	{
		$APPLICATION->AuthForm('', false, false, 'N', false);
	}

}
else
{
	$filterHistory = ($_REQUEST['filter_history'] ?? '');
	$filterShowCanceled = ($_REQUEST["show_canceled"] ?? '');

	if (!empty($arResult['ERRORS']['NONFATAL']))
	{
		foreach($arResult['ERRORS']['NONFATAL'] as $error)
		{
			ShowError($error);
		}
	}
	if (empty($arResult['ORDERS']))
	{
		if ($filterHistory === 'Y')
		{
			if ($filterShowCanceled === 'Y')
			{
				?>
				<h3><?= Loc::getMessage('SPOL_TPL_EMPTY_CANCELED_ORDER')?></h3>
				<?
			}
			else
			{
				?>
				<h3><?= Loc::getMessage('SPOL_TPL_EMPTY_HISTORY_ORDER_LIST')?></h3>
				<?
			}
		}
		else
		{
			?>
			<h3><?= Loc::getMessage('SPOL_TPL_EMPTY_ORDER_LIST')?></h3>
			<?
		}
	}
	?>
	
	<?
	if (empty($arResult['ORDERS']))
	{
		?>
		<div class="row col-md-12 col-sm-12">
			<a href="<?=htmlspecialcharsbx($arParams['PATH_TO_CATALOG'])?>" class="sale-order-history-link">
				<?=Loc::getMessage('SPOL_TPL_LINK_TO_CATALOG')?>
			</a>
		</div>
		<?
	}

	if ($filterHistory !== 'Y')
	{
		$paymentChangeData = array();
		$orderHeaderStatus = null;

		foreach ($arResult['ORDERS'] as $key => $order)
		{
			if ($orderHeaderStatus !== $order['ORDER']['STATUS_ID'] && $arResult['SORT_TYPE'] == 'STATUS')
			{
				$orderHeaderStatus = $order['ORDER']['STATUS_ID'];

				?>
				<h1 class="sale-order-title">
					<?= Loc::getMessage('SPOL_TPL_ORDER_IN_STATUSES') ?> &laquo;<?=htmlspecialcharsbx($arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME'])?>&raquo;
				</h1>
				<?
			}
			?>
			<div class="col-md-12 col-sm-12 sale-order-list-container">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12 sale-order-list-title-container">
						<h2 class="sale-order-list-title">
							<?=Loc::getMessage('SPOL_TPL_ORDER')?>
							<?=Loc::getMessage('SPOL_TPL_NUMBER_SIGN').$order['ORDER']['ACCOUNT_NUMBER']?>
							<?=Loc::getMessage('SPOL_TPL_FROM_DATE')?>
							<?=$order['ORDER']['DATE_INSERT_FORMATED']?>,
							<?=count($order['BASKET_ITEMS']);?>
							<?
							$count = count($order['BASKET_ITEMS']) % 10;
							if ($count == '1')
							{
								echo Loc::getMessage('SPOL_TPL_GOOD');
							}
							elseif ($count >= '2' && $count <= '4')
							{
								echo Loc::getMessage('SPOL_TPL_TWO_GOODS');
							}
							else
							{
								echo Loc::getMessage('SPOL_TPL_GOODS');
							}
							?>
							<?=Loc::getMessage('SPOL_TPL_SUMOF')?>
							<?=$order['ORDER']['FORMATED_PRICE']?>
						</h2>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 sale-order-list-inner-container">
						<span class="sale-order-list-inner-title-line">
							<span class="sale-order-list-inner-title-line-item"><?=Loc::getMessage('SPOL_TPL_PAYMENT')?></span>
							<span class="sale-order-list-inner-title-line-border"></span>
						</span>
						<?
						$showDelimeter = false;
						foreach ($order['PAYMENT'] as $payment)
						{
							if ($order['ORDER']['LOCK_CHANGE_PAYSYSTEM'] !== 'Y')
							{
								$paymentChangeData[$payment['ACCOUNT_NUMBER']] = array(
									"order" => htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']),
									"payment" => htmlspecialcharsbx($payment['ACCOUNT_NUMBER']),
									"allow_inner" => $arParams['ALLOW_INNER'],
									"refresh_prices" => $arParams['REFRESH_PRICES'],
									"path_to_payment" => $arParams['PATH_TO_PAYMENT'],
									"only_inner_full" => $arParams['ONLY_INNER_FULL'],
									"return_url" => $arResult['RETURN_URL'],
								);
							}
							?>
							<div class="row sale-order-list-inner-row">
								<?
								if ($showDelimeter)
								{
									?>
									<div class="sale-order-list-top-border"></div>
									<?
								}
								else
								{
									$showDelimeter = true;
								}
								?>

								<div class="sale-order-list-inner-row-body">
									<div class="col-md-9 col-sm-8 col-xs-12 sale-order-list-payment">
										<div class="sale-order-list-payment-title">
											<?
											$paymentSubTitle = Loc::getMessage('SPOL_TPL_BILL')." ".Loc::getMessage('SPOL_TPL_NUMBER_SIGN').htmlspecialcharsbx($payment['ACCOUNT_NUMBER']);
											if(isset($payment['DATE_BILL']))
											{
												$paymentSubTitle .= " ".Loc::getMessage('SPOL_TPL_FROM_DATE')." ".$payment['DATE_BILL_FORMATED'];
											}
											$paymentSubTitle .=",";
											echo $paymentSubTitle;
											?>
											<span class="sale-order-list-payment-title-element"><?=$payment['PAY_SYSTEM_NAME']?></span>
											<?
											if ($payment['PAID'] === 'Y')
											{
												?>
												<span class="sale-order-list-status-success"><?=Loc::getMessage('SPOL_TPL_PAID')?></span>
												<?
											}
											elseif ($order['ORDER']['IS_ALLOW_PAY'] == 'N')
											{
												?>
												<span class="sale-order-list-status-restricted"><?=Loc::getMessage('SPOL_TPL_RESTRICTED_PAID')?></span>
												<?
											}
											else
											{
												?>
												<span class="sale-order-list-status-alert"><?=Loc::getMessage('SPOL_TPL_NOTPAID')?></span>
												<?
											}
											?>
										</div>
										<div class="sale-order-list-payment-price">
											<span class="sale-order-list-payment-element"><?=Loc::getMessage('SPOL_TPL_SUM_TO_PAID')?>:</span>

											<span class="sale-order-list-payment-number"><?=$payment['FORMATED_SUM']?></span>
										</div>
										<?
										if (!empty($payment['CHECK_DATA']))
										{
											$listCheckLinks = "";
											foreach ($payment['CHECK_DATA'] as $checkInfo)
											{
												$title = Loc::getMessage('SPOL_CHECK_NUM', array('#CHECK_NUMBER#' => $checkInfo['ID']))." - ". htmlspecialcharsbx($checkInfo['TYPE_NAME']);
												if($checkInfo['LINK'] <> '')
												{
													$link = $checkInfo['LINK'];
													$listCheckLinks .= "<div><a href='$link' target='_blank'>$title</a></div>";
												}
											}
											if ($listCheckLinks <> '')
											{
												?>
												<div class="sale-order-list-payment-check">
													<div class="sale-order-list-payment-check-left"><?= Loc::getMessage('SPOL_CHECK_TITLE')?>:</div>
													<div class="sale-order-list-payment-check-left">
														<?=$listCheckLinks?>
													</div>
												</div>
												<?
											}
										}
										if ($payment['PAID'] !== 'Y' && $order['ORDER']['LOCK_CHANGE_PAYSYSTEM'] !== 'Y')
										{
											?>
											<a href="#" class="sale-order-list-change-payment" id="<?= htmlspecialcharsbx($payment['ACCOUNT_NUMBER']) ?>">
												<?= Loc::getMessage('SPOL_TPL_CHANGE_PAY_TYPE') ?>
											</a>
											<?
										}
										if ($order['ORDER']['IS_ALLOW_PAY'] == 'N' && $payment['PAID'] !== 'Y')
										{
											?>
											<div class="sale-order-list-status-restricted-message-block">
												<span class="sale-order-list-status-restricted-message"><?=Loc::getMessage('SOPL_TPL_RESTRICTED_PAID_MESSAGE')?></span>
											</div>
											<?
										}
										?>

									</div>
									<?
									if ($payment['PAID'] === 'N' && $payment['IS_CASH'] !== 'Y' && $payment['ACTION_FILE'] !== 'cash')
									{
										if ($order['ORDER']['IS_ALLOW_PAY'] == 'N')
										{
											?>
											<div class="col-md-3 col-sm-4 col-xs-12 sale-order-list-button-container">
												<a class="sale-order-list-button inactive-button">
													<?=Loc::getMessage('SPOL_TPL_PAY')?>
												</a>
											</div>
											<?
										}
										elseif ($payment['NEW_WINDOW'] === 'Y')
										{
											?>
											<div class="col-md-3 col-sm-4 col-xs-12 sale-order-list-button-container">
												<a class="sale-order-list-button" target="_blank" href="<?=htmlspecialcharsbx($payment['PSA_ACTION_FILE'])?>">
													<?=Loc::getMessage('SPOL_TPL_PAY')?>
												</a>
											</div>
											<?
										}
										else
										{
											?>
											<div class="col-md-3 col-sm-4 col-xs-12 sale-order-list-button-container">
												<a class="sale-order-list-button ajax_reload" href="<?=htmlspecialcharsbx($payment['PSA_ACTION_FILE'])?>">
													<?=Loc::getMessage('SPOL_TPL_PAY')?>
												</a>
											</div>
											<?
										}
									}
									?>

								</div>
								<div class="col-lg-9 col-md-9 col-sm-10 col-xs-12 sale-order-list-inner-row-template">
									<a class="sale-order-list-cancel-payment">
										<i class="fa fa-long-arrow-left"></i> <?=Loc::getMessage('SPOL_CANCEL_PAYMENT')?>
									</a>
								</div>
							</div>
							<?
						}
						if (!empty($order['SHIPMENT']))
						{
							?>
							<div class="sale-order-list-inner-title-line">
								<span class="sale-order-list-inner-title-line-item"><?=Loc::getMessage('SPOL_TPL_DELIVERY')?></span>
								<span class="sale-order-list-inner-title-line-border"></span>
							</div>
							<?
						}
						$showDelimeter = false;
						foreach ($order['SHIPMENT'] as $shipment)
						{
							if (empty($shipment))
							{
								continue;
							}
							?>
							<div class="row sale-order-list-inner-row">
								<?
									if ($showDelimeter)
									{
										?>
										<div class="sale-order-list-top-border"></div>
										<?
									}
									else
									{
										$showDelimeter = true;
									}
								?>
								<div class="col-md-9 col-sm-8 col-xs-12 sale-order-list-shipment">
									<div class="sale-order-list-shipment-title">
									<span class="sale-order-list-shipment-element">
										<?=Loc::getMessage('SPOL_TPL_LOAD')?>
										<?
										$shipmentSubTitle = Loc::getMessage('SPOL_TPL_NUMBER_SIGN').htmlspecialcharsbx($shipment['ACCOUNT_NUMBER']);
										if ($shipment['DATE_DEDUCTED'])
										{
											$shipmentSubTitle .= " ".Loc::getMessage('SPOL_TPL_FROM_DATE')." ".$shipment['DATE_DEDUCTED_FORMATED'];
										}

										if ($shipment['FORMATED_DELIVERY_PRICE'])
										{
											$shipmentSubTitle .= ", ".Loc::getMessage('SPOL_TPL_DELIVERY_COST')." ".$shipment['FORMATED_DELIVERY_PRICE'];
										}
										echo $shipmentSubTitle;
										?>
									</span>
										<?
										if ($shipment['DEDUCTED'] == 'Y')
										{
											?>
											<span class="sale-order-list-status-success"><?=Loc::getMessage('SPOL_TPL_LOADED');?></span>
											<?
										}
										else
										{
											?>
											<span class="sale-order-list-status-alert"><?=Loc::getMessage('SPOL_TPL_NOTLOADED');?></span>
											<?
										}
										?>
									</div>

									<div class="sale-order-list-shipment-status">
										<span class="sale-order-list-shipment-status-item"><?=Loc::getMessage('SPOL_ORDER_SHIPMENT_STATUS');?>:</span>
										<span class="sale-order-list-shipment-status-block"><?=htmlspecialcharsbx($shipment['DELIVERY_STATUS_NAME'])?></span>
									</div>

									<?
									if (!empty($shipment['DELIVERY_ID']))
									{
										?>
										<div class="sale-order-list-shipment-item">
											<?=Loc::getMessage('SPOL_TPL_DELIVERY_SERVICE')?>:
											<?=$arResult['INFO']['DELIVERY'][$shipment['DELIVERY_ID']]['NAME']?>
										</div>
										<?
									}

									if (!empty($shipment['TRACKING_NUMBER']))
									{
										?>
										<div class="sale-order-list-shipment-item">
											<span class="sale-order-list-shipment-id-name"><?=Loc::getMessage('SPOL_TPL_POSTID')?>:</span>
											<span class="sale-order-list-shipment-id"><?=htmlspecialcharsbx($shipment['TRACKING_NUMBER'])?></span>
											<span class="sale-order-list-shipment-id-icon"></span>
										</div>
										<?
									}
									?>
								</div>
								<?
								if ($shipment['TRACKING_URL'] <> '')
								{
									?>
									<div class="col-md-2 col-md-offset-1 col-sm-12 sale-order-list-shipment-button-container">
										<a class="sale-order-list-shipment-button" target="_blank" href="<?=$shipment['TRACKING_URL']?>">
											<?=Loc::getMessage('SPOL_TPL_CHECK_POSTID')?>
										</a>
									</div>
									<?
								}
								?>
							</div>
							<?
						}
						?>
						<div class="row sale-order-list-inner-row">
							<div class="sale-order-list-top-border"></div>
							<div class="col-md-<?=($order['ORDER']['CAN_CANCEL'] !== 'N') ? 8 : 10?>  col-sm-12 sale-order-list-about-container">
								<a class="sale-order-list-about-link" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"])?>"><?=Loc::getMessage('SPOL_TPL_MORE_ON_ORDER')?></a>
							</div>
							<div class="col-md-2 col-sm-12 sale-order-list-repeat-container">
								<a class="sale-order-list-repeat-link" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"])?>"><?=Loc::getMessage('SPOL_TPL_REPEAT_ORDER')?></a>
							</div>
							<?
							if ($order['ORDER']['CAN_CANCEL'] !== 'N')
							{
								?>
								<div class="col-md-2 col-sm-12 sale-order-list-cancel-container">
									<a class="sale-order-list-cancel-link" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_CANCEL"])?>"><?=Loc::getMessage('SPOL_TPL_CANCEL_ORDER')?></a>
								</div>
								<?
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<?
		}
	}
	else
	{
		$orderHeaderStatus = null;

		if ($filterShowCanceled === 'Y' && !empty($arResult['ORDERS']))
		{
			?>
			<h1 class="sale-order-title">
				<?= Loc::getMessage('SPOL_TPL_ORDERS_CANCELED_HEADER') ?>
			</h1>
			<?
		}

		foreach ($arResult['ORDERS'] as $key => $order)
		{
			if ($orderHeaderStatus !== $order['ORDER']['STATUS_ID'] && $filterShowCanceled !== 'Y')
			{
				$orderHeaderStatus = $order['ORDER']['STATUS_ID'];
				?>
				<h1 class="sale-order-title">
					<?= Loc::getMessage('SPOL_TPL_ORDER_IN_STATUSES') ?> &laquo;<?=htmlspecialcharsbx($arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME'])?>&raquo;
				</h1>
				<?
			}
			?>
			<div class="col-md-12 col-sm-12 sale-order-list-container">
				<div class="row">
					<div class="col-md-12 sale-order-list-inner-accomplished">
						<div class="row sale-order-list-inner-row">
							<div class="sale-order-list-about-accomplished">
								<div class="sale-b2b-container">
									<div class="sale-b2b-order-info">
										<div class="sale-b2b-info-text-contain">
											<?
											$orderNameID = Loc::getMessage('SPOL_ORDER_ID', array(
												'#ORDER_ID#' => $order['ORDER']['ID']
											));
											?>
											<div class="product-info-b2b-order-delivery">
												<span class="text-product-b2b"><?=$order['ORDER']['DATE_INSERT_FORMATED']?></span>
												<span class="text-product-user-b2b"><?=$orderNameID?></span>
											</div>
											<div class="product-info-b2b-order-delivery">
												<span class="text-product-b2b"><?=Loc::getMessage('SPOL_LIST_CURRENT_STATUS_DATE')?></span>
												<span class="text-product-price-b2b"><?=$arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME']?></span>
											</div>
										</div>
										<?
											$resDelivery = \AmikomB2B\DataB2BUser::getDeliveryOrder($order['SHIPMENT'][0],$order['ORDER']['ID']);
										?>
										<div class="address-delivery">
											<span class="text-product-user-b2b"><?=$resDelivery['NAME'].':'?></span>
											<span class="text-delivery-b2b"><?=$resDelivery['ADDRESS']?></span>
										</div>
									</div>
									<div class="sale-b2b-info">
										<div class="product-info-b2b-order-user">
											<span class="text-product-b2b"><?=Loc::getMessage('SPOL_SELLER')?></span>
											<span class="text-product-user-b2b"><?=$companyInfo?></span>
											<div class="sale-b2b-btn-order-container">
												<button class="sale-b2b-btn-order-active sale-b2b-btn-order sale-order-list-about-link" data-item-order="<?=$order['ORDER']['ID']?>">
													<?=Loc::getMessage('SPOL_TPL_MORE_ON_ORDER')?>
												</button>
												<button class="sale-b2b-btn-order-hide sale-b2b-btn-order sale-order-list-about-link" data-item-order="<?=$order['ORDER']['ID']?>" type-hide="true">
													<?=Loc::getMessage('SPOL_TPL_HIDE_ON_ORDER')?>
												</button>
											</div>
										</div>
										<div class="product-info-b2b-order">
											<?
											$valCnt = count($order['BASKET_ITEMS']);
											$countOrder = mb_substr(count($order['BASKET_ITEMS']), -1);
											$valFormat = '';
											if ($countOrder == '1')
											{
												$valFormat = Loc::getMessage('SPOL_TPL_GOOD');
											}
											elseif ($countOrder >= '2' || $count <= '4')
											{
												$valFormat = Loc::getMessage('SPOL_TPL_TWO_GOODS');
											}
											else
											{
												$valFormat = Loc::getMessage('SPOL_TPL_GOODS');
											}
											?>
											<?
											$priceNotDelivery = \AmikomB2B\DataB2BUser::getDeliveryPriceBasket($order['BASKET_ITEMS']);
											
											?>
											<span class="text-product-b2b"><?=$valCnt.' '.$valFormat.' '.Loc::getMessage('SPOL_TPL_SUMOF')?></span>
											<span class="text-product-price-b2b"><?=$priceNotDelivery?></span>
											<a class="sale-b2b-btn-order sale-order-list-repeat-link sale-order-link-accomplished" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"])?>">
												<?=Loc::getMessage('SPOL_TPL_REPEAT_ORDER')?>
											</a>
										</div>
									</div>
								</div>
								<div class="order-b2b-detail-hide order-b2b-detail-container" data-item-order="<?=$order['ORDER']['ID']?>">
									<?$APPLICATION->IncludeComponent(
										"bitrix:sale.personal.order.detail",
										"main-b2b",
										[
											"PATH_TO_LIST" => "/b2b/orders-history/?filter_history=Y",
											"PATH_TO_CANCEL" => "/b2b/orders-history/cancel/#ID#?",
											"PATH_TO_COPY" => "/b2b/orders-history/orders/?COPY_ORDER=Y&ID=#ID#",
											"PATH_TO_PAYMENT" => "/order/payment/",
											"SET_TITLE" => "N",
											"ID" => $order['ORDER']['ID'],
											"ACTIVE_DATE_FORMAT" => "d.m.Y",
											"ALLOW_INNER" => "N",
											"ONLY_INNER_FULL" => "N",
											"CACHE_TYPE" => "A",
											"CACHE_TIME" => 3600,
											"CACHE_GROUPS" => "Y",
											"RESTRICT_CHANGE_PAYSYSTEM" => array(0 => 0),
											"DISALLOW_CANCEL" => "N",
											"REFRESH_PRICES" => "N",
											"HIDE_USER_INFO" => array(0 => 0),
											"CUSTOM_SELECT_PROPS" => array(),
											"PROP_1" => [],
											"PROP_2" => [],
											"PICTURE_WIDTH" => 110,
											"PICTURE_HEIGHT" => 110,
											"PICTURE_RESAMPLE_TYPE" => 1,
											"AUTH_FORM_IN_TEMPLATE" => "",
											"GUEST_MODE" => "N",
										],
										$component
									);?>
								</div>
							</div>
							<div class="col-md-3 col-md-offset-6 col-sm-12 sale-order-list-repeat-accomplished">
								
							</div>
						</div>
					</div>
				</div>
			</div>
			<?
		}
	}
	?>
	<div class="clearfix"></div>
	<?
	echo $arResult["NAV_STRING"];

	if ($filterHistory !== 'Y')
	{
		$javascriptParams = array(
			"url" => CUtil::JSEscape($this->__component->GetPath().'/ajax.php'),
			"templateFolder" => CUtil::JSEscape($templateFolder),
			"templateName" => $this->__component->GetTemplateName(),
			"paymentList" => $paymentChangeData,
			"returnUrl" => CUtil::JSEscape($arResult["RETURN_URL"]),
		);
		$javascriptParams = CUtil::PhpToJSObject($javascriptParams);
		?>
		<script>
			BX.Sale.PersonalOrderComponent.PersonalOrderList.init(<?=$javascriptParams?>);
		</script>
		<?
	}
}
