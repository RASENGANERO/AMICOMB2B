<?
namespace AmikomB2B;
use Bitrix\Sale;
class DiscountPrices {
    public array $discountArr;
    public int $percent;
    
    public function __construct($discountArrValue,$percent) {
        $this->discountArr = $discountArrValue;
        $this->percent = $percent;
    }

    public function generateDiscountValues():array {
        
        $this->discountArr['BASE']['DISCOUNT_DIFF_PERCENT'] = $this->percent;
        $discountDiff = self::getDiscountDiff($this->discountArr['BASE']['VALUE']);
        $discountValue = self::getDiscountValue($this->discountArr['BASE']['VALUE']);
        $this->discountArr['BASE']['DISCOUNT_DIFF'] = $discountDiff;
        $this->discountArr['BASE']['DISCOUNT_VALUE'] = $discountValue;
        $this->discountArr['BASE']['DISCOUNT_VALUE_VAT'] = $discountValue;
        $this->discountArr['BASE']['ROUND_VALUE_VAT'] = $discountValue;
        $this->discountArr['BASE']['UNROUND_DISCOUNT_VALUE'] = $discountValue;
        $this->discountArr['BASE']['DISCOUNT_VATRATE_VALUE'] = self::getVatrateNovatValue($this->discountArr['BASE']['DISCOUNT_VATRATE_VALUE']);
        $this->discountArr['BASE']['ROUND_VATRATE_VALUE'] = self::getVatrateNovatValue($this->discountArr['BASE']['ROUND_VATRATE_VALUE']);
        $this->discountArr['BASE']['DISCOUNT_VALUE_NOVAT'] = self::getVatrateNovatValue($this->discountArr['BASE']['DISCOUNT_VALUE_NOVAT']);
        $this->discountArr['BASE']['ROUND_VALUE_NOVAT'] = self::getVatrateNovatValue($this->discountArr['BASE']['ROUND_VALUE_NOVAT']);
        $this->discountArr['BASE']['PRINT_DISCOUNT_VALUE_NOVAT'] = self::getPrintValue($this->discountArr['BASE']['DISCOUNT_VALUE_NOVAT']);
        $this->discountArr['BASE']['PRINT_DISCOUNT_VALUE_VAT'] = self::getPrintValue($this->discountArr['BASE']['DISCOUNT_VALUE_VAT']);
        $this->discountArr['BASE']['PRINT_DISCOUNT_DIFF'] = self::getPrintValue($this->discountArr['BASE']['DISCOUNT_DIFF']);
        $this->discountArr['BASE']['PRINT_DISCOUNT_VALUE'] = self::getPrintValue($this->discountArr['BASE']['DISCOUNT_VALUE']);
        return $this->discountArr;
    }


    public function generateDiscountBasketValues():array {
        
        $this->discountArr['DISCOUNT_PRICE_PERCENT'] = $this->percent;
        $this->discountArr['DISCOUNT_PRICE_PERCENT_FORMATED'] = $this->percent.'%';
        $this->discountArr['SHOW_DISCOUNT_PRICE'] = 1;
        
        $discountPrice = \CSaleBasket::GetByID($this->discountArr['ID'])['DISCOUNT_PRICE'];
        print_r($discountPrice);
        $this->discountArr['DISCOUNT_PRICE'] = $discountPrice;
        $this->discountArr['SUM_DISCOUNT_PRICE'] = $discountPrice;
        $this->discountArr['DISCOUNT_PRICE_FORMATED'] = self::getPrintValue($this->discountArr['DISCOUNT_PRICE']);
        $this->discountArr['SUM_DISCOUNT_PRICE_FORMATED'] = self::getPrintValue($this->discountArr['SUM_DISCOUNT_PRICE']);
        
        $this->discountArr['COLUMN_LIST'] = $this->generateColumnBasket($this->discountArr['COLUMN_LIST']);

        return $this->discountArr;
    }

    public function generateColumnBasket($columnsArray) {
        $arrDiscountColumn = [
            'CODE' => 'DISCOUNT',
            'NAME' => 'Скидка',
            'VALUE' => $this->discountArr['DISCOUNT_PRICE_PERCENT_FORMATED'],
            'IS_TEXT' => 1,
            'HIDE_MOBILE' => ''
        ];
        array_unshift($columnsArray,$arrDiscountColumn);
        return $columnsArray;
    }


    public static function getPrintValue($printValue) {
        return number_format($printValue, 0, ',', ' ') . ' ₽';
    }


    public static function getPrintValueFloat($printValue) {
        if (floor($printValue) == $printValue) {
            return number_format($printValue, 0, '.', ' ') . " ₽";
        } else {
            return number_format($printValue, 2, '.', ' ') . " ₽";
        }
    }
    public function getVatrateNovatValue($valueVatrateNovat) {
        $vatRateNovat = $valueVatrateNovat - ($valueVatrateNovat / 100 * $this->percent);
        return $vatRateNovat;
    }

    public function getDiscountDiff($discountBaseDiff) {
        $discountDiff = $discountBaseDiff / 100 * $this->percent;
        return $discountDiff;
    }
    public function getDiscountValue($discountBaseValue) {
        $discountValue = $discountBaseValue - ($discountBaseValue / 100 * $this->percent);
        return $discountValue;
    }
    public static function getAllDiscountOrder() {
        $fuserId = Sale\Fuser::getId();
        $sumDisc = 0;
        $resDisc = \CSaleBasket::GetList(
            array("ID" => "DESC"),
            array("LID" => 's1', "ORDER_ID" => "NULL",'F_USER'=>$fuserId),
            false, false, ["DISCOUNT_PRICE"]
        );
        while($obj = $resDisc->Fetch()) {
            $sumDisc+=$obj['DISCOUNT_PRICE'];
        }
        $sumDisc = self::getPrintValue($sumDisc);
        return $sumDisc;
    }
}
?>