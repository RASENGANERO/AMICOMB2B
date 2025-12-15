<?php
use Bitrix\Main\Application;
use Bitrix\Main\Web\PostDecodeFilter;
use Bitrix\Main\Loader;
use Bitrix\Main\Security\Sign\Signer;
use Bitrix\Main\Security\Sign\BadSignatureException;
use Bitrix\Main\Web\Json;

define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC','Y');
define('DisableEventsCheck', true);
define('BX_SECURITY_SHOW_MESSAGE', true);
define('NOT_CHECK_PERMISSIONS', true);

$siteId = isset($_REQUEST['SITE_ID']) && is_string($_REQUEST['SITE_ID']) ? $_REQUEST['SITE_ID'] : '';
$siteId = mb_substr((string) preg_replace('/[^a-z0-9_]/i', '', $siteId), 0, 2);
if (!empty($siteId) && is_string($siteId)) {
	define('SITE_ID', $siteId);
}

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

use CMax as Solution;

$request = Application::getInstance()->getContext()->getRequest();
$request->addFilter(new PostDecodeFilter);

if (!Loader::includeModule('sale')) {
	return;
}

$signer = new Signer;
try {
	$signedParamsString = $request->get('signedParamsString') ?: '';
	$params = $signer->unsign($signedParamsString, 'sale.order.ajax');
	$params = Solution::unserialize(base64_decode($params));
} catch (BadSignatureException) {
	die();
}

$arPost = $request->get('order');
$arProps = $request->get('props');

$action = $request->get($params['ACTION_VARIABLE']);
if (empty($action) && !$arProps) {
	return;
}

function getPropsByFilter($array, $field, $value){
    return array_filter($array, fn($item) => $item[$field] === $value);
}

$arUserProps = getPropsByFilter($arProps['properties'], 'USER_PROPS', 'Y');

$profileNameProp = getPropsByFilter($arUserProps, 'IS_PROFILE_NAME', 'Y')[0];
$profileName = $profileNameProp ? $arPost['ORDER_PROP_'.$profileNameProp['ID']] : '';


$arFields = [
   "NAME" => $profileName,
   "USER_ID" => $GLOBALS['USER']->GetID(),
   "PERSON_TYPE_ID" => $arPost['PERSON_TYPE']
];

$properties = [];
foreach ($arUserProps as $value) {
    $properties[$value['ID']] = $arPost['ORDER_PROP_'.$value['ID']];
}

$arErrors = [];
$profileID = CSaleOrderUserProps::DoSaveUserProfile(
    $GLOBALS['USER']->GetID(),
    $arPost['PROFILE_ID'],
    $profileName,
    $arPost['PERSON_TYPE'],
    $properties,
    $arErrors
);

header('Content-Type: application/json');

echo Json::encode(['profileID' => $profileID, 'error' => $arErrors]);
?>
