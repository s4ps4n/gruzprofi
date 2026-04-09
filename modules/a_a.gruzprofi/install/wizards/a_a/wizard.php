<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Application;

Loader::includeModule('iblock');

class Step1 extends CWizardStep
{
    public function InitStep()
    {
        $this->SetStepID("StartStep");
        $this->SetTitle("Начало установки");
        $this->SetNextStep("SiteSettingsStep");
        $this->SetNextCaption("Далее");
    }

    public function ShowStep()
    {
        $this->content = '<div style="padding:20px;">';
        $this->content .= '<h2>Мастер установки сайта "ГрузПрофи"</h2>';
        $this->content .= '<p>Будет создан полнофункциональный лендинг грузоперевозок со следующим функционалом:</p>';
        $this->content .= '<ul>';
        $this->content .= '<li>✓ Калькулятор стоимости переезда</li>';
        $this->content .= '<li>✓ Каталог автопарка с характеристиками</li>';
        $this->content .= '<li>✓ Блок преимуществ</li>';
        $this->content .= '<li>✓ B2B баннер для юрлиц</li>';
        $this->content .= '<li>✓ Карта с машинами онлайн</li>';
        $this->content .= '<li>✓ Отзывы клиентов</li>';
        $this->content .= '</ul>';
        $this->content .= '<p><strong>Внимание:</strong> Для работы карты потребуется API-ключ Яндекс.Карт (можно добавить позже).</p>';
        $this->content .= '</div>';
    }
}

class Step2 extends CWizardStep
{
    public function InitStep()
    {
        $this->SetStepID("SiteSettingsStep");
        $this->SetTitle("Настройки сайта");
        $this->SetNextStep("DataInstallStep");
        $this->SetNextCaption("Установить");
        $this->SetPrevStep("StartStep");
    }

    public function ShowStep()
    {
        $this->content = '<table class="wizard-data-table" style="width:100%">';
        
        // Название компании
        $this->content .= '<tr><th style="text-align:right; padding:8px;">Название компании:</th><td>';
        $this->content .= $this->ShowInputField('text', 'COMPANY_NAME', [
            'value' => 'ООО ГрузПрофи Транс',
            'size' => 40,
            'style' => 'width:300px; padding:6px;'
        ]);
        $this->content .= '</td></tr>';
        
        // Телефон
        $this->content .= '<tr><th style="text-align:right; padding:8px;">Телефон:</th><td>';
        $this->content .= $this->ShowInputField('text', 'PHONE', [
            'value' => '+7 (999) 123-45-67',
            'size' => 40,
            'style' => 'width:300px; padding:6px;'
        ]);
        $this->content .= '</td></tr>';
        
        // Email
        $this->content .= '<tr><th style="text-align:right; padding:8px;">Email:</th><td>';
        $this->content .= $this->ShowInputField('text', 'EMAIL', [
            'value' => 'info@gruzprofi.ru',
            'size' => 40,
            'style' => 'width:300px; padding:6px;'
        ]);
        $this->content .= '</td></tr>';
        
        // Часы работы
        $this->content .= '<tr><th style="text-align:right; padding:8px;">Часы работы:</th><td>';
        $this->content .= $this->ShowInputField('text', 'WORK_HOURS', [
            'value' => '24/7, без выходных',
            'size' => 40,
            'style' => 'width:300px; padding:6px;'
        ]);
        $this->content .= '</td></tr>';
        
        // Telegram
        $this->content .= '<tr><th style="text-align:right; padding:8px;">Ссылка на Telegram:</th><td>';
        $this->content .= $this->ShowInputField('text', 'TELEGRAM_LINK', [
            'value' => 'https://t.me/username',
            'size' => 40,
            'style' => 'width:300px; padding:6px;'
        ]);
        $this->content .= '</td></tr>';
        
        // ИНН
        $this->content .= '<tr><th style="text-align:right; padding:8px;">ИНН:</th><td>';
        $this->content .= $this->ShowInputField('text', 'INN', [
            'value' => '7700000000',
            'size' => 40,
            'style' => 'width:300px; padding:6px;'
        ]);
        $this->content .= '</td></tr>';
        
        // ОГРН
        $this->content .= '<tr><th style="text-align:right; padding:8px;">ОГРН:</th><td>';
        $this->content .= $this->ShowInputField('text', 'OGRN', [
            'value' => '1234567890123',
            'size' => 40,
            'style' => 'width:300px; padding:6px;'
        ]);
        $this->content .= '</td></tr>';
        
        // Яндекс.Карты API-ключ
        $this->content .= '<tr><th style="text-align:right; padding:8px;">Яндекс.Карты API-ключ:</th><td>';
        $this->content .= $this->ShowInputField('text', 'YANDEX_MAPS_API_KEY', [
            'value' => '',
            'size' => 50,
            'style' => 'width:400px; padding:6px;'
        ]);
        $this->content .= '<br><small>Получить ключ можно в <a href="https://developer.tech.yandex.ru/" target="_blank">Кабинете разработчика Яндекса</a>. Если оставить пустым, карта может работать с ограничениями.</small>';
        $this->content .= '</td></tr>';
        
        $this->content .= '</table>';
    }

