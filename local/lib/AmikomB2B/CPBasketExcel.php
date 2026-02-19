<?


namespace AmikomB2B;
require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
error_reporting(E_ERROR | E_PARSE);
require("D:/OPENSERVER/domains/AMIKOMB2BNEW/local/vendor/autoload.php");



use PhpOffice\PhpSpreadsheet\Spreadsheet,
    PhpOffice\PhpSpreadsheet\Writer\Xlsx,
    PhpOffice\PhpSpreadsheet\Worksheet\Worksheet,
    PhpOffice\PhpSpreadsheet\Worksheet\Drawing,
    PhpOffice\PhpSpreadsheet\RichText\RichText,
    PhpOffice\PhpSpreadsheet\Style\Color,
    PhpOffice\PhpSpreadsheet\Style\Border,
    PhpOffice\PhpSpreadsheet\Style\Fill,
    PhpOffice\PhpSpreadsheet\Cell\Hyperlink;

class CPBasketExcel {
    const PATH_NOT_IMAGE = '/local/lib/AmikomB2B/images/noimage_product.png';
    public Spreadsheet $spreadsheet;
    public Worksheet $sheet;
    const LID = 's1';
    public string $FUSER_ID;
    public string $allPrice;
    public int $rowIndexTableStart;
    public array $columnsAr = ['A','B','C','D','E','F'];
    public array $nameColumnsAr = ['Наименование','Фотография','Описание','Цена','Кол-во','Стоимость'];

    function __construct($FUSER_ID)
    {
        $this->allPrice = 0;
        $this->FUSER_ID = $FUSER_ID;
        $this->rowIndexTableStart = 9;
        
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
        $this->spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $this->spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        //$this->sheet->setCellValue('A1', 'Hello World !');
    }
    public function getDataBasket():array {
        $dataBasketAr = [];
        $arOrder = [
            'DATE_INSERT' => 'ASC'
        ];
        $arFilter = [
            'LID' => self::LID, 
            'ORDER_ID' => '', 
            'FUSER_ID' => $this->FUSER_ID
        ];
        $arSelected = [
            'PRODUCT_ID',
            'QUANTITY',
            'PRICE',
            'NAME',
            'ID'

        ];
        $allPrice = 0;
        $rsItems = \CSaleBasket::GetList($arOrder,$arFilter,false, false, $arSelected);
        while($obj = $rsItems->Fetch()) {
            $itemObject = \CIBlockElement::GetByID($obj['PRODUCT_ID'])->Fetch();
            $obj['PICTURE'] = $itemObject['DETAIL_PICTURE'] ?? $itemObject['PREVIEW_PICTURE'];
            $obj['TEXT'] = !empty($itemObject['DETAIL_TEXT']) ? $itemObject['DETAIL_TEXT'] : $itemObject['PREVIEW_TEXT'];
            $obj['PICTURE'] = \CFile::GetByID($obj['PICTURE'])->Fetch()['SRC'] ?? '';
            $obj['PICTURE'] = self::generateImage($obj['PICTURE'],$obj['ID']);
            $obj['PRICE_ALL'] = $obj['PRICE'] * $obj['QUANTITY'];
            $obj['PRICE'] = \AmikomB2B\DiscountPrices::getPrintValueFloat($obj['PRICE']); 
            $obj['URL'] = 'https://www.ami-com.ru/catalog/'.$itemObject['CODE'].'/';
            $allPrice += $obj['PRICE_ALL'];
            $obj['PRICE_ALL'] = \AmikomB2B\DiscountPrices::getPrintValueFloat($obj['PRICE_ALL']);
            $dataBasketAr[] = $obj;
        }

        $this->allPrice = \AmikomB2B\DiscountPrices::getPrintValueFloat($allPrice);
        return $dataBasketAr;
    }

