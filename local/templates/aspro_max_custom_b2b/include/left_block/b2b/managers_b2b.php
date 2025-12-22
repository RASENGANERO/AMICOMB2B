<?
use AmikomB2B;
$userID = \AmikomB2B\DiscountInfo::getPartnerID($USER->GetID());
$managerInfo = \AmikomB2B\DataB2BUser::getManager($userID);
//print_r($managerInfo);
?>
<?if (!empty($managerInfo)):?>
    <div class="manager-b2b-container">
        <div class="manager-b2b-info-container">
            <span class="manager-b2b-main-text">Ваш менеджер:</span> 
            <span class="manager-b2b-text"><?=$managerInfo['UF_NAME']?></span> 
            <a class="manager-b2b-mail" href="mailto:<?=$managerInfo['UF_EMAIL']?>"><?=$managerInfo['UF_EMAIL']?></a>
            <div class="manager-info">
                <span class="manager-b2b-text">Тел. </span> 
                <a class="manager-b2b-phone" href="tel:<?=$managerInfo['UF_PHONE']?>"><?=$managerInfo['UF_PHONE_FORMATTED']?></a>
            </div>
            
        </div>
    </div>
<?endif;?>