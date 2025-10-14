<?
require_once('BrandFileDownloader.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $downloader = new \AmikomnewBrands\BrandFileDownloader($_REQUEST['brand_code']);
    $downloader->downloadFile();
}
?>