# TODO — Чеклист перед загрузкой на маркетплейс 1С-Битрикс

> Аудит выполнен: 2026-04-09  
> Модуль: `a_a.gruzprofi` v1.0.0  
> Метод: системный анализ всех файлов проекта  

---

## КРИТИЧЕСКИЕ — блокируют запуск модуля

### 1. Неверное имя класса в `install/index.php:7`
**Проблема:** Класс называется `mycompany_gruzprofi`. По конвенции Битрикс имя класса должно совпадать с ID модуля, где точки заменяются на подчёркивания.  
**Должно быть:** `a_a_gruzprofi extends CModule`  
**Файл:** `modules/a_a.gruzprofi/install/index.php:7`

---

### 2. Неверный ID модуля в форме `install/step1.php:11`
**Проблема:** `<input type="hidden" name="id" value="mycompany.gruzprofi">` — скрытое поле передаёт неверный ID. Битрикс не найдёт нужный модуль для установки.  
**Должно быть:** `value="a_a.gruzprofi"`  
**Файл:** `modules/a_a.gruzprofi/install/step1.php:11`

---

### 3. Неверный `wizardName` — мастер не запустится
**Проблема:** `LocalRedirect(".../wizard_install.php?...&wizardName=mycompany:gruzprofi...")`. Битрикс ищет файл по пути `wizards/mycompany/wizard.php`, но файл находится в `wizards/a_a/wizard.php`.  
**Должно быть:** `wizardName=a_a:gruzprofi`  
**Файл:** `modules/a_a.gruzprofi/install/index.php:83`

---

### 4. Неверный ID в конструкторе `CWizard`
**Проблема:** `new CWizard("mycompany:gruzprofi")` — не совпадает с реальным расположением файла мастера (`wizards/a_a/`).  
**Должно быть:** `new CWizard("a_a:gruzprofi")`  
**Файл:** `modules/a_a.gruzprofi/install/wizards/a_a/wizard.php:721`

---

### 5. Несуществующий путь к include_areas в `CopyAndUpdateIncludeAreas()`
**Проблема:** `$includeSource = __DIR__ . '/../../demo_data/include_areas'` — директория `demo_data/include_areas` не существует. `CopyDirFiles` не скопирует ничего. Реальные файлы include_areas находятся в `files/templates/gruzprofi/include_areas/`.  
**Должно быть:** Путь должен вести к существующей директории с include_areas шаблона.  
**Файл:** `modules/a_a.gruzprofi/install/wizards/a_a/wizard.php:420-421`

---

### 6. Дублирование FAB-кнопки Telegram на странице
**Проблема:** `telegram.php` включается дважды: в `header.php:69-77` и в `footer.php:123-127`. На готовой странице будет два одинаковых плавающих элемента (`position:fixed`).  
**Решение:** Убрать включение `telegram.php` из `header.php`, оставить только в `footer.php`.  
**Файлы:** `header.php:69-77`, `footer.php:123-127`

---

## ВЫСОКИЙ ПРИОРИТЕТ — заблокируют модерацию маркетплейса

### 7. Отсутствует иконка модуля `logo.png`
**Проблема:** Файл `install/logo.png` (или `logo.gif`) не найден. Обязательный файл для отображения иконки модуля в административной панели. Без него иконка будет пустой.  
**Решение:** Создать файл `modules/a_a.gruzprofi/install/logo.png` размером 90×90 px.

---

### 8. Отсутствует языковой файл для `install/index.php`
**Проблема:** `Loc::loadMessages(__FILE__)` вызывается в `install/index.php`, но папка `lang/` с языковыми файлами отсутствует. `Loc::getMessage("MYCOMPANY_GRUZPROFI_INSTALL_TITLE")` вернёт пустую строку — заголовок страницы установки будет пустым.  
**Решение:** Создать `modules/a_a.gruzprofi/install/lang/ru/install/index.php` с нужными ключами.

---

### 9. Яндекс.Карты без API-ключа
**Проблема:** В `generateIndexPage()` подключение карт: `<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU">` — без `apikey`. С 2023 года API 2.1 требует ключ, иначе карта показывает ошибку. В мастере установки нет поля для ввода API-ключа.  
**Решение:** Добавить шаг в мастер для ввода Yandex Maps API Key, подставлять в страницу.  
**Файл:** `modules/a_a.gruzprofi/install/wizards/a_a/wizard.php:~615`

---

### 10. `DoUninstall()` не удаляет инфоблоки и тип инфоблоков
**Проблема:** При удалении модуля в базе данных остаются: тип инфоблоков `gruzprofi_content` и все 8 созданных инфоблоков со всеми данными. Требование маркетплейса: деинсталляция должна откатывать все изменения (или предлагать пользователю выбор).  
**Файл:** `modules/a_a.gruzprofi/install/index.php:49-55`

---

