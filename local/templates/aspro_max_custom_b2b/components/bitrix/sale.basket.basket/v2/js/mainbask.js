function getCP(){
    let userID = document.getElementById('get-cp').getAttribute('fuser');
    $.ajax({
        url: '/local/lib/AmikomB2B/CPAjax.php',
        method: 'POST',
        async: true,
        data: { userID: userID },
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
            alert('Произошла ошибка при загрузке файла.');
        }
    });
}
document.addEventListener('DOMContentLoaded',()=>{
    let cpButton = document.getElementById('get-cp');
    if (cpButton != null) {
        cpButton.addEventListener('click',getCP);
    }
});