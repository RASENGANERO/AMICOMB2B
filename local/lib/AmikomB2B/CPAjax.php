<?
require_once('CPBasketExcel.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $downloader = new \AmikomB2B\CPBasketExcel($_REQUEST['userID']);
    $downloader->genDataXlsx();
    $downloader->downloadFile();
}
?>