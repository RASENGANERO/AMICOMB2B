function getIdElements(){
    let elems = document.getElementsByClassName('news-brands-item');
    let idsArr = [];
    for(let i=0;i<elems.length;i++) {
        idsArr.push(elems[i].getAttribute('data-id-element'));
    }
    return idsArr;
}
function addElements(arras){
    let divMain = document.getElementsByClassName('news-brands-container-items')[1];
    for(let i=0;i<arras.length;i++){
        let divNews = document.createElement('div');
        divNews.setAttribute('class','news-brands-item item-wrapper col-lg-20 col-xs-6 col-xxs-12 clearfix');
        divNews.setAttribute('data-ref','mixitup-target');
        divNews.setAttribute('data-id-element',arras[i].ID);
        



        const itemDiv = document.createElement('div');
        itemDiv.className = 'item bg-white clearfix';

        // Создаем блок с изображением
        const imageDiv = document.createElement('div');
        imageDiv.className = 'image shine';

        const linkImage = document.createElement('a');
        linkImage.href = arras[i].CODE;

        const spanImage = document.createElement('span');
        spanImage.className = 'rounded3 bg-fon-img set-position top left lazyloaded';
        spanImage.setAttribute('data-lazyload', '');
        spanImage.setAttribute('data-src', arras[i].PREVIEW_PICTURE);
        spanImage.setAttribute('data-bg', arras[i].PREVIEW_PICTURE);
        spanImage.style.backgroundImage = 'url("'+arras[i].PREVIEW_PICTURE+'")';

        // Собираем элементы изображения
        linkImage.appendChild(spanImage);
        imageDiv.appendChild(linkImage);
        itemDiv.appendChild(imageDiv);

        // Создаем блок с текстом
        let innerTextDiv = document.createElement('div');
        innerTextDiv.className = 'inner-text with-section with-date';

        let sectionDiv = document.createElement('div');
        sectionDiv.className = 'section muted font_upper';
        sectionDiv.textContent = arras[i].IBLOCK_SECTION_ID;

        let periodBlockDiv = document.createElement('div');
        periodBlockDiv.className = 'period-block font_xs';

        let dateSpan = document.createElement('span');
        dateSpan.className = 'date';
        dateSpan.textContent =  arras[i].DATE_ACTIVE_FROM;

        // Собираем элементы блока с текстом
        periodBlockDiv.appendChild(dateSpan);
        innerTextDiv.appendChild(sectionDiv);
        innerTextDiv.appendChild(periodBlockDiv);

        let titleDiv = document.createElement('div');
        titleDiv.className = 'title';

        let linkTitle = document.createElement('a');
        linkTitle.href = arras[i].CODE;
        linkTitle.textContent = arras[i].NAME;

        // Собираем элементы заголовка
        titleDiv.appendChild(linkTitle);
        innerTextDiv.appendChild(titleDiv);
        itemDiv.appendChild(innerTextDiv);

        // Добавляем созданный элемент в документ (например, в body)
        divNews.appendChild(itemDiv);

        divMain.appendChild(divNews);
    }
}
function removeLoadings(){
    let count = Number(document.getElementsByClassName('news-brands-item').length);
    let countRem = Number(document.getElementsByClassName('news-brands-container-items')[0].getAttribute('data-brand-count'));
    if (count == countRem){
        document.getElementsByClassName('load_more_brands')[0].remove();
    }
    let loadings = document.getElementsByClassName('bottom_nav');
    if(loadings.length!=0){
        for(let i=0;i<loadings.length;i++){
            if (loadings[i].hasAttribute('class')) {
                loadings[i].classList.remove('loadings');
            }
        }
    }
}
function show_more_brands_news(){
    let brandValue = document.getElementsByClassName('news-brands-container-items')[0].getAttribute('data-brand-id');
    let elemsIdsValue = getIdElements();
    let dt = {
        'elements': elemsIdsValue,
        'brand': brandValue
    };
    $.ajax({
        url: '/local/templates/aspro_max_custom/components/bitrix/system.pagenavigation/brands-news-detail/bnpagHandler.php',
        type: 'POST',
        data: dt,
        dataType: 'json',
        async: true,
        success: function(response) {
            addElements(response['result']);
            removeLoadings();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Ошибка:', textStatus, errorThrown);
        }
    });
}
document.addEventListener('DOMContentLoaded',()=>{
    if(document.getElementsByClassName('load_more_brands').length!=0){
        let loadMore = document.getElementsByClassName('load_more_brands')[0];
        loadMore.addEventListener('click',show_more_brands_news); 
    }
});