<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>
<form action="<?= $APPLICATION->GetCurPage() ?>" method="POST">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="lang" value="<?= LANG ?>">
    <input type="hidden" name="id" value="a_a.gruzprofi">
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="2">
    
    <div style="padding: 20px; background: #fff; border-radius: 8px;">
        <h2>Установка решения "ГрузПрофи"</h2>
        <p>Модуль успешно установлен. Для завершения установки необходимо запустить мастер создания сайта.</p>
        <p>Мастер выполнит следующие действия:</p>
        <ul>
            <li>Создаст инфоблоки (Автопарк, Отзывы, Тарифы и др.)</li>
            <li>Импортирует демо-данные</li>
            <li>Создаст страницы сайта</li>
            <li>Настроит шаблон</li>
        </ul>
        <br>
        <input type="submit" class="adm-btn-save" value="Запустить мастер установки">
    </div>
</form>