    public static function generateImage($imagePath,$idElCart) {
        if (empty($imagePath)) {
            return self::PATH_NOT_IMAGE;
        }
        else{
            if (str_ends_with(strval($imagePath),'.webp') === true) {
                $img = imageCreatefromWebp('D:/OPENSERVER/domains/AMIKOMB2BNEW'.$imagePath);
                $path = 'D:/OPENSERVER/domains/AMIKOMB2BNEW/local/lib/AmikomB2B/images/images_excel/'.$idElCart. '.png';
                imagePng($img, $path);
                $path = '/local/lib/AmikomB2B/images/images_excel/'.$idElCart. '.png';
                return $path;
            }
            else{
                return $imagePath;
            }
        } 
    }
    public function addLogoHeader() {
        $this->sheet->getColumnDimension('A')->setWidth(210, 'px');
        $drawing = new Drawing();
        $drawing->setName('Sample Image');
        $drawing->setDescription('This is a sample image.');
        $drawing->setPath('D:/OPENSERVER/domains/AMIKOMB2BNEW/local/lib/AmikomB2B/images/logo.png'); // Указываем путь к изображению
        $drawing->setHeight(120); // Задаём высоту изображения (автоматически изменится ширина)
        $drawing->setCoordinates('A1'); // Указываем ячейку для размещения
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(10);
        $drawing->setWorksheet($this->sheet);
    }
    public function addCompanyInfoHeader() {
        $this->sheet->getRowDimension(2)->setRowHeight(42,'px');
        $this->sheet->getRowDimension(3)->setRowHeight(42,'px');
        $this->sheet->getRowDimension(4)->setRowHeight(42,'px');
        $this->sheet->getColumnDimension('B')->setWidth(190, 'px');
        $this->sheet->getColumnDimension('C')->setWidth(270, 'px');

        $arrDataInfo = ['О компании','Реквизиты','107023, Москва, пл. Журавлева, д. 2, стр. 2'];
        $arrDataInfoUrl = ['https://www.ami-com.ru/about/','https://www.ami-com.ru/privacy/','https://www.ami-com.ru/contacts/stores/384/'];
        $arrIndexData = [2,3,4];
        for ($i = 0; $i < count($arrDataInfo); $i++) {
            $richText = new RichText();
            self::generateRichText($richText,$arrDataInfo[$i],'ffec7b15','Arial',12,false,true);
            $this->sheet->setCellValue('B'.$arrIndexData[$i], $richText);
            $this->sheet->getStyle('B'.$arrIndexData[$i])->getAlignment()->setWrapText(true);
            $this->sheet->getStyle('B'.$arrIndexData[$i])->getAlignment()->setVertical('center');
            $this->sheet->getStyle('B'.$arrIndexData[$i])->getAlignment()->setHorizontal('right');
            $this->sheet->mergeCells('B'.$arrIndexData[$i].':'.'D'.$arrIndexData[$i],'hide'); 
            $this->sheet->getCell('B'.$arrIndexData[$i])->setHyperlink(new Hyperlink($arrDataInfoUrl[$i])); 
            
        }
    }
    public static function generateRichText($richObject,$valName, $color, $font,$size,$bold,$underline) {
        $text = $richObject->createTextRun($valName);
        $text->getFont()->setColor(new Color($color));
        $text->getFont()->setName($font);
        $text->getFont()->setSize($size);
        $text->getFont()->setBold($bold);
        $text->getFont()->setUnderline($underline);
    }
    public function addContactsHeader() {
        $this->sheet->getColumnDimension('D')->setWidth(100, 'px');
        $this->sheet->getColumnDimension('E')->setWidth(100, 'px');
        $this->sheet->getColumnDimension('F')->setWidth(100, 'px');
        $arrDataInfo = ['+7 (495) 120 06 86','sale@ami-com.ru','ami-com.ru'];
        $arrDataInfoUrl = ['tel:+74951200686','mailto:sale@ami-com.ru','https://www.ami-com.ru/'];
        $arrIndexData = [2,3,4];
        for ($i = 0; $i < count($arrDataInfo); $i++) {
            $richText = new RichText();
            self::generateRichText($richText,$arrDataInfo[$i],'ffec7b15','Arial',12,false,true);
            $this->sheet->setCellValue('E'.$arrIndexData[$i], $richText);
            $this->sheet->getStyle('E'.$arrIndexData[$i])->getAlignment()->setWrapText(true);
            $this->sheet->getStyle('E'.$arrIndexData[$i])->getAlignment()->setVertical('center');
            $this->sheet->getStyle('E'.$arrIndexData[$i])->getAlignment()->setHorizontal('right');
            $this->sheet->mergeCells('E'.$arrIndexData[$i].':'.'F'.$arrIndexData[$i],'hide'); 
            $this->sheet->getCell('E'.$arrIndexData[$i])->setHyperlink(new Hyperlink($arrDataInfoUrl[$i])); 
            
        }
    }
    
