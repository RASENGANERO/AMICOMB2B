<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
header('Content-Type: application/json');
use Bitrix\Main\Loader;
use Bitrix\Catalog\Model\Price;

Loader::includeModule('iblock');

function setHTML($queryText) {
    $arFilter = [
        'IBLOCK_ID' => 33,
        'ACTIVE' => 'Y',
        'NAME' => '%'.$queryText.'%',
    ];
    $brands = [];
    $res = CIBlockElement::GetList(['SORT'=>'ASC'],$arFilter,false,false,['ID']);
    while ($ob = $res->Fetch()) {
        $brands[] = $ob['ID'];
    }

  //  $brands = Amikomnew\BrainSearch::getBrandID($queryText);
    //$query = \Bitrix\Iblock\ElementTable::getList()
    $query = \Bitrix\Iblock\ElementTable::query()
    ->setSelect(['ID', 'IBLOCK_ID', 'PREVIEW_PICTURE', 'NAME','CODE'])
    ->setFilter([
        'IBLOCK_ID' => 29,
        'ACTIVE' => 'Y',
        'NAME' => '%'.$queryText.'%'
    ])
    ->setLimit(10)
    ->setOrder(['SORT' => 'ASC']); // Укажите порядок сортировки
    $resultQuery = $query->exec();
    
    $searchElements = [];

        while ($element = $resultQuery->Fetch()) {
            $path = CFile::GetPath($element['PREVIEW_PICTURE']);
            $priceData = Price::getList([
                'filter' => ['PRODUCT_ID' => $element['ID']],
                'select' => ['PRICE'],
                'limit' => 1,
            ])->fetch();
            if (intval($priceData['PRICE']) === 0) {
                $priceData['PRICE'] = 'По запросу';
            }
            if (strpos(strtoupper($element['NAME']), strtoupper($queryText)) !== false) {
                $uppercaseQueryText = strtoupper($queryText);
                $element['NAME'] = str_replace($uppercaseQueryText, '<b>' . $uppercaseQueryText . '</b>', $element['NAME']);
            }
            $searchElements[] = [
                'NAME' => $element['NAME'],
                'PREVIEW_PICTURE' => $path,
                'PRICE' => $priceData['PRICE'],
                'CODE' => $element['CODE']
            ];
        }
    
        $arFilter = [
            'IBLOCK_ID' => 29,
            'ACTIVE' => 'Y',
            'PROPERTY_CML2_ARTICLE' => '%'.$queryText.'%',
        ];
        $arSelect = [
            'ID', 'IBLOCK_ID', 'PREVIEW_PICTURE', 'NAME', 'CODE'
        ];
        
        $res = CIBlockElement::GetList(['SORT' => 'ASC'], $arFilter, false, ['nTopCount' => 10], $arSelect);
        
        while ($element = $res->Fetch()) {
            $path = CFile::GetPath($element['PREVIEW_PICTURE']);
            $priceData = Price::getList([
                'filter' => ['PRODUCT_ID' => $element['ID']],
                'select' => ['PRICE'],
                'limit' => 1,
            ])->fetch();
            if (intval($priceData['PRICE']) === 0) {
                $priceData['PRICE'] = 'По запросу';
            }
            $searchElements[] = [
                'NAME' => $element['NAME'],
                'PREVIEW_PICTURE' => $path,
                'PRICE' => $priceData['PRICE'],
                'CODE' => $element['CODE']
            ];
        }
    
        $arFilter = [
            'IBLOCK_ID' => 29,
            'ACTIVE' => 'Y',
            'PROPERTY_BRAND' => $brands,
        ];
        $arSelect = [
            'ID', 'IBLOCK_ID', 'PREVIEW_PICTURE', 'NAME', 'CODE'
        ];
        
        $res = CIBlockElement::GetList(['SORT' => 'ASC'], $arFilter, false, ['nTopCount' => 10], $arSelect);
        
        while ($element = $res->Fetch()) {
            $path = CFile::GetPath($element['PREVIEW_PICTURE']);
            $priceData = Price::getList([
                'filter' => ['PRODUCT_ID' => $element['ID']],
                'select' => ['PRICE'],
                'limit' => 1,
            ])->fetch();
            if (intval($priceData['PRICE']) === 0) {
                $priceData['PRICE'] = 'По запросу';
            }
            $searchElements[] = [
                'NAME' => $element['NAME'],
                'PREVIEW_PICTURE' => $path,
                'PRICE' => $priceData['PRICE'],
                'CODE' => $element['CODE']
            ];
        }
    
   
    
    $html = '
    <div class="title-search-result title-search-input fixed_type" style="display: block; position: fixed; z-index: 30012; top: 93px; left: 28px; width: 1208px;">
        <div class="search-maxwidth-wrapper">
            <div class="bx_searche scrollblock scrollblock--thick">';

    foreach ($searchElements as $searchEl) {
        $html .= '
        <a class="bx_item_block" href="/catalog/' .$searchEl['CODE'] . '/">
            <div class="maxwidth-theme">
                <div class="bx_img_element">
                    <img data-lazyload="" class="lazyloaded" 
                         src="' . $searchEl['PREVIEW_PICTURE']. '" 
                         data-src="' . $searchEl['PREVIEW_PICTURE'] . '" />
                </div>
                <div class="bx_item_element">
                    <span class="font_sm">' . $searchEl['NAME'] . '</span>
                    <div class="price cost prices font_sxs">
                        <div class="title-search-price">
                            <div class="price">' . $searchEl['PRICE'] . '</div>
                        </div>
                    </div>
                </div>
            </div>
        </a>';
    }
    $html .= '
            </div>
        </div>
    </div>';
    return str_replace('\r\n','',$html);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $res = setHTML($_POST['q']);
    echo json_encode(['data' =>  $res], JSON_UNESCAPED_UNICODE);
}

?>