    public function OnPostForm()
    {
        $wizard = $this->GetWizard();
        
        $wizard->SetVar('COMPANY_NAME', $_POST['COMPANY_NAME']);
        $wizard->SetVar('PHONE', $_POST['PHONE']);
        $wizard->SetVar('EMAIL', $_POST['EMAIL']);
        $wizard->SetVar('WORK_HOURS', $_POST['WORK_HOURS']);
        $wizard->SetVar('TELEGRAM_LINK', $_POST['TELEGRAM_LINK']);
        $wizard->SetVar('INN', $_POST['INN']);
        $wizard->SetVar('OGRN', $_POST['OGRN']);
        $wizard->SetVar('YANDEX_MAPS_API_KEY', $_POST['YANDEX_MAPS_API_KEY']);
    }
}

class Step3 extends CWizardStep
{
    public function InitStep()
    {
        $this->SetStepID("DataInstallStep");
        $this->SetTitle("Установка данных");
        $this->SetNextStep("FinishStep");
        $this->SetNextCaption("Завершить");
        $this->SetPrevStep("SiteSettingsStep");
    }

    public function ShowStep()
{
    $this->content = '<div style="padding:20px;">';
    $this->content .= '<p>Идёт установка данных...</p>';
    
    $results = [];
    $hasError = false;
    
    try {
        // Выполняем установку и собираем результаты
        $results = $this->InstallData();
        
        $this->content .= '<ul style="color:green;">';
        foreach ($results as $result) {
            if ($result['success']) {
                $this->content .= '<li>✓ ' . htmlspecialchars($result['message']) . '</li>';
            } else {
                $this->content .= '<li style="color:red;">✗ ' . htmlspecialchars($result['message']) . '</li>';
                $hasError = true;
            }
        }
        $this->content .= '</ul>';
        
        if ($hasError) {
            $this->content .= '<p style="color:red;">Установка завершена с ошибками. Проверьте права доступа и повторите попытку.</p>';
        }
        
    } catch (Exception $e) {
        $this->content .= '<p style="color:red;">Ошибка: ' . htmlspecialchars($e->getMessage()) . '</p>';
        $hasError = true;
    }
    
    if ($hasError) {
        $this->SetNextStep("SiteSettingsStep");
        $this->SetNextCaption("Назад к настройкам");
    }
    
    $this->content .= '</div>';
}

