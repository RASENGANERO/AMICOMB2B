<?
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dataTypeCookie = $_POST['typecook'];
    if ($dataTypeCookie==='usecook') {
        setcookie('COOKIE_APPLY','Y', time() + 60*60*24*31, '/',$_SERVER['SERVER_NAME']);
    }
    if ($dataTypeCookie==='seminar') {
        setcookie('SEMINAR_APPLY','Y', time() + 60*60*1, '/',$_SERVER['SERVER_NAME']);    
    }
    echo json_encode('CookieOK',JSON_UNESCAPED_UNICODE);
}
?>