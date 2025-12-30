<?
use AmikomB2B;
$managerInfo = [];
$userID = \AmikomB2B\DiscountInfo::getPartnerID($USER->GetID());
if (!empty($userID)) {
    $managerInfo = \AmikomB2B\DataB2BUser::getManager($userID);
}
?>
<?if (!empty($managerInfo)):?>
    <div class="manager-b2b-container">
        <div class="manager-b2b-info-container">
            <span class="manager-b2b-main-text">Ваш менеджер:</span> 
            <span class="manager-b2b-text"><?=$managerInfo['UF_NAME']?></span>
            <?if (!empty($managerInfo['UF_EMAIL'])):?>
                <a class="manager-b2b-mail" href="mailto:<?=$managerInfo['UF_EMAIL']?>"><?=$managerInfo['UF_EMAIL']?></a>
            <?endif;?>
            <?if (!empty($managerInfo['UF_PHONE_FORMATTED'])):?>
                <div class="manager-info">
                    <span class="manager-b2b-text">Тел. </span> 
                    <a class="manager-b2b-phone" href="tel:<?=str_replace(' ','',$managerInfo['UF_PHONE_FORMATTED'])?>"><?=$managerInfo['UF_PHONE_FORMATTED']?></a>
                </div>
            <?endif;?>
        </div>
    </div>
<?endif;?>