    protected function InstallData()
{
    $wizard = $this->GetWizard();
    $results = [];
    
    try {
        // Создаём тип инфоблоков
        $this->CreateIBlockType();
        $results[] = ['success' => true, 'message' => 'Тип инфоблоков создан'];
    } catch (Exception $e) {
        $results[] = ['success' => false, 'message' => 'Ошибка создания типа инфоблоков: ' . $e->getMessage()];
        return $results;
    }
    
    try {
        // Создаём инфоблоки и импортируем данные
        $iblocks = $this->CreateAllIBlocks();
        
        foreach ($iblocks as $code => $id) {
            if ($id > 0) {
                $results[] = ['success' => true, 'message' => 'Инфоблок "' . $code . '" создан'];
                $wizard->SetVar('IBLOCK_' . $code, $id);
            } else {
                $results[] = ['success' => false, 'message' => 'Инфоблок "' . $code . '" не создан'];
            }
        }
    } catch (Exception $e) {
        $results[] = ['success' => false, 'message' => 'Ошибка создания инфоблоков: ' . $e->getMessage()];
        return $results;
    }
    
    try {
        // Копируем шаблон сайта
        $this->CopyTemplate();
        $results[] = ['success' => true, 'message' => 'Шаблон сайта скопирован'];
    } catch (Exception $e) {
        $results[] = ['success' => false, 'message' => 'Ошибка копирования шаблона: ' . $e->getMessage()];
    }
    
    try {
        // Копируем и обновляем включаемые области
        $this->CopyAndUpdateIncludeAreas();
        $results[] = ['success' => true, 'message' => 'Включаемые области обновлены'];
    } catch (Exception $e) {
        $results[] = ['success' => false, 'message' => 'Ошибка обновления включаемых областей: ' . $e->getMessage()];
    }
    
    try {
        // Создаём страницы сайта
        $this->CreatePages($iblocks ?? []);
        $results[] = ['success' => true, 'message' => 'Страницы сайта созданы'];
    } catch (Exception $e) {
        $results[] = ['success' => false, 'message' => 'Ошибка создания страниц: ' . $e->getMessage()];
    }
    
    return $results;
}

    protected function CreateIBlockType()
    {
        $obBlocktype = new CIBlockType;
        $arFields = [
            'ID' => 'gruzprofi_content',
            'SECTIONS' => 'Y',
            'IN_RSS' => 'N',
            'SORT' => 100,
            'LANG' => [
                'ru' => [
                    'NAME' => 'Контент ГрузПрофи',
                    'SECTION_NAME' => 'Разделы',
                    'ELEMENT_NAME' => 'Элементы'
                ],
                'en' => [
                    'NAME' => 'GruzProfi Content',
                    'SECTION_NAME' => 'Sections',
                    'ELEMENT_NAME' => 'Elements'
                ]
            ]
        ];
        
        if (!CIBlockType::GetByID('gruzprofi_content')->Fetch()) {
            $obBlocktype->Add($arFields);
        }
    }

    protected function CreateAllIBlocks()
    {
        $iblockCodes = [
            'FLEET' => 'auto.xml',
            'FEATURES' => 'advantages.xml',
            'CALC_TARIFFS' => 'tarif.xml',
            'HERO_CONTENT' => 'contentheader.xml',
            'B2B_BANNER' => 'btb.xml',
            'REVIEWS' => 'reviews.xml',
            'CONTACTS' => 'contacts.xml',
            'SERVICES' => 'services.xml'
        ];
        
        $iblockNames = [
            'FLEET' => 'Автопарк',
            'FEATURES' => 'Преимущества',
            'CALC_TARIFFS' => 'Тарифы калькулятора',
            'HERO_CONTENT' => 'Контент хедера',
            'B2B_BANNER' => 'B2B баннер',
            'REVIEWS' => 'Отзывы',
            'CONTACTS' => 'Контакты',
            'SERVICES' => 'Услуги'
        ];
        
        $iblockIds = [];
        
        foreach ($iblockCodes as $code => $xmlFile) {
            // Создаём инфоблок
            $id = $this->CreateIBlock(strtolower($code), $iblockNames[$code]);
            $iblockIds[$code] = $id;
            
            // Импортируем XML если файл существует
            $xmlPath = __DIR__ . '/../../demo_data/' . $xmlFile;
            if (file_exists($xmlPath)) {
                $this->ImportIBlockXML($id, $xmlPath);
            }
        }
        
        return $iblockIds;
    }

