<?php
//Класс предназначенный для вывода данных о товарах
CModule::IncludeModule('iblock');
class ShowHelper {
    public int $ID_ELEMENT;
    const IBLOCK_REV_ID = 22;
    public function __construct(int $idElement) {
        $this->ID_ELEMENT = $idElement;
    }
    public function getCountReviews(): int {
        $order = ['SORT' => 'ASC'];
        $filterElements = [
            'IBLOCK_ID' => self::IBLOCK_REV_ID,
            'PROPERTY_PRODUCT' => $this->ID_ELEMENT
        ];
        $countReviews = CIBlockElement::GetList($order, $filterElements)->SelectedRowsCount();
        return $countReviews;
    }
}


?>