### 11. Кнопка "Забронировать по этой цене" без действия
**Проблема:** `<button class="btn btn-primary calc-btn">Забронировать по этой цене</button>` не имеет ни `onclick`, ни `type="submit"`, ни обёртки в `<form>`. При клике ничего не происходит — мёртвая CTA-кнопка.  
**Решение:** Подвязать к форме заявки / Bitrix форм / открытию модального окна с контактами.  
**Файл:** `modules/a_a.gruzprofi/install/files/templates/gruzprofi/components/bitrix/news.list/calculator_template/template.php:70`

---

### 12. Две конфликтующие реализации функции `initMap()`
**Проблема:** Функция `initMap()` определена дважды с разной логикой:
- В `template_styles.js:16-39` (использует `hintContent`, иконку `islands#yellowAutoIcon`)
- В `generateIndexPage()` (строки ~617-636) (использует `balloonContent`, иконку `islands#blueCarIcon`)

Вторая перезаписывает первую. Файл `template_styles.js` подключается через `ShowHead()`, что может привести к конфликту.  
**Файлы:** `template_styles.js:16-39`, `wizard.php:~617-636`

---

### 13. Отсутствует файл `.description.php` шаблона
**Проблема:** Шаблон `gruzprofi` не содержит файла `.description.php`. Без него у шаблона нет метаданных (название, автор, описание) и он может не отображаться корректно в настройках сайта.  
**Решение:** Создать `files/templates/gruzprofi/.description.php` с полями `$arTemplate`.

---

### 14. Отсутствует скриншот шаблона
**Проблема:** В папке шаблона `files/templates/gruzprofi/` нет файла `screenshot.jpg` / `screenshot.png`. Без него в панели управления Битрикс шаблон отображается без превью.

---

### 15. Несоответствие кода инфоблока в XML и при создании
**Проблема:** В XML-файлах код указан в верхнем регистре (например, `<БитриксКод>FLEET</БитриксКод>`), а `CreateIBlock()` создаёт инфоблок с кодом `strtolower($code)` = `fleet`. При импорте через `CIBlockXMLFile` может возникнуть несоответствие, если класс привязывает импорт к коду.  
**Затронуты:** `auto.xml`, `advantages.xml`, `tarif.xml`, `contentheader.xml`, `btb.xml`, `reviews.xml`, `contacts.xml`, `services.xml`

---

## СРЕДНИЙ ПРИОРИТЕТ — функциональные и качественные проблемы

### 16. Хардкодированная ссылка `t.me/username` в fallback баннера
**Проблема:** В `b2b_banner/template.php:19` ссылка `https://t.me/username` прошита в шаблоне и не заменяется значением, введённым в мастере установки.  
**Файл:** `modules/a_a.gruzprofi/install/files/templates/gruzprofi/components/bitrix/news.list/b2b_banner/template.php:19`

---

### 17. `phone_link.php` не обновляется в мастере
**Проблема:** В массиве `$updates` в `CopyAndUpdateIncludeAreas()` отсутствует ключ `phone_link.php`. Файл `include_areas/phone_link.php` остаётся с дефолтным значением вместо введённого номера телефона.  
**Файл:** `modules/a_a.gruzprofi/install/wizards/a_a/wizard.php:431-441`

---

### 18. Внешняя CDN-зависимость SweetAlert2
**Проблема:** `inline_editor.php:42` подключает скрипт с `cdn.jsdelivr.net`. При отсутствии интернета (production-сервер) редактирование сломается. Рекомендация маркетплейса — не подключать внешние ресурсы.  
**Решение:** Добавить SweetAlert2 в состав модуля или использовать стандартные Битрикс-диалоги (`BX.alert`).  
**Файл:** `modules/a_a.gruzprofi/install/files/templates/gruzprofi/inline_editor.php:42`

---

### 19. Отсутствуют SEO мета-теги в `header.php`
**Проблема:** В `header.php` нет `<meta name="description">`, Open Graph тегов (`og:title`, `og:description`, `og:image`). Для лендинга грузоперевозок SEO критично. Битрикс предоставляет `$APPLICATION->ShowMeta()` — не используется.  
**Файл:** `modules/a_a.gruzprofi/install/files/templates/gruzprofi/header.php`

---

### 20. Trailing-пробел в `settings.json`
**Проблема:** `"HERO_TITLE": "Квартирные и офисные "` — значение содержит пробел в конце строки. Артефакт, который будет отображаться в заголовке хиро-секции.  
**Файл:** `modules/a_a.gruzprofi/install/files/templates/gruzprofi/settings.json:3`

---

### 21. Неверные единицы объёма кузова в `auto.xml`
**Проблема:** Объём кузова указан как `2693 (см3)`. Для грузового автомобиля типа УАЗ Еврофургон объём кузова должен быть в кубических метрах (~9 м³). `2693 см³` — это объём двигателя, не кузова.  
**Файл:** `modules/a_a.gruzprofi/install/demo_data/auto.xml:339`

---

### 22. Некорректное имя элемента в `reviews.xml`
**Проблема:** `<Наименование>Лучшие</Наименование>` — это не имя рецензента. В шаблоне `reviews_list/template.php` поле `NAME` выводится как имя человека. Демо-данные некорректны.  
**Файл:** `modules/a_a.gruzprofi/install/demo_data/reviews.xml:169`