    protected function CreateIBlock($code, $name)
    {
        $iblock = new CIBlock;

        // Проверяем, существует ли уже (ищем по коду как есть)
        $res = CIBlock::GetList([], ['CODE' => $code]);
        if ($arRes = $res->Fetch()) {
            return $arRes['ID'];
        }

        $arFields = [
            'ACTIVE' => 'Y',
            'NAME' => $name,
            'CODE' => $code, // оставляем как есть, без strtolower
            'API_CODE' => $code,
            'IBLOCK_TYPE_ID' => 'gruzprofi_content',
            'SITE_ID' => [CSite::GetDefSite()],
            'SORT' => 100,
            'VERSION' => 1,
            'GROUP_ID' => ['2' => 'R']
        ];

        return $iblock->Add($arFields);
    }
    protected function ImportIBlockXML($iblockId, $xmlFile)
    {
        if (!file_exists($xmlFile)) {
            return false;
        }
        
        // Читаем XML файл
        $xmlContent = file_get_contents($xmlFile);
        
        // Исправляем проблемы с кодировкой если нужно
        $xmlContent = $this->fixEncoding($xmlContent);
        
        // Создаём временный файл
        $tmpFile = $_SERVER['DOCUMENT_ROOT'] . '/upload/tmp_import_' . md5($xmlFile) . '.xml';
        file_put_contents($tmpFile, $xmlContent);
        
        // Импортируем
        $obXMLFile = new CIBlockXMLFile;
        $obXMLFile->DropTemporaryTables();
        $obXMLFile->CreateTemporaryTables();
        $obXMLFile->ReadXMLToDB($tmpFile, $iblockId);
        $obXMLFile->IndexTemporaryTables();
        $obXMLFile->ImportMetaData([$iblockId]);
        $obXMLFile->DropTemporaryTables();
        
        // Удаляем временный файл
        @unlink($tmpFile);
        
        return true;
    }
    
    protected function fixEncoding($content)
    {
        // Пытаемся определить и исправить кодировку
        if (function_exists('mb_detect_encoding')) {
            $encoding = mb_detect_encoding($content, ['UTF-8', 'Windows-1251', 'ISO-8859-5'], true);
            if ($encoding && $encoding !== 'UTF-8') {
                $content = mb_convert_encoding($content, 'UTF-8', $encoding);
            }
        }
        
        // Заменяем известные кракозябры
        $replacements = [
            '袣芯屑屑械褉褔械褋泻邪褟袠薪褎芯褉屑邪褑懈褟' => 'КоммерческаяИнформация',
            '袙械褉褋懈褟小褏械屑褘' => 'ВерсияСхемы',
            '袛邪褌邪肖芯褉屑懈褉芯胁邪薪懈褟' => 'ДатаФормирования',
            '袣谢邪褋褋懈褎懈泻邪褌芯褉' => 'Классификатор',
            '袧邪懈屑械薪芯胁邪薪懈械' => 'Наименование',
            '小胁芯泄褋褌胁邪' => 'Свойства',
            '小胁芯泄褋褌胁芯' => 'Свойство',
            '袦薪芯卸械褋褌胁械薪薪芯械' => 'Множественное',
            '袘懈褌褉懈泻褋袗泻褌懈胁薪芯褋褌褜' => 'БитриксАктивность',
            '小懈屑胁芯谢褜薪褘泄 泻芯写' => 'Символьный код',
            '小芯褉褌懈褉芯胁泻邪' => 'Сортировка',
            '袧邪褔邪谢芯 邪泻褌懈胁薪芯褋褌懈' => 'Начало активности',
            '袨泻芯薪褔邪薪懈械 邪泻褌懈胁薪芯褋褌懈' => 'Окончание активности',
            '袗薪芯薪褋' => 'Анонс',
            '袨锌懈褋邪薪懈械' => 'Описание',
            '袣邪褉褌懈薪泻邪 邪薪芯薪褋邪' => 'Картинка анонса',
            '袣邪褌邪谢芯谐' => 'Каталог',
            '孝芯胁邪褉褘' => 'Товары',
            '孝芯胁邪褉' => 'Товар',
            '袟薪邪褔械薪懈褟小胁芯泄褋褌胁' => 'ЗначенияСвойств',
            '袟薪邪褔械薪懈褟小胁芯泄褋褌胁邪' => 'ЗначенияСвойства',
            '袟薪邪褔械薪懈械' => 'Значение',
            '袚褉褍锌锌褘' => 'Группы',
            '袣邪褉褌懈薪泻邪' => 'Картинка',
            '袠写' => 'Ид',
        ];
        
        foreach ($replacements as $bad => $good) {
            $content = str_replace($bad, $good, $content);
        }
        
        return $content;
    }

