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
	
})
readyDOM(function () {
    checkLinkedArticles();
});
function removeElement (event) {
	let sbtn = event.currentTarget;
	let elementBtnID = sbtn.getAttribute('element');
	let elemsID = document.getElementsByClassName('rem-block');
	for (let i = 0; i < elemsID.length; i++) {
		if (elemsID[i].getAttribute('element') == elementBtnID) {
			elemsID[i].remove();
		}
	}
}
document.addEventListener('DOMContentLoaded',()=>{
	let elemRemoveBtn = document.getElementsByClassName('remove-element__close');
	if (elemRemoveBtn.length!=0) {
		for (let i=0;i < elemRemoveBtn.length; i++) {
			elemRemoveBtn[i].addEventListener('click',removeElement);
		}
	}
});