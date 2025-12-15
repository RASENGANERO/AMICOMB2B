<?php

use Bitrix\Main\Type\Collection;

Collection::sortByColumn($arResult["SITES"], ['CURRENT' => SORT_DESC]);
