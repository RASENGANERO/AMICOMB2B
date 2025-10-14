<?php
namespace AmikomnewBrands;
require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
error_reporting(E_ERROR | E_PARSE);
class BrandFileDownloader {
    public $brandCode;
    public $iblockId = 33;
    public function __construct($brandCode) {
        $this->brandCode = trim(htmlspecialchars(mb_strtolower(strip_tags($brandCode))));
    }
    public function getFilePath() {
        if ($this->brandCode) {
            $dbItems = \CIBlockElement::GetList(
                [],
                [
                    'IBLOCK_ID' => $this->iblockId,
                    'CODE' => $this->brandCode,
                ],
                false,
                false,
                [
                    'PROPERTY_BRAND_PRICE'
                ]
            );
            if ($arItem = $dbItems->Fetch()) {
                if ($fileId = $arItem['PROPERTY_BRAND_PRICE_VALUE']) {
                    return $fileId; // Возвращаем ID файла вместо пути
                }
            }
        }
        return null;
    }
    public function generateFileName($arFile) {
        $ext = pathinfo($arFile['SRC'], PATHINFO_EXTENSION);
        $fileDate = isset($arFile['TIMESTAMP_X']) ? (new \DateTime($arFile['TIMESTAMP_X']))->format('d-m-Y') : date('d-m-Y');
        $fileName = 'ami-com-price-' . $this->brandCode . '-' . $fileDate;
        return $fileName . '.'.$ext;
        
    }
    public function downloadFile() {
        $fileId = $this->getFilePath();
        if ($fileId) {
            $arFile = \CFile::GetFileArray($fileId);
            if ($arFile) {
                $filePath = $_SERVER["DOCUMENT_ROOT"] . \CFile::GetPath($fileId);
                $fileName = $this->generateFileName($arFile);
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filePath));
                readfile($filePath);
                exit;
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'File not found']);
        }
    }
}
?>