BX.namespace('BX.Sale.PersonalOrderComponent');

(function() {
	BX.Sale.PersonalOrderComponent.PersonalOrderList = {
		init : function(params)
		{
			var rowWrapper = document.getElementsByClassName('sale-order-list-inner-row');

			params.paymentList = params.paymentList || {};
			params.url = params.url || "";
			params.templateName = params.templateName || "";
			params.returnUrl = params.returnUrl || "";

			Array.prototype.forEach.call(rowWrapper, function(wrapper)
			{
				var shipmentTrackingId = wrapper.getElementsByClassName('sale-order-list-shipment-id');
				if (shipmentTrackingId[0])
				{
					Array.prototype.forEach.call(shipmentTrackingId, function(blockId)
					{
						var clipboard = blockId.parentNode.getElementsByClassName('sale-order-list-shipment-id-icon')[0];
						if (clipboard)
						{
							BX.clipboard.bindCopyClick(clipboard, {text : blockId.innerHTML});
						}
					});
				}

				BX.bindDelegate(wrapper, 'click', { 'class': 'ajax_reload' }, BX.proxy(function(event)
				{
					var block = wrapper.getElementsByClassName('sale-order-list-inner-row-body')[0];
					var template = wrapper.getElementsByClassName('sale-order-list-inner-row-template')[0];
					var cancelPaymentLink = template.getElementsByClassName('sale-order-list-cancel-payment')[0];

					BX.ajax(
						{
							method: 'POST',
							dataType: 'html',
							url: event.target.href,
							data:
							{
								sessid: BX.bitrix_sessid(),
								RETURN_URL: params.returnUrl
							},
							onsuccess: BX.proxy(function(result)
							{
								var resultDiv = document.createElement('div');
								resultDiv.innerHTML = result;
								template.insertBefore(resultDiv, cancelPaymentLink);
								block.style.display = 'none';
								template.style.display = 'block';

								BX.bind(cancelPaymentLink, 'click', function()
								{
									block.style.display = 'block';
									template.style.display = 'none';
									resultDiv.remove();
								},this);

							},this),
							onfailure: BX.proxy(function()
							{
								return this;
							}, this)
						}, this
					);
					event.preventDefault();
				}, this));

				BX.bindDelegate(wrapper, 'click', { 'class': 'sale-order-list-change-payment' }, BX.proxy(function(event)
				{
					event.preventDefault();

					var block = wrapper.getElementsByClassName('sale-order-list-inner-row-body')[0];
					var template = wrapper.getElementsByClassName('sale-order-list-inner-row-template')[0];
					var cancelPaymentLink = template.getElementsByClassName('sale-order-list-cancel-payment')[0];

					BX.ajax(
						{
							method: 'POST',
							dataType: 'html',
							url: params.url,
							data:
							{
								sessid: BX.bitrix_sessid(),
								orderData: params.paymentList[event.target.id],
								templateName : params.templateName
							},
							onsuccess: BX.proxy(function(result)
							{
								var resultDiv = document.createElement('div');
								resultDiv.innerHTML = result;
								template.insertBefore(resultDiv, cancelPaymentLink);
								event.target.style.display = 'none';
								block.parentNode.removeChild(block);
								template.style.display = 'block';
								BX.bind(cancelPaymentLink, 'click', function()
								{
									window.location.reload();
								},this);

							},this),
							onfailure: BX.proxy(function()
							{
								return this;
							}, this)
						}, this
					);

				}, this));
			});
		}
	};
})();
function showOrder(event) {
	let dt = event.target;
	let idOrder = dt.getAttribute('data-item-order');
	let btnOrder = document.getElementsByClassName('sale-b2b-btn-order');
	let btnOrd = [];
	for (let i=0;i<btnOrder.length;i++) {
		if (btnOrder[i].getAttribute('data-item-order') == idOrder) {
			btnOrd.push(btnOrder[i]);
		}
	}
	for (let i=0;i<btnOrd.length;i++) {
		if (btnOrd[i] == event.target) {
			btnOrd[i].setAttribute('class','sale-b2b-btn-order-hide sale-b2b-btn-order sale-order-list-about-link');
		}
		else {
			btnOrd[i].setAttribute('class','sale-b2b-btn-order-active sale-b2b-btn-order sale-order-list-about-link');
		}
	}
	let sd = document.getElementsByClassName('order-b2b-detail-container');
	for (let i = 0; i < sd.length; i++) {
		if (sd[i].getAttribute('data-item-order') == idOrder) {
			let sCls = sd[i].getAttribute('class');
			if (sCls == 'order-b2b-detail-active order-b2b-detail-container') {
				sd[i].setAttribute('class','order-b2b-detail-hide order-b2b-detail-container');
			}
			else {
				sd[i].setAttribute('class','order-b2b-detail-active order-b2b-detail-container');
			}
		}
	}
}
document.addEventListener('DOMContentLoaded',()=>{
	if (document.getElementsByClassName('sale-b2b-btn-order').length!=0) {
		let s = document.getElementsByClassName('sale-b2b-btn-order');
		for (let i = 0;i<s.length;i++){
			s[i].addEventListener('click',showOrder);
		}
	}
});