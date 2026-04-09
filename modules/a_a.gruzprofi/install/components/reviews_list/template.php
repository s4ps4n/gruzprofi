<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (empty($arResult["ITEMS"])) return;
$stars = ["1" => "★", "2" => "★★", "3" => "★★★", "4" => "★★★★", "5" => "★★★★★"];
?>
<div class="reviews-list" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:30px;">
<?php foreach ($arResult["ITEMS"] as $arItem):
    $rating = $arItem["PROPERTIES"]["RATING"]["VALUE"] ?: "5";
    $source = $arItem["PROPERTIES"]["SOURCE"]["VALUE"];
    $imgSrc = $arItem["PREVIEW_PICTURE"] ? CFile::GetPath($arItem["PREVIEW_PICTURE"]) : "";
?>
    <div class="feature-card">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;">
            <?php if ($imgSrc): ?>
                <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($arItem["NAME"]) ?>"
                     style="width:52px;height:52px;border-radius:50%;object-fit:cover;">
            <?php else: ?>
                <div style="width:52px;height:52px;border-radius:50%;background:#EAECEF;display:flex;align-items:center;justify-content:center;font-size:24px;">👤</div>
            <?php endif; ?>
            <div>
                <strong style="display:block;"><?= htmlspecialchars($arItem["NAME"]) ?></strong>
                <span style="color:#FFB800;font-size:1.1rem;"><?= $stars[$rating] ?? "★★★★★" ?></span>
                <?php if ($source): ?>
                    <span style="font-size:0.8rem;color:#888;margin-left:6px;"><?= htmlspecialchars($source) ?></span>
                <?php endif; ?>
            </div>
        </div>
        <p style="color:#555;font-size:0.95rem;"><?= htmlspecialchars($arItem["DETAIL_TEXT"] ?: $arItem["PREVIEW_TEXT"]) ?></p>
    </div>
<?php endforeach; ?>
</div>
