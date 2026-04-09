<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Получаем SEO свойства страницы
$pageTitle = $APPLICATION->GetTitle();
$pageDescription = $APPLICATION->GetProperty("description");
$pageKeywords = $APPLICATION->GetProperty("keywords");
$ogImage = $APPLICATION->GetProperty("og_image") ?: "/local/templates/gruzprofi/images/og-image.jpg";
$siteName = "ГрузПрофи";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO мета-теги -->
    <title><?php $APPLICATION->ShowTitle(); ?></title>
    <?php $APPLICATION->ShowMeta("description") ?>
    <?php $APPLICATION->ShowMeta("keywords") ?>
    <?php $APPLICATION->ShowMeta("robots") ?>
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?: $siteName) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription ?: "Профессиональные грузоперевозки и переезды с фиксированной ценой") ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= "https://" . $_SERVER["HTTP_HOST"] . $APPLICATION->GetCurPage() ?>">
    <meta property="og:image" content="<?= "https://" . $_SERVER["HTTP_HOST"] . $ogImage ?>">
    <meta property="og:site_name" content="<?= $siteName ?>">
    <meta property="og:locale" content="ru_RU">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle ?: $siteName) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($pageDescription ?: "Профессиональные грузоперевозки и переезды с фиксированной ценой") ?>">
    <meta name="twitter:image" content="<?= "https://" . $_SERVER["HTTP_HOST"] . $ogImage ?>">
    
    <!-- Canonical -->
    <link rel="canonical" href="<?= "https://" . $_SERVER["HTTP_HOST"] . $APPLICATION->GetCurPage() ?>">
    
    <?php $APPLICATION->ShowHead(); ?>
    
    <style>
        /* Убираем синий цвет у ссылок */
        .header a, .phone {
            color: inherit;
            text-decoration: none;
        }
        /* Стили для логотипа */
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .logo span {
            color: #FFD500;
        }
    </style>
</head>
<body>

<div id="panel"><?php $APPLICATION->ShowPanel(); ?></div>

<header class="header">
    <div class="container header__inner">
        <!-- Логотип - целиком включаемая область -->
        <?$APPLICATION->IncludeFile(
            "/local/include/logo.php",
            array(),
            array(
                "MODE" => "html",
                "SHOW_BORDER" => true,
                "NAME" => "Логотип"
            )
        );?>
        
        <div class="header__info">
            <div class="work-time">
                Работаем 
                <?$APPLICATION->IncludeFile(
                    "/local/include/work_hours.php",
                    array(),
                    array(
                        "MODE" => "text",
                        "SHOW_BORDER" => true,
                        "NAME" => "Часы работы"
                    )
                );?>
            </div>
            <div class="contact-block">
                <?$APPLICATION->IncludeFile(
                    "/local/include/phone.php",
                    array(),
                    array(
                        "MODE" => "html",
                        "SHOW_BORDER" => true,
                        "NAME" => "Телефон"
                    )
                );?>
                <a href="#calc" class="callback-link">Заказать звонок</a>
            </div>
            <!-- Telegram кнопка убрана отсюда, оставлена только в footer -->
        </div>
    </div>
</header>

<main>