    protected function CopyTemplate()
    {
        $templateSource = __DIR__ . '/../../files/templates/gruzprofi';
        $templateDest = $_SERVER['DOCUMENT_ROOT'] . '/local/templates/gruzprofi';
        
        if (!is_dir($templateDest)) {
            mkdir($templateDest, 0755, true);
        }
        
        CopyDirFiles($templateSource, $templateDest, true, true);
        
        // Устанавливаем шаблон как основной
        $this->SetTemplateAsMain('gruzprofi');
    }
    
    protected function SetTemplateAsMain($templateId)
    {
        $siteId = CSite::GetDefSite();
        
        $obSite = new CSite;
        $arSite = $obSite->GetByID($siteId)->Fetch();
        
        $arTemplates = [];
        
        // Добавляем наш шаблон для всего сайта
        $arTemplates[] = [
            'TEMPLATE' => $templateId,
            'CONDITION' => '',
            'SORT' => 150
        ];
        
        // Добавляем существующие шаблоны с более низким приоритетом
        if (!empty($arSite['TEMPLATE'])) {
            foreach ($arSite['TEMPLATE'] as $arTemplate) {
                if ($arTemplate['TEMPLATE'] != $templateId) {
                    $arTemplate['SORT'] = 500;
                    $arTemplates[] = $arTemplate;
                }
            }
        }
        
        $obSite->Update($siteId, ['TEMPLATE' => $arTemplates]);
    }

    protected function CopyAndUpdateIncludeAreas()
    {
        $wizard = $this->GetWizard();
        
        $includeSource = __DIR__ . '/../../files/templates/gruzprofi/include_areas';
        $includeDest = $_SERVER['DOCUMENT_ROOT'] . '/local/include';
        
        if (!is_dir($includeDest)) {
            mkdir($includeDest, 0755, true);
        }
        
        // Копируем все файлы
        CopyDirFiles($includeSource, $includeDest, true, true);
        
        // Обновляем значения из мастера
    $updates = [
        'phone.php' => $wizard->GetVar('PHONE'),
        'phone_link.php' => 'tel:' . preg_replace('/[^0-9+]/', '', $wizard->GetVar('PHONE')),
        'work_hours.php' => $wizard->GetVar('WORK_HOURS'),
        'footer_phone.php' => $wizard->GetVar('PHONE'),
        'footer_email.php' => $wizard->GetVar('EMAIL'),
        'footer_work_hours.php' => $wizard->GetVar('WORK_HOURS'),
        'footer_company_name.php' => $wizard->GetVar('COMPANY_NAME'),
        'footer_inn.php' => $wizard->GetVar('INN'),
        'footer_ogrn.php' => $wizard->GetVar('OGRN'),
        'telegram.php' => $this->generateTelegramContent($wizard->GetVar('TELEGRAM_LINK')),
    ];
        
        foreach ($updates as $file => $content) {
            $filePath = $includeDest . '/' . $file;
            if (file_exists($filePath)) {
                $fileContent = file_get_contents($filePath);
                
                // Находим позицию после проверки B_PROLOG_INCLUDED
                if (preg_match('/<\?if\(!defined\("B_PROLOG_INCLUDED"\) \|\| B_PROLOG_INCLUDED!==true\)die\(\);\?>/', $fileContent, $matches)) {
                    $prefix = $matches[0];
                    $newContent = $prefix . $content;
                } else {
                    $newContent = '<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>' . $content;
                }
                
                file_put_contents($filePath, $newContent);
            }
        }
    }
    