    public function addHeader() {
        self::addLogoHeader();
        self::addCompanyInfoHeader();
        self::addContactsHeader();
    }
    public function addInfoBasket() {
        $this->sheet->getRowDimension(5)->setRowHeight(50,'px');

        $this->sheet->mergeCells('A6:F6','hide');
        $richText = new RichText();
        self::generateRichText($richText,'ТОВАРЫ В КОРЗИНЕ','FF000000','Arial',16,true,false);
        $this->sheet->setCellValue('A6', $richText);
        $this->sheet->getStyle('A6')->getAlignment()->setVertical('center');
        $this->sheet->getStyle('A6')->getAlignment()->setHorizontal('center');
        $this->sheet->getRowDimension(6)->setRowHeight(30,'px');
        
        $this->sheet->mergeCells('A7:F7','hide');
        $richText = new RichText();
        self::generateRichText($richText,'Дата и время выгрузки: '.date('d/m/Y в H:i:s'),'FF000000','Arial',11,false,false);
        $this->sheet->setCellValue('A7', $richText);
        $this->sheet->getStyle('A7')->getAlignment()->setVertical('center');
        $this->sheet->getStyle('A7')->getAlignment()->setHorizontal('center');
        $this->sheet->getRowDimension(7)->setRowHeight(40,'px');
        
        
    }

    public function generateTable() {
        self::generateHeaderTable();
        self::generateMainTable();
        self::generateFooterTable();
    }
    
