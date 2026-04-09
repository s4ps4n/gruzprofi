<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (empty($arResult["ITEMS"])) return;
?>
<div class="fleet-grid">
<?php foreach ($arResult["ITEMS"] as $arItem):
    $isHit    = ($arItem["PROPERTIES"]["IS_HIT"]["VALUE"] == "Y");
    $capacity = $arItem["PROPERTIES"]["CAPACITY"]["VALUE"];
    $length   = $arItem["PROPERTIES"]["BODY_LENGTH"]["VALUE"];
    $volume   = $arItem["PROPERTIES"]["BODY_VOLUME"]["VALUE"];
    $bestFor  = $arItem["PROPERTIES"]["BEST_FOR"]["VALUE"];
    $price    = (int)$arItem["PROPERTIES"]["PRICE_FROM"]["VALUE"];
    $imgSrc   = $arItem["PREVIEW_PICTURE"] ? CFile::GetPath($arItem["PREVIEW_PICTURE"]) : "";
?>
    <div class="fleet-card">
        <div class="fleet-img">
            <?php if ($imgSrc): ?>
                <img src="<?= htmlspecialchars($imgSrc) ?>"
                     alt="<?= htmlspecialchars($arItem["NAME"]) ?>"
                     loading="lazy" width="400" height="200">
            <?php else: ?>
                🚚
            <?php endif; ?>
        </div>
        <div class="fleet-info">
            <?php if ($isHit): ?>
                <span class="fleet-badge">Хит заказа</span>
            <?php endif; ?>
            <div class="fleet-title"><?= htmlspecialchars($arItem["NAME"]) ?></div>
            <ul class="fleet-specs">
                <?php if ($capacity): ?>
                    <li><span>Грузоподъемность:</span><span><?= htmlspecialchars($capacity) ?></span></li>
                <?php endif; ?>
                <?php if ($length): ?>
                    <li><span>Длина кузова:</span><span><?= htmlspecialchars($length) ?></span></li>
                <?php endif; ?>
                <?php if ($volume): ?>
                    <li><span>Объем кузова:</span><span><?= htmlspecialchars($volume) ?></span></li>
                <?php endif; ?>
                <?php if ($bestFor): ?>
                    <li><span>Идеально для:</span><span><?= htmlspecialchars($bestFor) ?></span></li>
                <?php endif; ?>
            </ul>
            <div class="fleet-price">от <?= number_format($price, 0, "", " ") ?> ₽/час</div>
            <button class="btn <?= $isHit ? 'btn-primary' : 'btn-outline' ?>" style="width:100%"
                    onclick="document.getElementById('calc').scrollIntoView({behavior:'smooth'})">
                <?= $isHit ? "Хит - заказать" : "Выбрать машину" ?>
            </button>
        </div>
    </div>
<?php endforeach; ?>
</div>
