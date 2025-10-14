<?
require_once($_SERVER['DOCUMENT_ROOT'].'/local/lib/AmikomnewBrands/BrandFileDownloader.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_GET['brand'])) {
        $brandDownload = new \AmikomnewBrands\BrandFileDownloader($_GET['brand']);
        $brandDownload->downloadFile();
    }
    else { 
        LocalRedirect('/404.php');
    }
}
else {
    
    LocalRedirect('/404.php');
}
?>