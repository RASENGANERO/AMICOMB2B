$(document).ready(function(){

	let arSectionID = '';
	let arBrandCode = '';
	/*if(document.getElementsByClassName('slide-block').length!=0){
		document.getElementsByClassName('slide-block')[0].remove();
	}
	if(document.getElementsByClassName('bottom-links-block').length!=0){
		document.getElementsByClassName('bottom-links-block')[0].remove();
	}*/
					
	$(document).on('click', 'div.section-detail-list__info', function(e){
		var _this = $(this);
		e.preventDefault();
		var arSectionsIDs = [];
		if(!$(_this).hasClass('all-sections')){

			let s = document.getElementsByClassName("section-detail-list__info bordered box-shadow-sm rounded3");
			for (let i = 0; i < s.length; i++) {
				s[i].setAttribute('class','section-detail-list__info bordered box-shadow-sm rounded3');
				s[i].setAttribute('data-section_reset','false');
			}

			_this.attr('class', 'section-detail-list__info bordered box-shadow-sm rounded3 section-detail-list__item--active colored_theme_bg colored_theme_bg_hovered_hover');
			$('.section-detail-list__info.all-sections').removeClass('section-detail-list__item--active colored_theme_bg');
			
			$('.section-detail-list__info.section-detail-list__item--active').each(function() {
				var secId = $(this).attr('data-section_id');
				if(secId){
					arSectionsIDs.push(secId);
					arSectionID = secId;
					arBrandCode = $(this).attr('data-brand-code');
				}
			});
			if(!arSectionsIDs.length){
				$('.section-detail-list__info.all-sections').addClass('section-detail-list__item--active colored_theme_bg');
			}
		}else{
			$('.section-detail-list__info.section-detail-list__item--active').each(function() {
				$(this).removeClass('section-detail-list__item--active colored_theme_bg colored_theme_bg_hovered_hover');
				_this.addClass('section-detail-list__item--active colored_theme_bg');
			});
		}

		document.getElementById('show-products-brand').setAttribute('class','brand-product-url-not');
		let dt = {
			'section_id':_this.attr('data-section_id'),
			'brand_code':_this.attr('data-brand-code'),
		};
		$.ajax({
			url:'/local/templates/aspro_max_custom/components/aspro/catalog.section.list.max/sections-tags-brands/getnavchain.php',
			type: 'POST',
			data: dt,
			dataType: 'json',
			async: true,
			success: function(response) {
				document.getElementById('show-products-brand').setAttribute('class','brand-product-url');
				document.getElementById('show-products-brand').setAttribute('href',response['URL']);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.error('Ошибка:', textStatus, errorThrown);
			}
		});

		var strSectionIds = JSON.stringify(arSectionsIDs);
		
		$('.content_linked_goods').attr('data-sections-ids',encodeURIComponent(strSectionIds));
			$.ajax({
				url:window.location.href, //_this.attr('href'),
				type: "GET",
				data: {'ajax_get':'Y', 'ajax_get_sections':'Y', 'ajax_section_id':strSectionIds, 'ajax_section_reset': _this.attr('data-section_reset')},
				success: function(html){

			
					// Создаем временный элемент для работы с HTML
					
					let response = $('<div>').html(html);
					response.find('.amikom-news-list-brands').remove();
					response.find('.slide-block').remove();
					response.find('.bottom-links-block').remove();
					
					
					$('.js-load-block').html(response.html());
					
					//$('.js-load-block').html(html);					
					if(_this.hasClass('section-detail-list__item--active')){
						_this.attr('data-section_reset', 'true');
					} else {
						_this.attr('data-section_reset', 'false');
					}
					var eventdata = {action:'jsLoadBlock'};
					BX.onCustomEvent('onCompleteAction', [eventdata, _this]);
				}
			})
		})
	$('.section-detail-list__item--js-more').on('click', function(){
		var $this = $(this),
			block = $this.find('> span'),
			dataOpened = $this.data('opened'),
			thisText = block.text()
			dataText = block.data('text'),
			item = $this.closest('.section-detail-list').find('.section-detail-list__item-more');
		item.removeClass('hidden');
		if(dataOpened != 'Y'){
			item.velocity('stop').velocity({
				'opacity': 1,
			}, {
				'display': 'inline',
				'duration': 200,
				begin: function(){
					
				}
			});
			$this.addClass('opened').data('opened', 'Y');
		}
		else{
			item.velocity('stop').velocity({
				'opacity': 0,
			}, {
				'display': 'none',
				'duration': 100,
				complete: function(){
				}
			});
			$this.removeClass('opened').data('opened', 'N');
		}
		block.data('text', thisText).text(dataText);
	});
});