    protected function generateTelegramContent($link)
    {
        return '
<a href="' . htmlspecialchars($link) . '" target="_blank" rel="noopener" class="fab" aria-label="Написать в Telegram">
    <svg viewBox="0 0 24 24" width="28" height="28">
        <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.94z"/>
    </svg>
</a>';
    }

protected function CreatePages($iblocks)
{
    $siteId = CSite::GetDefSite();
    $wizard = $this->GetWizard();
    $email = $wizard->GetVar('EMAIL') ?: 'info@gruzprofi.ru';
    
    // Главная страница
    $indexPath = $_SERVER['DOCUMENT_ROOT'] . '/index.php';
    $backupPath = $_SERVER['DOCUMENT_ROOT'] . '/index_backup_' . date('Ymd_His') . '.php';
    
    // Проверяем, существует ли index.php и не пустой ли он
    if (file_exists($indexPath) && filesize($indexPath) > 0) {
        // Читаем существующий файл
        $existingContent = file_get_contents($indexPath);
        
        // Проверяем, не создан ли он уже нашим модулем
        if (strpos($existingContent, 'Грузоперевозки и переезды с фиксированной ценой') === false) {
            // Это чужой index.php - делаем бэкап
            copy($indexPath, $backupPath);
            
            // Записываем в лог
            $wizard->SetVar('INDEX_BACKUP', $backupPath);
        }
    }
    
    // Создаём новый index.php
    $indexContent = $this->generateIndexPage($iblocks);
    file_put_contents($indexPath, $indexContent);
    
    // Страница политики конфиденциальности
    $privacyDir = $_SERVER['DOCUMENT_ROOT'] . '/privacy';
    $privacyPath = $privacyDir . '/index.php';
    
    if (!is_dir($privacyDir)) {
        mkdir($privacyDir, 0755, true);
    }
    
    // Проверяем существование privacy
    if (file_exists($privacyPath) && filesize($privacyPath) > 0) {
        $existingPrivacy = file_get_contents($privacyPath);
        if (strpos($existingPrivacy, 'Политика конфиденциальности') === false) {
            // Бэкапим чужую страницу
            copy($privacyPath, $privacyDir . '/index_backup_' . date('Ymd_His') . '.php');
        }
    }
    
    $privacyContent = $this->generatePrivacyPage($email);
    file_put_contents($privacyPath, $privacyContent);
    
    // Устанавливаем шаблон для главной
    $this->SetTemplateForPage('/', 'gruzprofi');
}
    
    protected function generateIndexPage($iblocks)
{
    $heroIblock = $iblocks['HERO_CONTENT'] ?? 1;
    $fleetIblock = $iblocks['FLEET'] ?? 1;
    $featuresIblock = $iblocks['FEATURES'] ?? 1;
    $calcIblock = $iblocks['CALC_TARIFFS'] ?? 1;
    $reviewsIblock = $iblocks['REVIEWS'] ?? 1;
    $b2bIblock = $iblocks['B2B_BANNER'] ?? 1;
    
    $apiKey = $this->GetWizard()->GetVar('YANDEX_MAPS_API_KEY');
    $apiKeyParam = $apiKey ? '?apikey=' . htmlspecialchars($apiKey) . '&lang=ru_RU' : '?lang=ru_RU';
    
    return '<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Грузоперевозки и переезды с фиксированной ценой");

if (!CModule::IncludeModule("iblock")) {
    echo "Модуль инфоблоков не установлен!";
}
?>
<script>
window.CARS_ONLINE = <?= json_encode([
    ["coords" => [55.76, 37.64], "title" => "Грузовик ГАЗель"],
    ["coords" => [55.75, 37.62], "title" => "Фургон Mercedes"],
    ["coords" => [55.78, 37.65], "title" => "Грузовик Hyundai"],
]) ?>;
</script>

<!-- Первый экран -->
<section class="hero">
    <div class="container hero__grid">
        <div class="hero__content">
            <?$APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "hero_content",
                [
                    "IBLOCK_TYPE" => "gruzprofi_content",
                    "IBLOCK_CODE" => "HERO_CONTENT",
                    "NEWS_COUNT" => "1",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "36000",
                ]
            );?>
        </div>
        
