# Конфигурационные файлы проекта

## Общая структура конфигурации

Проект использует иерархическую систему конфигурации на основе Yii2:

```
common/
├── config/
│   ├── main.php          # Общие компоненты и настройки
│   ├── params.php        # Общие параметры
│   ├── bootstrap.php     # Бутстрап приложения
│
console/
├── config/
│   ├── main.php          # Конфигурация консольного приложения
│   ├── params.php        # Параметры консольного приложения
│
backend/
├── config/
│   ├── main.php          # Конфигурация бэкенд приложения
│   ├── params.php        # Параметры бэкенд приложения
│   ├── urlManager.php    # Настройки URL для бэкенда
│
frontend/
├── config/
│   ├── main.php          # Конфигурация фронтенд приложения
│   ├── params.php        # Параметры фронтенд приложения
│   ├── urlManager.php    # Настройки URL для фронтенда
```

## Основные компоненты конфигурации

### Common Configuration (common/config/main.php)

Основные компоненты:
- `aliases` - Псевдонимы путей
- `vendorPath` - Путь к vendor директории
- `bootstrap` - Бутстрап компоненты
- `components`:
  - `cache` - Компонент кэширования (FileCache)
  - `authManager` - Компонент управления доступом (RBAC)

Параметры (common/config/params.php):
- `adminEmail` - Email администратора
- `supportEmail` - Email поддержки
- `user.passwordResetTokenExpire` - Время жизни токена сброса пароля
- `frontendHostInfo` - URL фронтенд приложения
- `backendHostInfo` - URL бэкенд приложения

### Console Configuration (console/config/main.php)

Контроллеры:
- `fixture` - Контроллер фикстур
- `migrate` - Контроллер миграций (с использованием fishvision/migrate)

Компоненты:
- `log` - Компонент логгирования
- `backendUrlManager` - URL менеджер бэкенда
- `frontendUrlManager` - URL менеджер фронтенда

### Backend Configuration (backend/config/main.php)

Модули:
- `gridview` - Модуль Kartik GridView
- `gii` - Модуль Gii с кастомными генераторами:
  - `migrik` - Генератор структуры миграций
  - `migrikdata` - Генератор данных миграций
  - `model` - Генератор моделей
  - `crud` - Генератор CRUD

Компоненты:
- `project` - Сервис проекта
- `view` - Компонент представлений
- `i18n` - Интернационализация
- `request` - Компонент запросов
- `user` - Компонент пользователя
- `session` - Компонент сессий
- `log` - Компонент логгирования
- `errorHandler` - Обработчик ошибок
- `backendUrlManager` - URL менеджер бэкенда
- `frontendUrlManager` - URL менеджер фронтенда

### Frontend Configuration (frontend/config/main.php)

Компоненты:
- `i18n` - Интернационализация
- `assetManager` - Менеджер ассетов
- `request` - Компонент запросов
- `user` - Компонент пользователя
- `session` - Компонент сессий
- `log` - Компонент логгирования
- `errorHandler` - Обработчик ошибок
- `backendUrlManager` - URL менеджер бэкенда
- `frontendUrlManager` - URL менеджер фронтенда

## Bootstrap процесс

Файл `common/bootstrap/SetUp.php` содержит логику инициализации приложения:
- Установка темы оформления из переменной окружения SKIN
- Регистрация MailerInterface
- Регистрация ContactService
- Регистрация ManagerInterface (RBAC)
- Переопределение виджетов Menu и Nav