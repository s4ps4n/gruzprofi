<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class a_a_gruzprofi extends CModule
{
    public $MODULE_ID = 'a_a.gruzprofi';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME = "A A";
    public $PARTNER_URI = "https://a-a.ru";

    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . '/version.php');
        
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = "ГрузПрофи - Готовый сайт грузоперевозок";
        $this->MODULE_DESCRIPTION = "Установка лендинга грузоперевозок с калькулятором, автопарком и отзывами";
    }

public function DoInstall()
{
    global $APPLICATION, $step;
    
    // Сначала копируем файлы
    $this->InstallFiles();
    
    // Потом регистрируем модуль
    ModuleManager::registerModule($this->MODULE_ID);
    
    $step = intval($step);
    if ($step < 2) {
        $APPLICATION->IncludeAdminFile(
            Loc::getMessage("A_A_GRUZPROFI_INSTALL_TITLE"), 
            __DIR__ . "/step1.php"
        );
    } elseif ($step == 2) {
        $this->InstallWizard();
    }
}

public function DoUninstall()
{
    global $APPLICATION;
    
    // Удаляем инфоблоки
    $this->DeleteIBlocks();
    
    // Удаляем тип инфоблоков
    $this->DeleteIBlockType();
    
    // Удаляем файлы шаблона
    DeleteDirFilesEx("/local/templates/gruzprofi/");
    DeleteDirFilesEx("/local/include/");
    
    // Удаляем страницы сайта, если они были созданы модулем
    $this->DeletePages();
    
    ModuleManager::unRegisterModule($this->MODULE_ID);
}

protected function DeleteIBlocks()
{
    if (!CModule::IncludeModule("iblock")) {
        return;
    }
    
    $iblockCodes = [
        'FLEET',
        'FEATURES',
        'CALC_TARIFFS',
        'HERO_CONTENT',
        'B2B_BANNER',
        'REVIEWS',
        'CONTACTS',
        'SERVICES'
    ];
    
    foreach ($iblockCodes as $code) {
        $res = CIBlock::GetList([], ['CODE' => $code]);
        if ($arRes = $res->Fetch()) {
            CIBlock::Delete($arRes['ID']);
        }
    }
}

protected function DeleteIBlockType()
{
    if (!CModule::IncludeModule("iblock")) {
        return;
    }
    
    $obBlocktype = new CIBlockType;
    $obBlocktype->Delete('gruzprofi_content');
}

protected function DeletePages()
{
    // Удаляем страницу политики конфиденциальности
    DeleteDirFilesEx("/privacy/");
    
    // Проверяем, был ли index.php создан модулем (содержит метку)
    $indexPath = $_SERVER['DOCUMENT_ROOT'] . '/index.php';
    if (file_exists($indexPath)) {
        $content = file_get_contents($indexPath);
        // Если файл содержит характерный для модуля заголовок
        if (strpos($content, 'Грузоперевозки и переезды с фиксированной ценой') !== false) {
            // Восстанавливаем стандартный index.php Битрикса
            copy($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/install/wizards/bitrix/empty/site/public/ru/index.php', $indexPath);
        }
    }
}

    public function InstallFiles()
    {
        // Копируем шаблон сайта
        CopyDirFiles(
            __DIR__ . "/files/templates/gruzprofi",
            $_SERVER["DOCUMENT_ROOT"] . "/local/templates/gruzprofi",
            true,
            true
        );
        
        // Копируем включаемые области
        CopyDirFiles(
            __DIR__ . "/demo_data/include_areas",
            $_SERVER["DOCUMENT_ROOT"] . "/local/include",
            true,
            true
        );
        
        return true;
    }
    public function InstallWizard()
    {
        global $APPLICATION;
        
        // Запускаем мастер установки
        LocalRedirect("/bitrix/admin/wizard_install.php?lang=" . LANGUAGE_ID . "&wizardName=a_a:gruzprofi&" . bitrix_sessid_get());
    }