# Структура проекта

## Общая структура

```
.
├── .osp/                    # Конфигурация OpenServer
├── api/                     # API приложение (не трогать)
├── backend/                 # Бэкенд приложение
│   ├── controllers/         # Контроллеры
│   ├── views/               # Представления
│   ├── widgets/             # Виджеты
│   ├── config/              # Конфигурации
│   └── web/                 # Веб директория
├── bin/                     # Бинарные файлы
├── common/                  # Общие компоненты
│   ├── bootstrap/           # Инициализация приложения
│   ├── components/          # Общие компоненты
│   ├── auth/                # Аутентификация
│   ├── config/              # Общие конфигурации
│   ├── fixtures/            # Фикстуры для тестов
│   ├── helpers/             # Общие хелперы
│   ├── widgets/             # Общие виджеты
│   └── codeception.yml      # Конфигурация Codeception
├── console/                 # Консольное приложение
│   ├── controllers/         # Консольные контроллеры
│   ├── migrations/          # Миграции базы данных
│   ├── models/              # Консольные модели
│   ├── config/              # Конфигурации
│   └── runtime/             # Временные файлы
├── core/                    # Ядро приложения (Domain)
│   ├── access/              # Компоненты доступа
│   ├── actions/             # Действия
│   ├── behaviors/           # Поведения
│   ├── delegate/            # Делегаты
│   ├── dispatchers/         # Диспетчеры событий
│   ├── dto/                 # Объекты передачи данных
│   ├── entities/            # Сущности предметной области
│   ├── examples/            # Примеры кода
│   ├── forms/               # Формы и валидация
│   ├── helpers/             # Хелперы
│   ├── interfaces/          # Интерфейсы
│   ├── readModels/          # Модели для чтения
│   ├── repositories/        # Репозитории
│   ├── rules/               # Правила доступа
│   ├── services/            # Сервисы
│   ├── traits/              # Трейты
│   ├── validators/          # Валидаторы
│   └── widgets/             # Виджеты
├── environments/            # Окружения
├── frontend/                # Фронтенд приложение (не трогать)
├── tests/                   # Тесты
├── themes/                  # Темы оформления
├── vendor/                  # Зависимости Composer
├── .bowerrc                # Конфигурация Bower
├── .env                    # Переменные окружения
├── .gitignore              # Игнорируемые файлы Git
├── .htaccess               # Конфигурация Apache
├── composer.json           # Зависимости Composer
├── composer.lock           # Блокировка зависимостей
├── init                    # Скрипт инициализации (Unix)
├── init.bat                # Скрипт инициализации (Windows)
├── LICENSE.md              # Лицензия
├── README.md               # Основная документация
├── requirements.php        # Проверка требований
├── yii                     # Консольный скрипт Yii (Unix)
├── yii.bat                 # Консольный скрипт Yii (Windows)
└── yii_test                # Тестовый скрипт Yii (Unix)
```

## Core директория (Домен)

### Entities (Сущности)
```
core/entities/
├── Auth/                   # Сущности аутентификации
├── Kit/                    # Сущности Kit (проекты)
├── Language/               # Сущности языков
├── Menu/                   # Сущности меню
├── User/                   # Сущности пользователей
└── BaseEntity.php          # Базовая сущность
```

### Services (Сервисы)
```
core/services/
├── auth/                   # Сервисы аутентификации
├── kit/                    # Сервисы Kit
├── language/               # Сервисы языков
├── menu/                   # Сервисы меню
├── user/                   # Сервисы пользователей
├── manage/                 # Сервисы управления
└── CodeCollectorService.php # Сервис сбора кода
```

### Repositories (Репозитории)
```
core/repositories/
├── auth/                   # Репозитории аутентификации
├── kit/                    # Репозитории Kit
├── language/               # Репозитории языков
├── menu/                   # Репозитории меню
├── user/                   # Репозитории пользователей
└── NotFoundException.php    # Исключение "Не найдено"
```

