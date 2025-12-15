function getFile(brandCode){
    $.ajax({
        url: '/local/lib/AmikomnewBrands/BrandAjax.php',
        method: 'POST',
        async: true,
        data: { brand_code: brandCode },
        xhrFields: {
            responseType: 'blob'
        },
        success: function(blob, status, xhr) {
            let filename = '';
            let disposition = xhr.getResponseHeader('Content-Disposition');

            const regex = /filename="([^"]+)"/;
            const match = disposition.match(regex);
            filename = match[1];
            
            let url = window.URL.createObjectURL(blob);
            let a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            setTimeout(function() {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            }, 100);
        },
        error: function(e) {
           // console.log(e);
            alert('Произошла ошибка при загрузке файла.');
        }
    });
}
function getBrandOne(){
    let brandCode = document.getElementById('downloadprice').getAttribute('data-element');
    getFile(brandCode);
}
function getBrandMany(event){
    let brandCode = event.currentTarget.getAttribute('data-element');
    getFile(brandCode);
}
document.addEventListener('DOMContentLoaded',()=>{
    if (document.getElementById('downloadprice')!=null){
        document.getElementById('downloadprice').addEventListener('click',getBrandOne);
    }
    if (document.getElementsByClassName('price-elem').length!=0){
        let prices = document.getElementsByClassName('price-elem');
        for(let i=0; i<prices.length;i++){
            prices[i].addEventListener('click',getBrandMany);
        }

    }
});