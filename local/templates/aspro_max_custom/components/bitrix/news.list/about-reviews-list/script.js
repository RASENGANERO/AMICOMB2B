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
	const swiper = new Swiper(".rev-swiper", {
	  loop: true, // Зацикливание слайдов
	  autoplay: {
		delay: 5000, // Автопрокрутка каждые 5 секунд
		disableOnInteraction: false, // Автопрокрутка не останавливается при взаимодействии
	  },
	  navigation: {
		nextEl: ".swiper-button-rev-about-next",
		prevEl: ".swiper-button-rev-about-prev",
	  },
	  pagination: {
		el: ".swiper-pagination-rev-about",
		clickable: true,
	  },
	  effect: "fade", // Плавное появление нового слайда
	  fadeEffect: {
		crossFade: true,
	  },
	});
  });
