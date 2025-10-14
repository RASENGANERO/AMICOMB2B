<?
    class ShowCustom {
        
        public static function addBtnShow (&$items) {
            $iBlockID = $_REQUEST['IBLOCK_ID'];
            $elementID = $_REQUEST['ID'];$iblockIDS = [33,51,23,20,53,29];
            if (in_array($iBlockID,$iblockIDS)) {
                $codeIblock = ShowCustom::getCodeIblock($iBlockID);
                $newLink = '';
                $textLink = '';
                $htmlElem = '';
                if (strpos($_SERVER['REQUEST_URI'],'element_edit') !== false) {
                    $textLink = 'Посмотреть элемент';
                    $newLink = '/'.$codeIblock.'/'.ShowCustom::getCodeElement($elementID).'/';
                    $htmlElem = '<a href="'.$newLink.'" class="adm-btn" target="_blank">'.$textLink.'</a>';
                }
                else {
                    $textLink = 'Посмотреть раздел';
                    $newLink = '/'.$codeIblock.'/'.ShowCustom::getPathSection($iBlockID,$elementID).'/';
                    $htmlElem = '<a href="'.$newLink.'" class="adm-btn" target="_blank">'.$textLink.'</a>';
                }
                $items[] = [
                    'TEXT' => $textLink,
                    'HTML' => $htmlElem,
                ];
            }
        }
        
        public static function getCodeIblock($iblockID) {
            $codeBlock = CIBlock::GetByID($iblockID)->Fetch()['CODE'];
            $codeBlock = end(explode('_',$codeBlock));
            return $codeBlock;
        }
        public static function getCodeElement($idElem) {
            $codeElement = CIBlockElement::GetByID($idElem)->Fetch()['CODE'];
            return $codeElement;
        }
        public static function getPathSection($idIBlock,$idSect) {
            $arSelect = [
                'ID',
                'CODE',
            ];
            $list = CIBlockSection::GetNavChain($idIBlock,intval($idSect), $arSelect , true);
            $arrData = [];
            foreach ($list as $arSectionPath) {
                $arrData[] = $arSectionPath['CODE'];
            }
            $arrData = implode('/',$arrData);
            $arrData = str_replace('\\', '',  $arrData);
            return $arrData;
        }
    }
?>