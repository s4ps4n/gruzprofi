<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Путь к файлу настроек
$settingsFile = __DIR__ . "/settings.json";

// Функция для чтения настроек
function getSettings($file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        return json_decode($content, true) ?: [];
    }
    return [];
}

// Функция для сохранения настроек
function saveSettings($file, $field, $value) {
    $settings = getSettings($file);
    $settings[$field] = $value;
    file_put_contents($file, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    return true;
}

// Обработка сохранения
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["inline_save"]) && $APPLICATION->GetShowIncludeAreas()) {
    if (check_bitrix_sessid()) {
        $field = $_POST["field"];
        $value = $_POST["value"];
        
        $result = saveSettings($settingsFile, $field, $value);
        
        echo json_encode([
            "success" => $result,
            "field" => $field,
            "value" => $value
        ]);
        die();
    }
}
?>

<style>
.editor-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.6);
    z-index: 20001;
}

.editor-modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 20px 35px -10px rgba(0,0,0,0.2);
    z-index: 20002;
    width: 450px;
    max-width: 90%;
}

.editor-modal h3 {
    margin: 0 0 16px 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e66;
}

.editor-modal textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    margin: 10px 0;
    resize: vertical;
}

.editor-modal textarea:focus {
    outline: none;
    border-color: #FF6B35;
}

.modal-buttons {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 20px;
}

.modal-btn {
    padding: 8px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-btn-cancel {
    background: #e2e8f0;
    color: #4a5568;
}

.modal-btn-cancel:hover {
    background: #cbd5e0;
}

.modal-btn-save {
    background: #FF6B35;
    color: white;
}

.modal-btn-save:hover {
    background: #e55a2a;
}
</style>

<script>
let currentEditElement = null;
let currentField = null;
let currentOriginalText = null;

function openEditor(element, field) {
    if (!element) return;
    
    currentEditElement = element;
    currentField = field;
    currentOriginalText = element.innerText || element.textContent;
    
    const overlay = document.createElement('div');
    overlay.className = 'editor-modal-overlay';
    overlay.onclick = closeEditor;
    
    const modal = document.createElement('div');
    modal.className = 'editor-modal';
    modal.innerHTML = `
        <h3>✏️ Редактирование текста</h3>
        <textarea id="editorText" rows="4">${escapeHtml(currentOriginalText.trim())}</textarea>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="closeEditor()">Отмена</button>
            <button class="modal-btn modal-btn-save" onclick="saveContent()">Сохранить</button>
        </div>
    `;
    
    document.body.appendChild(overlay);
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        const textarea = document.getElementById('editorText');
        if (textarea) {
            textarea.focus();
            textarea.select();
        }
    }, 100);
}

function escapeHtml(text) {
    return text.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function closeEditor() {
    const overlay = document.querySelector('.editor-modal-overlay');
    const modal = document.querySelector('.editor-modal');
    if (overlay) overlay.remove();
    if (modal) modal.remove();
    document.body.style.overflow = '';
    currentEditElement = null;
    currentField = null;
}

function saveContent() {
    const newValue = document.getElementById('editorText').value;
    
    if (newValue === currentOriginalText) {
        closeEditor();
        return;
    }
    
    const saveBtn = document.querySelector('.modal-btn-save');
    const originalText = saveBtn.innerText;
    saveBtn.innerText = 'Сохранение...';
    saveBtn.disabled = true;
    
    fetch(window.location.href, {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'inline_save': '1',
            'field': currentField,
            'value': newValue,
            'sessid': '<?= bitrix_sessid() ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (currentEditElement) {
                currentEditElement.innerText = newValue;
            }
            closeEditor();
            alert('Сохранено! Текст обновлен');
        } else {
            throw new Error('Ошибка сохранения');
        }
    })
    .catch(error => {
        saveBtn.innerText = originalText;
        saveBtn.disabled = false;
        alert('Не удалось сохранить изменения');
    });
}
</script>