### Forms (Формы)
```
core/forms/
├── auth/                   # Формы аутентификации
├── kit/                    # Формы Kit
├── language/               # Формы языков
├── menu/                   # Формы меню
├── user/                   # Формы пользователей
├── manage/                 # Формы управления
└── CompositeForm.php        # Композитная форма
```

### Read Models (Модели чтения)
```
core/readModels/
├── auth/                   # Модели чтения аутентификации
├── kit/                    # Модели чтения Kit
├── menu/                   # Модели чтения меню
└── UserReadRepository.php   # Репозиторий чтения пользователей
```

### Другие компоненты
```
core/
├── behaviors/              # Поведения
├── dispatchers/            # Диспетчеры событий
├── dto/                    # Объекты передачи данных
├── helpers/                # Хелперы
├── interfaces/             # Интерфейсы
├── rules/                  # Правила доступа
├── traits/                 # Трейты
├── validators/             # Валидаторы
└── widgets/                # Виджеты
```

## Console директория

### Controllers (Контроллеры)
```
console/controllers/
├── CollectCodeController.php  # Сбор кода
├── ConfigController.php       # Конфигурация
├── DocController.php          # Документация
├── KitController.php          # Управление Kit
├── MenuController.php         # Управление меню
├── NamespaceController.php    # Управление неймспейсами
├── RoleController.php         # Управление ролями
├── StructureController.php    # Структура проекта
├── TableController.php        # Управление таблицами
└── YamlController.php         # Работа с YAML
```

### Migrations (Миграции)
```
console/migrations/
├── archive/                # Архивные миграции
├── m2501_000001_optimized_user_tables.php
├── m2501_0002_optimized_rbac_tables.php
├── m250101_000003_optimized_oauth_tables.php
├── m250101_000004_optimized_menu_tables.php
├── m2501_0005_optimized_language_table.php
├── m2501_0006_optimized_data_insert.php
├── README.md               # Документация по миграциям
├── setup_optimized_migrations.php
└── USAGE_INSTRUCTIONS.md    # Инструкции по использованию
```

## Backend директория

### Controllers (Контроллеры)
```
backend/controllers/
├── auth/                   # Контроллеры аутентификации
├── cabinet/                # Контроллеры кабинета
├── kit/                    # Контроллеры Kit
├── menu/                   # Контроллеры меню
├── site/                   # Основные контроллеры
└── user/                   # Контроллеры пользователей
```

### Views (Представления)
```
backend/views/
├── auth/                   # Представления аутентификации
├── cabinet/                # Представления кабинета
├── kit/                    # Представления Kit
├── menu/                   # Представления меню
├── site/                   # Основные представления
├── user/                   # Представления пользователей
└── layouts/                # Макеты страниц
```

## Common директория

### Bootstrap (Инициализация)
```
common/bootstrap/
└── SetUp.php               # Инициализация приложения
```

### Config (Конфигурации)
```
common/config/
├── main.php                # Основная конфигурация
├── params.php              # Параметры
├── bootstrap.php           # Бутстрап
└── test.php                # Конфигурация тестов
```

## Environments (Окружения)
```
environments/
├── dev/                    # Окружение разработки
├── prod/                   # Окружение production
└── index.php               # Точка входа для окружений
```

## Особенности структуры

1. **Domain-Driven Design**: Ядро приложения (core/) реализует DDD подход
2. **Разделение по слоям**: Presentation, Application, Domain, Infrastructure
3. **Модульность**: Компоненты разделены по функциональности
4. **Расширяемость**: Использование трейтов и интерфейсов для расширения функциональности
5. **Тестируемость**: Четкое разделение ответственностей облегчает тестирование

## Игнорируемые директории

Следующие директории не должны использоваться в документации для Kilo Code:
- `api/` - API приложение
- `frontend/` - Фронтенд приложение
- `kit/` - Директории внутри различных компонентов (Kit)
- `vendor/` - Зависимости Composer
- `themes/` - Темы оформления
- `tests/` - Тесты