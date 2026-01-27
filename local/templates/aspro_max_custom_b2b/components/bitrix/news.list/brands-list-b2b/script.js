$(document).ready(function(){
	var containerEl = document.querySelector('.mixitup-container');
	if(containerEl)
	{
		var config = {
			selectors:{
				target: '[data-ref="mixitup-target"]'
			},
			animation:{
				effects: 'fade scale stagger(50ms)' // Set a 'stagger' effect for the loading animation
			},
			load:{
				filter: 'none' // Ensure all targets start from hidden (i.e. display: none;)
			},
			animation:{
				duration: 350
			},
			controls:{
				scope: 'local'
			},
			callbacks: {
				onMixStart:function(state) {
				},
				onMixEnd:function() {
					InitLazyLoad();
				}
			}
		};
		var mixer = mixitup(containerEl, config);

		// Add a class to the container to remove 'visibility: hidden;' from targets. This
	    // prevents any flickr of content before the page's JavaScript has loaded.

	    containerEl.classList.add('mixitup-ready');

	    // Show all targets in the container

	    mixer.show()
		.then(function(){
			// Remove the stagger effect for any subsequent operations
			mixer.configure({
				animation: {
					effects: 'fade scale'
				}
			});
		});
	}

});
function checkActiveBrands() {
	let cnt = 0;
	let brandItems = document.getElementsByClassName('item-wrapper-b2b');
	for (let i = 0; i < brandItems.length; i++) {
		let sClass = String(brandItems[i].getAttribute('class')).trim().split(' ')[0].trim();
		if (sClass == 'element-brand-active') {
			cnt+=1;
		}	
	}
	return cnt;
}
function showDiscountsBrand() {
	let checkBrands = checkActiveBrands();
	let brandItems = document.getElementsByClassName('item-wrapper-b2b');
	let showParam = Number(document.getElementById('show-all-discount-brand').getAttribute('show-brand-cnt'));
	if (checkBrands == showParam) {
		for (let i = 0; i < brandItems.length; i++) {
			let sClass = String(brandItems[i].getAttribute('class')).trim().split(' ');
			if (String(sClass[0]).trim() == 'element-brand-hide') {
				sClass[0] = 'element-brand-active';
			}
			sClass = sClass.join(' ').trim();
			brandItems[i].setAttribute('class',sClass);
		}
		document.getElementById('show-all-discount-brand').textContent = 'Скрыть скидки';
	}
	else {
		for (let i = 0; i < brandItems.length; i++) {
			let sClass = String(brandItems[i].getAttribute('class')).trim().split(' ');
			if (i > 3) {
				if (String(sClass[0]).trim() == 'element-brand-active') {
					sClass[0] = 'element-brand-hide';
				}	
			}
			sClass = sClass.join(' ').trim();
			brandItems[i].setAttribute('class',sClass);
			document.getElementById('show-all-discount-brand').textContent = 'Все скидки';
		}
	}	
}
document.addEventListener('DOMContentLoaded',()=>{
	if (document.getElementById('show-all-discount-brand') != null) {
		document.getElementById('show-all-discount-brand').addEventListener('click',showDiscountsBrand);
	}
});