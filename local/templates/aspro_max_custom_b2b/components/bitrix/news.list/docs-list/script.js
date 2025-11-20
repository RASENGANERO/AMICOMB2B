function setResultData(data){
    for(let i=0;i<data.length;i++){
        let div = document.createElement('div');
        div.setAttribute('class','fd-result-element item-width-261 box-shadow bordered colored_theme_hover_bg-block item-wrap col-xs-12');

        let div1 = document.createElement('div');
        div1.setAttribute('class','docs-props');

        let sp = document.createElement('span');
        sp.textContent = data[i]['NAME'];
        sp.setAttribute('class','docs-title-filter');

        
        let a1 = document.createElement('a');
        a1.setAttribute('class','docs-props-url');
        a1.setAttribute('href',data[i]['BRAND_URL']);
        a1.textContent = data[i]['BRAND_NAME'];
        
        let a2 = document.createElement('a');
        a2.setAttribute('class','docs-props-url');
        a2.setAttribute('target','_blank');
        a2.setAttribute('href',data[i]['DOC_PATH']);
        a2.textContent = 'Загрузить';


        div1.appendChild(a1);
        div1.appendChild(a2);

        div.appendChild(sp);
        div.appendChild(div1);

        document.getElementsByClassName('filter-docses-result')[0].appendChild(div);
    }
}

function getFiltersResult(event) {
    let val = String(event.target.value).trim(); // Удаляем пробелы по краям
    const resultContainer = document.getElementsByClassName('filter-docses-result')[0];

    if (val === '') {
        resultContainer.classList.add('filter-docses-result-not');
        resultContainer.innerHTML = '';
    } else {
        // Если значение не пустое
        let dt = {
            'query': val
        };
        
        $.ajax({
            url: '/local/templates/aspro_max_custom/components/bitrix/news.list/docs-list/docsfilterHandler.php',
            type: 'POST',
            data: dt,
            dataType: 'json',
            async: true,
            success: function(response) {
                resultContainer.classList.remove('filter-docses-result-not');
                resultContainer.innerHTML = '';
                setResultData(response['values']);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Ошибка:', textStatus, errorThrown);
            }
        });
    }
}
document.addEventListener("DOMContentLoaded", ()=>{
    if(document.getElementsByClassName("filter-docses").length!=0){
        let checkInput = document.getElementsByClassName("filter-docses")[0];
        checkInput.addEventListener('input',getFiltersResult);
    }
});