---

### 23. Недостаточно демо-данных для визуализации сеток
**Проблема:**
- `auto.xml` — 1 позиция (нужно минимум 3 для демонстрации `fleet-grid`)
- `reviews.xml` — 1 позиция (нужно минимум 3 для демонстрации `reviews-list`)  
Покупатель в маркетплейсе не сможет оценить внешний вид из демо.

---

### 24. Перезапись корневого `index.php` без предупреждения
**Проблема:** `file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/index.php', ...)` в `CreatePages()` перезаписывает главную страницу сайта без запроса подтверждения. Если у клиента уже есть рабочий сайт — данные будут уничтожены.  
**Файл:** `modules/a_a.gruzprofi/install/wizards/a_a/wizard.php:477`

---

### 25. Нестандартный порядок в `DoInstall()`
**Проблема:** `ModuleManager::registerModule()` вызывается до `InstallFiles()`. Стандартная практика Битрикс: сначала устанавливаются файлы, потом регистрируется модуль (чтобы при ошибке копирования модуль не числился установленным).  
**Файл:** `modules/a_a.gruzprofi/install/index.php:32-35`

---

### 26. `inline_editor.php` не проверяет права администратора
**Проблема:** Обработчик POST проверяет только `$APPLICATION->GetShowIncludeAreas()`, но не `$USER->IsAdmin()`. Любой авторизованный пользователь с доступом к `SHOW_INCLUDE_AREAS` сможет изменять `settings.json`.  
**Файл:** `modules/a_a.gruzprofi/install/files/templates/gruzprofi/inline_editor.php:25`

---

### 27. `demo_data/pages/index.php` — пустой файл
**Проблема:** Файл существует, но содержит 0 байт. Если wizardом не генерируется страница (ошибка), сайт будет показывать пустую страницу.  
**Файл:** `modules/a_a.gruzprofi/install/demo_data/pages/index.php`

---

### 28. Email в `privacy.php` не подставляется из мастера
**Проблема:** В `demo_data/pages/privacy.php:27` захардкожен контакт `info@gruzprofi.ru`. В шаблоне `generatePrivacyPage()` email подставляется из мастера (`$this->GetWizard()->GetVar('EMAIL')`), но `demo_data/pages/privacy.php` не используется как источник — страница генерируется из строки в PHP. Если `generatePrivacyPage()` выполнится без активного wizard-контекста, метод упадёт.  
**Файл:** `modules/a_a.gruzprofi/install/wizards/a_a/wizard.php:688`

---

## НИЗКИЙ ПРИОРИТЕТ — улучшения качества

### 29. Статические данные на карте вводят в заблуждение
**Проблема:** В `generateIndexPage()` блок карты содержит захардкоженные данные: `"14 машин свободно"`, `"Среднее время подачи: 32 минуты"`. Это статический текст, не связанный с реальными данными. Маркетплейс может расценить как вводящую в заблуждение информацию.  
**Файл:** `modules/a_a.gruzprofi/install/wizards/a_a/wizard.php:606-609`

---

### 30. `Step3` всегда отображает зелёные галочки
**Проблема:** Список успешно выполненных шагов выводится в начале `ShowStep()`, до реального выполнения `InstallData()`. Если часть шагов не выполнится (не выброшен Exception), пользователь всё равно увидит "✓ Инфоблок создан".  
**Файл:** `modules/a_a.gruzprofi/install/wizards/a_a/wizard.php:152-166`

---

### 31. Мёртвый файл `logo_text.php` в include_areas
**Проблема:** Файл `include_areas/logo_text.php` нигде не подключается — ни в `header.php`, ни в `footer.php`. Используется только `logo.php`. Мёртвый файл, путающий структуру.  
**Файл:** `modules/a_a.gruzprofi/install/files/templates/gruzprofi/include_areas/logo_text.php`

---

### 32. Отсутствует `readme.html` / `description.ru.html`
**Проблема:** Рекомендуемые маркетплейсом файлы с описанием продукта, инструкцией по установке и скриншотами. Влияют на отображение страницы продукта в маркетплейсе.

---

### 33. `VERSION_DATE` требует обновления перед публикацией
**Проблема:** `"VERSION_DATE" => "2026-01-15 00:00:00"` — дата более чем на 3 месяца отстаёт от даты аудита. При публикации в маркетплейс дату необходимо обновить до актуальной.  
**Файлы:** `modules/a_a.gruzprofi/version.php:3`, `modules/a_a.gruzprofi/install/version.php:3`

---

## Итоговая сводка

| Уровень | Количество |
|---|---|
| Критические (блокируют запуск) | 6 |
| Высокий приоритет (блокируют модерацию) | 9 |
| Средний приоритет (функциональные проблемы) | 13 |
| Низкий приоритет (качество/чистота) | 5 |
| **ИТОГО** | **33** |

---

*Аудит проведён без внесения правок в код. Все замечания носят информационный характер.*