        <?$APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "calculator_template",
            [
                "IBLOCK_TYPE" => "gruzprofi_content",
                "IBLOCK_CODE" => "CALC_TARIFFS",
                "NEWS_COUNT" => "20",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000",
            ]
        );?>
    </div>
</section>

<!-- Преимущества -->
<section class="section-light">
    <div class="container">
        <h2><?$APPLICATION->IncludeFile("/local/include/features_title.php", [], ["MODE" => "text"])?></h2>
        <?$APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "features_grid",
            [
                "IBLOCK_TYPE" => "gruzprofi_content",
                "IBLOCK_CODE" => "FEATURES",
                "NEWS_COUNT" => "20",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000",
            ]
        );?>
    </div>
</section>

<!-- Автопарк -->
<section>
    <div class="container">
        <h2><?$APPLICATION->IncludeFile("/local/include/fleet_title.php", [], ["MODE" => "text"])?></h2>
        <?$APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "fleet_grid",
            [
                "IBLOCK_TYPE" => "gruzprofi_content",
                "IBLOCK_CODE" => "FLEET",
                "NEWS_COUNT" => "20",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000",
            ]
        );?>
    </div>
</section>

<!-- B2B Баннер -->
<section class="section-light">
    <div class="container">
        <?$APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "b2b_banner",
            [
                "IBLOCK_TYPE" => "gruzprofi_content",
                "IBLOCK_CODE" => "B2B_BANNER",
                "NEWS_COUNT" => "1",
                "CACHE_TYPE" => "N",
            ]
        );?>
    </div>
</section>

<!-- Карта -->
<section>
    <div class="container">
        <h2><?$APPLICATION->IncludeFile("/local/include/map_title.php", [], ["MODE" => "text"])?></h2>
        <div class="map-wrapper" style="position:relative;">
            <div class="map-ui" style="position:absolute; top:20px; right:20px; background:#fff; padding:10px 20px; border-radius:12px; z-index:10;">
                <strong>Машины в вашем районе</strong>
                <span style="margin-left:10px;">Оперативная подача</span>
            </div>
            <div id="yandex-map" style="width:100%; height:450px; border-radius:24px; overflow:hidden;"></div>
        </div>
    </div>
</section>

<script src="https://api-maps.yandex.ru/2.1/' . $apiKeyParam . '"></script>
<script>
function initMap() {
    if (typeof ymaps === "undefined") return setTimeout(initMap, 500);
    
    ymaps.ready(function() {
        var map = new ymaps.Map("yandex-map", {
            center: [55.76, 37.64],
            zoom: 11,
            controls: ["zoomControl", "fullscreenControl"]
        });
        
        var carsOnline = window.CARS_ONLINE;
        if (carsOnline && carsOnline.length) {
            carsOnline.forEach(function(car) {
                var placemark = new ymaps.Placemark(car.coords, {balloonContent: car.title}, {preset: "islands#blueCarIcon"});
                map.geoObjects.add(placemark);
            });
        }
    });
}
document.addEventListener("DOMContentLoaded", initMap);
</script>

