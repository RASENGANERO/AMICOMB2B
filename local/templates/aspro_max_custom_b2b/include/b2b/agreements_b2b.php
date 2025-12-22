<?
use AmikomB2B;
$userID = \AmikomB2B\DiscountInfo::getPartnerID($USER->GetID());
$agreementInfo = \AmikomB2B\DataB2BUser::getAgree($userID);
?>
<?if (!empty($agreementInfo)):?>
    <div class="partner-b2b-container">
        <div class="partner-b2b-info-container">
            <span class="agreements-b2b-main-text"><?='Договор от'.' '.$agreementInfo['PROPERTY_DATE_START_VALUE']?></span> 
            <span class="partner-b2b-text"><?=$agreementInfo['PROPERTY_NUMBER_VALUE']?></span>
            <span class="partner-b2b-text"><?=$agreementInfo['NAME']?></span>
        </div>
    </div>
<?endif;?>