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
document.addEventListener("DOMContentLoaded", function () {
    const swiper2 = new Swiper(".brands-swiper", {
        loop: true,
        slidesPerView: 3, // Показывать 4 слайда одновременно
        slidesPerGroup: 3, // Группировать по 4 слайда
		autoplay: {
			delay: 5000, // Автопрокрутка каждые 5 секунд
			disableOnInteraction: false, // Автопрокрутка не останавливается при взаимодействии
		  },
        navigation: {
            nextEl: ".swiper-button-brand-about-next",
            prevEl: ".swiper-button-brand-about-prev",
        },
        pagination: {
            el: ".swiper-pagination-brand-about",
            clickable: true,
        },
		breakpoints: {
			320:{
				slidesPerView: 1,
				slidesPerGroup: 1,
			},
			780:{
				slidesPerView: 2,
				slidesPerGroup: 2,
			},
			1150:{
				slidesPerView: 3,
				slidesPerGroup: 3,
			},
		}
    });
});
	
	


