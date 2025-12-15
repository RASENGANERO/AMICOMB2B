<?php
//Класс предназначенный для вывода данных о товарах
CModule::IncludeModule('iblock');
class ShowHelper {
    const IBLOCK_REV_ID = 22;
    public function __construct(public int $ID_ELEMENT)
    {
    }
    public function getCountReviews(): int {
        $order = ['SORT' => 'ASC'];
        $filterElements = [
            'IBLOCK_ID' => self::IBLOCK_REV_ID,
            'PROPERTY_PRODUCT' => $this->ID_ELEMENT
        ];
        return CIBlockElement::GetList($order, $filterElements)->SelectedRowsCount();
    }
}


?>