# Документация по лайаутам фронтенда

## Общая структура лайаутов

В проекте используется система лайаутов Yii2 с различными шаблонами для разных типов страниц. Все лайауты находятся в директории `frontend/views/layouts/` и `frontend/views/layouts/partials/`.

## Основные лайауты

### 1. `main.php` - Основной лайаут
- **Используется по умолчанию** для всех контроллеров, если не указан другой лайаут
- Использует `AppAsset` или `LandingAsset` в зависимости от параметра `Yii::$app->params['layout']`
- Рендерит `_clean.php` как основной контент
- **Используется в**: большинстве страниц, кроме главной и специальных страниц

### 2. `landing.php` - Лайаут для главной страницы
- **Используется в**: `SiteController::actionIndex()` (через `$this->layout = 'landing'`)
- Рендерит `_landing.php` как основной контент
- Использует `AppAsset` или `LandingAsset` в зависимости от параметра `Yii::$app->params['layout']`

## Служебные лайауты

### 3. `_main.php` - Переключатель лайаутов
- **Файл**: `frontend/views/layouts/_main.php` (не в partials)
- Содержит switch-конструкцию для выбора лайаута на основе `Yii::$app->params['layout']`:
  - `'main'` → рендерит `/layouts/partials/_main.php` (с боковой панелью)
  - `'landing'` → рендерит `/layouts/partials/_landing.php` (без боковой панели)
  - `default` → рендерит `/layouts/_clean.php` (без боковой панели)

### 4. `_clean.php` - Чистый лайаут
- **Используется в**: `main.php`, `landing.php`, `_main.php` (default)
- Рендерит: `_header`, `_content`, `_footer` из папки `land`
- Не содержит боковой панели

## Специализированные лайауты

### 5. `col-4.php`, `col-6.php`, `col-8.php`, `col-10.php`, `col-12.php` - Колоночные лайауты
- **Используются в**: страницах аутентификации
- Создают центрированные колонки разной ширины
- **Пример использования**: `auth/login.php` использует `col-4.php`

### 6. `two-column-col-9.php` - Двухколоночный лайаут
- **Используется в**: `about.php`, `article.php`, `index.php` через `beginContent()`
- Создает макет с основной колонкой (9) и боковой панелью (3)

## Частичные лайауты (partials)

### 7. `_main.php` (в partials) - Лайаут с боковой панелью
- **Используется в**: `_main.php` (переключатель) при `params['layout'] == 'main'`
- Рендерит `_menu` (содержит `_sidenav` и `_topbar`)
- Используется для страниц с боковой навигацией

### 8. `_landing.php` (в partials) - Лайаут для лендинга
- **Используется в**: `landing.php`, `_main.php` (переключатель) при `params['layout'] == 'landing'`
- Содержит все компоненты лендинга: `_header`, `_services`, `_carousel`, `_plans`, `_cta`, `_reviews`, `_blog`, `_contact`, `_footer`

### 9. `_header.php`, `_content.php`, `_footer.php` (в land)
- **Используются в**: `_clean.php`
- Обеспечивают стандартную структуру страницы

## Специальные частичные шаблоны

### 10. `_menu.php`, `_sidenav.php`, `_topbar.php` - Навигация
- `_menu.php` рендерит `_sidenav.php` и `_topbar.php`
- Используются в `_main.php` (partials)

### 11. `_auth_brand.php`, `_copyright.php` - Элементы аутентификации
- **Используются в**: страницах аутентификации
- `_auth_brand.php` - логотип и заголовок аутентификации
- `_copyright.php` - копирайт внизу страницы

## Использование в контроллерах

### SiteController
- `actionIndex()` → `$this->layout = 'landing'` → `landing.php` → `_landing.php`
- Другие методы → `main.php` → `_clean.php` (включая `actionAuthTemplate()`)

### AuthController
- Все методы → `beginContent('@frontend/views/layouts/col-4.php')` → специализированные лайауты аутентификации

## Важные особенности

1. **Переключение лайаутов**: Используется параметр `Yii::$app->params['layout']` для выбора между разными типами лайаутов, но в текущей реализации этот параметр нигде не устанавливается, и его значение по умолчанию - `null`. Это означает, что в switch-конструкции в `_main.php` всегда используется ветка `default`, которая рендерит `_clean.php`.
2. **beginContent/endContent**: Используется для вложения вьюх во внешние лайауты
3. **Хлебные крошки**: Добавляются в каждой вьюхе через `$this->params['breadcrumbs'][]`
4. **Заголовки**: Устанавливаются через `$this->title`

## Для изучения при работе с версткой

1. Структуру папок `frontend/views/layouts/` и `frontend/views/layouts/partials/`
2. Принцип работы `beginContent()` и вложенных лайаутов
3. Использование параметра `Yii::$app->params['layout']` для переключения лайаутов
4. Структуру и содержимое частичных шаблонов (`_auth_brand.php`, `_menu.php`, и т.д.)
5. Примеры использования в существующих вьюхах (`auth/login.php`, `site/about.php`, и т.д.)
