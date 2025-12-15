function sendCookie() {
    let data = {
        typecook:'usecook'
    };
    $.ajax({
        url: '/local/lib/Amikomnew/CookieSet.php',
        type: 'POST',
        dataType: 'json',
        data: data,
        success: function(data) {
            removeCookie();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Ошибка:', textStatus, errorThrown);
        },
    });
}
function removeCookie() {
    document.getElementsByClassName('cookie-apply')[0].remove();
} 
document.addEventListener('DOMContentLoaded', ()=>{
    if (document.getElementsByClassName('cookie-apply').length!=0) {
        let closeCookie = document.getElementById('close-cookie');
        closeCookie.addEventListener('click',removeCookie);
        let sendCookieBtn = document.getElementById('success-cookie');
        sendCookieBtn.addEventListener('click',sendCookie);
    }
});