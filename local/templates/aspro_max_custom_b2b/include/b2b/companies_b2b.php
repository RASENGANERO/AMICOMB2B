<?
use AmikomB2B;
$userID = \AmikomB2B\DiscountInfo::getPartnerID($USER->GetID());
$companyInfo = \AmikomB2B\DataB2BUser::getCompany($userID);
?>
<?if (!empty($companyInfo)):?>
    <div class="partner-b2b-container">
        <div class="partner-b2b-info-container">
            <span class="company-b2b-main-text"><?=$companyInfo['NAME']?></span> 
            <span class="partner-b2b-text"><?='ИНН'.' '.$companyInfo['PROPERTY_INN_VALUE']?></span>
            <span class="partner-b2b-text"><?='КПП'.' '.$companyInfo['PROPERTY_KPP_VALUE']?></span>
        </div>
    </div>
<?endif;?>