<!-- Отзывы -->
<section class="section-light">
    <div class="container">
        <h2><?$APPLICATION->IncludeFile("/local/include/reviews_title.php", [], ["MODE" => "text"])?></h2>
        <?$APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "reviews_list",
            [
                "IBLOCK_TYPE" => "gruzprofi_content",
                "IBLOCK_CODE" => "REVIEWS",
                "NEWS_COUNT" => "20",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000",
            ]
        );?>
    </div>
</section>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>';
}
    
protected function generatePrivacyPage($email = 'info@gruzprofi.ru')
{
    return '<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Политика конфиденциальности");
?>

<div class="container" style="padding: 60px 20px; max-width: 900px; margin: 0 auto;">
    <h1>Политика конфиденциальности</h1>
    
    <h2>1. Общие положения</h2>
    <p>Настоящая политика обработки персональных данных составлена в соответствии с требованиями Федерального закона от 27.07.2006. №152-ФЗ «О персональных данных» и определяет порядок обработки персональных данных и меры по обеспечению безопасности персональных данных.</p>
    
    <h2>2. Основные понятия</h2>
    <p><strong>Автоматизированная обработка персональных данных</strong> — обработка персональных данных с помощью средств вычислительной техники.</p>
    <p><strong>Блокирование персональных данных</strong> — временное прекращение обработки персональных данных.</p>
    
    <h2>3. Оператор может обрабатывать следующие персональные данные Пользователя</h2>
    <ul>
        <li>Фамилия, имя, отчество</li>
        <li>Номер телефона</li>
        <li>Адрес электронной почты</li>
    </ul>
    
    <h2>4. Цели обработки персональных данных</h2>
    <p>Цель обработки персональных данных Пользователя — информирование Пользователя посредством отправки электронных писем; заключение, исполнение и прекращение гражданско-правовых договоров.</p>
    
    <h2>5. Контакты</h2>
    <p>Все предложения или вопросы по настоящей Политике конфиденциальности следует сообщать на email: ' . htmlspecialchars($email) . '</p>
    
    <p><a href="/">← Вернуться на главную</a></p>
</div>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>';
}
}

class Step4 extends CWizardStep
{
    public function InitStep()
    {
        $this->SetStepID("FinishStep");
        $this->SetTitle("Установка завершена");
        $this->SetCancelStep("FinishStep");
    }

public function ShowStep()
{
    $wizard = $this->GetWizard();
    $backupPath = $wizard->GetVar('INDEX_BACKUP');
    
    $this->content = '<div style="padding:30px; text-align:center;">';
    $this->content .= '<h2 style="color:#2c3e66;">🎉 Поздравляем!</h2>';
    $this->content .= '<p style="font-size:1.2em;">Сайт грузоперевозок "ГрузПрофи" успешно установлен.</p>';
    
    if ($backupPath) {
        $this->content .= '<p style="color:#e67e22; background:#fef5e7; padding:15px; border-radius:8px; margin:20px 0;">';
        $this->content .= '⚠️ Внимание: существующий файл index.php был заменён.<br>';
        $this->content .= 'Резервная копия сохранена: <br><code>' . htmlspecialchars($backupPath) . '</code>';
        $this->content .= '</p>';
    }
    
    $this->content .= '<br>';
    $this->content .= '<p><a href="/" class="adm-btn adm-btn-save" style="padding:12px 30px; font-size:1.1em;">Перейти на сайт</a></p>';
    $this->content .= '<br>';
    $this->content .= '<p style="color:#888; font-size:0.9em;">Для редактирования контента используйте административную панель Битрикс.</p>';
    $this->content .= '<p style="color:#888; font-size:0.9em;">Инфоблоки находятся в разделе "Контент" → "Контент ГрузПрофи".</p>';
    $this->content .= '</div>';
}
}

// Регистрируем шаги
$wizard = new CWizard("a_a:gruzprofi");
$wizard->AddStep(new Step1);
$wizard->AddStep(new Step2);
$wizard->AddStep(new Step3);
$wizard->AddStep(new Step4);

$wizard->SetTemplate(new CWizardTemplate);
$wizard->Run();
?>