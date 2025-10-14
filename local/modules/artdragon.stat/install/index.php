<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

class artdragon_stat extends CModule
{
    public $MODULE_ID = 'artdragon.stat';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_URI;
    public $MODULE_GROUP_RIGHTS;

    public function __construct() {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        $this->MODULE_NAME = Loc::getMessage('MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('PARTNER_URL');
        $this->MODULE_GROUP_RIGHTS = 'N';
    }

    public function InstallDB($arParams = [])
    {
        $connection = Application::getConnection();
        $errors = [];
        // Выполняем SQL-скрипт для создания таблиц
        try {
            $sqlFilePath = $_SERVER["DOCUMENT_ROOT"] . "/local/modules/" . $this->MODULE_ID . "/install/db/" . strtolower($connection->getType()) . "/install.sql";
            $sql = file_get_contents($sqlFilePath);
            $connection->queryExecute($sql);
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }

        if (!empty($errors)) {
            Application::getInstance()->getExceptionHandler()->writeToLog(implode("\n", $errors));
            return false;
        }

        return true;
    }

    public function UnInstallDB($arParams = [])
    {
        // Удаляем таблицы, если не нужно сохранять данные
        if (array_key_exists("SAVEDATA", $arParams) && $arParams["SAVEDATA"] == "N") {
            $connection = Application::getConnection();
            $errors = [];
            // Выполняем SQL-скрипт для удаления таблиц
            try {
                $sqlFilePath = $_SERVER["DOCUMENT_ROOT"] . "/local/modules/" . $this->MODULE_ID . "/install/db/" . strtolower($connection->getType()) . "/uninstall.sql";
                $sql = file_get_contents($sqlFilePath);
                $connection->queryExecute($sql);
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
            if (!empty($errors)) {
                Application::getInstance()->getExceptionHandler()->writeToLog(implode("\n", $errors));
                return false;
            }
        }
        return true;
    }

    public function InstallFiles($arParams = [])
    {
        if (is_dir($p = $_SERVER["DOCUMENT_ROOT"]."/local/modules/".$this->MODULE_ID."/admin")){
            if ($dir = opendir($p)){
                while (false !== $item = readdir($dir)){
                    if ($item == ".." || $item == "." || $item == "menu.php")
                        continue;
                    file_put_contents($file = $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.$this->MODULE_ID.'_'.$item,
                    '<'.'? require($_SERVER["DOCUMENT_ROOT"]."/local/modules/'.$this->MODULE_ID.'/admin/'.$item.'");?'.'>');
                }
                closedir($dir);
            }
        }
        //копируем js
        if (is_dir($p = $_SERVER["DOCUMENT_ROOT"]."/local/modules/".$this->MODULE_ID."/install/js")){
            if ($dir = opendir($p)){
                while (false !== $item = readdir($dir)){
                    if ($item == ".." || $item == ".")
                        continue;
                    CopyDirFiles($p."/".$item, $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/".$this->MODULE_ID."/".$item, $ReWrite = True, $Recursive = True);
                }
                closedir($dir);
            }
        }
        return true;
    }

    public function UnInstallFiles()
    {
        if (is_dir($p = $_SERVER["DOCUMENT_ROOT"]."/local/modules/".$this->MODULE_ID."/admin"))
        {
            if ($dir = opendir($p))
            {
                while (false !== $item = readdir($dir))
                {
                    if ($item == ".." || $item == ".")
                        continue;
                    unlink($_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/".$this->MODULE_ID."_".$item);
                }
                closedir($dir);
            }
        }
        //удаляем стили
        if(is_dir($_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/.default/".$this->MODULE_ID)){                       
            DeleteDirFilesEx("/bitrix/themes/.default/".$this->MODULE_ID);
        }
        //удаляем js
        if(is_dir($_SERVER["DOCUMENT_ROOT"]."/bitrix/js/".$this->MODULE_ID)){                    
            DeleteDirFilesEx("/bitrix/js/".$this->MODULE_ID);
        }
        return true;
    }    

    public function DoInstall()
    {
        $this->InstallFiles();
        $this->InstallDB();
        ModuleManager::registerModule($this->MODULE_ID);
    }
    public function DoUninstall() //удаляем модуль за 2 шага - спрашиваем, сохранить ли таблицы
    {
        global $APPLICATION, $step; 
        $step = IntVal($step);
        if ($step < 2) {    
            $APPLICATION->IncludeAdminFile(Loc::getMessage('PARTNER_NAME'), $_SERVER["DOCUMENT_ROOT"]."/local/modules/".$this->MODULE_ID."/install/unstep1.php");
        }
        elseif(($step == 2) and (array_key_exists("nextstep", $_REQUEST))){                        
            $this->UnInstallFiles();
            ModuleManager::unRegisterModule($this->MODULE_ID);
            $this->UnInstallDB(["SAVEDATA" => $_REQUEST["savedata"]]);
        }       
    }  
}
