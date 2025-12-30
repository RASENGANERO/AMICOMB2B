<?
use AmikomB2B;
$userID = \AmikomB2B\DiscountInfo::getPartnerID($USER->GetID());
$companyInfo = [];
if (!empty($userID)) {
    $companyInfo = \AmikomB2B\DataB2BUser::getCompany($userID);
}
?>
<?if (!empty($companyInfo)):?>
    <div class="partner-b2b-container">
        <div class="partner-b2b-info-container">
            <span class="company-b2b-main-text"><?=$companyInfo['NAME']?></span> 
            <span class="partner-b2b-text"><?='ИНН'.' '.$companyInfo['PROPERTY_INN_VALUE']?></span>
            <span class="partner-b2b-text"><?='КПП'.' '.$companyInfo['PROPERTY_KPP_VALUE']?></span>
        </div>
        <img class="doc-b2b-image" src="/local/templates/aspro_max_custom_b2b/images/b2b/b2b-doc-agree.jpg"> 
    </div>
<?endif;?>