    public function drawImageTable($path) {
        $drawing = new Drawing();
        $drawing->setName('Sample Image');
        $drawing->setDescription('This is a sample image.');
        $drawing->setPath('D:/OPENSERVER/domains/AMIKOMB2BNEW'.$path);//'D:/OPENSERVER/domains/AMIKOMB2BNEW/local/lib/AmikomB2B/images/logo.png'); // Указываем путь к изображению
        $drawing->setHeight(140); // Задаём высоту изображения (автоматически изменится ширина)
        $drawing->setCoordinates('B'.$this->rowIndexTableStart); // Указываем ячейку для размещения
        $drawing->setOffsetX(40);
        $drawing->setOffsetY(20);
        $drawing->setWidth(120); // Задаём высоту изображения (автоматически изменится ширина)
        $drawing->setWorksheet($this->sheet);
        $this->sheet->getStyle('B'.$this->rowIndexTableStart)->getAlignment()->setVertical('center');
        $this->sheet->getStyle('B'.$this->rowIndexTableStart)->getAlignment()->setHorizontal('center');
        
    }
    public function generateHeaderTable () {
        $this->sheet->getRowDimension(8)->setRowHeight(40,'px');
        for ($i = 0; $i < count($this->columnsAr); $i++) {
            $horizontal = 'center';
            if (($i === 0) || ($i === 2)) {
                $horizontal = 'left';
            } 
            $this->sheet->getStyle($this->columnsAr[$i].'8')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('ff333333'));
            $this->sheet->getStyle($this->columnsAr[$i].'8')->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('ff333333'));
            $richText = new RichText();
            self::generateRichText($richText,$this->nameColumnsAr[$i],'FFFFFFFF','Arial',12,true,false);
            $this->sheet->setCellValue($this->columnsAr[$i].'8', $richText);
            $this->sheet->getStyle($this->columnsAr[$i].'8')->getAlignment()->setVertical('center');
            $this->sheet->getStyle($this->columnsAr[$i].'8')->getAlignment()->setHorizontal($horizontal);
            $this->sheet->getStyle($this->columnsAr[$i].'8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ffff6600');

        }
    }
    public function generateMainTable () {
        $dataTable = $this->getDataBasket();
        $keysAll = [
            'NAME',
            'PICTURE',
            'TEXT',
            'PRICE',
            'QUANTITY',
            'PRICE_ALL',
            'URL'
        ];        
        for ($i = 0; $i < count($dataTable); $i++) {
            for ($j = 0; $j < count($this->columnsAr); $j++) {
                $this->sheet->getRowDimension($this->rowIndexTableStart)->setRowHeight(150,'px');
                $this->sheet->getStyle($this->columnsAr[$j].$this->rowIndexTableStart)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('ff333333'));
                if ($j === 1) {
                    self::drawImageTable($dataTable[$i][$keysAll[$j]]);
                }
                else {   
                    $richText = new RichText();
                    $verticalAligment = 'center';
                    $horizontalAligment = 'center';
                    if (($j === 3) || ($j === 5)) {
                        $horizontalAligment = 'right';
                    }
                    if (($j === 0) || ($j === 2)) {
                        $horizontalAligment = 'left';
                    } 
                    self::generateRichText($richText,$dataTable[$i][$keysAll[$j]],'FF000000','Arial',12,false,false);
                    $this->sheet->setCellValue($this->columnsAr[$j].$this->rowIndexTableStart, $richText);
                    $this->sheet->getStyle($this->columnsAr[$j].$this->rowIndexTableStart)->getAlignment()->setVertical($verticalAligment);
                    $this->sheet->getStyle($this->columnsAr[$j].$this->rowIndexTableStart)->getAlignment()->setHorizontal($horizontalAligment);
                    $this->sheet->getStyle($this->columnsAr[$j].$this->rowIndexTableStart)->getAlignment()->setWrapText(true);
                    if ($j === 0) {
                        $this->sheet->getCell($this->columnsAr[$j].$this->rowIndexTableStart)->setHyperlink(new Hyperlink($dataTable[$i]['URL']));
                    }   
                }
            }
            $this->rowIndexTableStart += 1;
        }
    }
    public function generateFooterTable() {
        $this->sheet->getRowDimension($this->rowIndexTableStart)->setRowHeight(40,'px');
        $this->sheet->mergeCells('A'.$this->rowIndexTableStart.':'.'C'.$this->rowIndexTableStart,'hide');
        $this->sheet->mergeCells('D'.$this->rowIndexTableStart.':'.'F'.$this->rowIndexTableStart,'hide');
        $this->sheet->getStyle('B'.$this->rowIndexTableStart)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('ff333333')); 
        $this->sheet->getStyle('C'.$this->rowIndexTableStart)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('ff333333')); 
        $this->sheet->getStyle('E'.$this->rowIndexTableStart)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('ff333333')); 
        $this->sheet->getStyle('F'.$this->rowIndexTableStart)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('ff333333')); 

        $arValues = ['Итого: ',$this->allPrice];
        $arColumn = ['A','D'];
        for ($i = 0; $i < count($arValues); $i++) {
            $richText = new RichText();
            self::generateRichText($richText,$arValues[$i],'FFFFFFFF','Arial',14,true,false);
            $this->sheet->setCellValue($arColumn[$i].$this->rowIndexTableStart, $richText);
            $this->sheet->getStyle($arColumn[$i].$this->rowIndexTableStart)->getAlignment()->setVertical('center');
            $this->sheet->getStyle($arColumn[$i].$this->rowIndexTableStart)->getAlignment()->setHorizontal('right');
            $this->sheet->getStyle($arColumn[$i].$this->rowIndexTableStart)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ffff6600');
            $this->sheet->getStyle($arColumn[$i].$this->rowIndexTableStart)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('ff333333'));    
        }       
    }
    public function setWhiteBorder() {
        $columnsAll = [1,2,3,4,5,6,7];
        for ($i = 0; $i < count($columnsAll); $i++) {
            for ($j = 0; $j < count($this->columnsAr); $j++) {
                $this->sheet->getStyle($this->columnsAr[$j].$columnsAll[$i])->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('ffffffff'));
            }
        }
    }
    
    public function genDataXlsx() {
        self::addHeader();
        self::addInfoBasket();
        self::setWhiteBorder();
        self::generateTable();
        self::saveFile();
    }
    public function saveFile() {
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($_SERVER['DOCUMENT_ROOT'].'/local/lib/AmikomB2B/files_basket/basket_'.$this->FUSER_ID.'.xlsx');
        
    }
    public function deleteFile() {
        unlink($_SERVER['DOCUMENT_ROOT'].'/local/lib/AmikomB2B/files_basket/basket_'.$this->FUSER_ID.'.xlsx');
    }
    public function getFilePath() {
        return $_SERVER['DOCUMENT_ROOT'].'/local/lib/AmikomB2B/files_basket/basket_'.$this->FUSER_ID.'.xlsx';
    }
    public function downloadFile() {
        $filePath = $this->getFilePath();
        
        if (!empty($filePath)) {
            $fileName = 'basket_'.$this->FUSER_ID.'.xlsx';
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            //$this->deleteFile();
            exit;
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'File not found']);
        }
    }
}
?>