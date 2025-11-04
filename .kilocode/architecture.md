# Архитектурные принципы проекта

## Общая архитектура

Проект следует Domain-Driven Design (DDD) подходу с четким разделением на слои:

```
core/
├── entities/          # Сущности предметной области (Domain Model)
├── repositories/      # Репозитории для работы с данными
├── readModels/       # Модели для чтения данных
├── services/         # Сервисы предметной области
├── forms/            # Формы и валидация
├── helpers/          # Вспомогательные классы
├── traits/           # Трейты
├── behaviors/        # Поведения
├── widgets/          # Виджеты
├── validators/       # Валидаторы
├── rules/            # Правила доступа
├── dispatchers/      # Диспетчеры событий
├── dto/              # Объекты передачи данных
├── interfaces/       # Интерфейсы
└── access/           # Компоненты доступа

common/
├── bootstrap/        # Инициализация приложения
├── components/       # Общие компоненты
├── auth/             # Аутентификация
└── widgets/          # Общие виджеты

console/
├── controllers/      # Консольные контроллеры
├── models/           # Консольные модели
└── migrations/       # Миграции базы данных

backend/
├── controllers/      # Бэкенд контроллеры
├── views/            # Представления
└── widgets/          # Бэкенд виджеты

frontend/
├── controllers/      # Фронтенд контроллеры
├── views/            # Представления
└── widgets/          # Фронтенд виджеты
```

## Принципы Domain-Driven Design

### Entities (Сущности)
- Расположены в `core/entities/`
- Представляют объекты предметной области с уникальной идентичностью
- Содержат бизнес-логику и правила
- Используют трейты для расширения функциональности

### Repositories (Репозитории)
- Расположены в `core/repositories/`
- Отвечают за сохранение и извлечение сущностей
- Инкапсулируют логику доступа к данным
- Не содержат бизнес-логики

### Read Models (Модели чтения)
- Расположены в `core/readModels/`
- Оптимизированы для чтения данных
- Могут отличаться от сущностей по структуре
- Используются для отображения в интерфейсе

### Services (Сервисы)
- Расположены в `core/services/`
- Содержат бизнес-логику, которая не относится конкретной сущности
- Координируют работу между сущностями и репозиториями
- Являются точкой входа для использования домена

## Архитектурные паттерны

### Service Layer
Все операции с доменом происходят через сервисы:
```php
// Пример использования сервиса
$userService = new UserService($userRepository, $roleManager, $transactionManager);
$user = $userService->create($userForm);
```

### Repository Pattern
Репозитории инкапсулируют логику доступа к данным:
```php
// Пример использования репозитория
$userRepository = new UserRepository();
$user = $userRepository->get($id);
```

### Form Model Pattern
Формы используются для валидации входных данных:
```php
// Пример использования формы
$userForm = new UserForm();
if ($userForm->load(Yii::$app->request->post()) && $userForm->validate()) {
    // Данные валидны
}
```

### Dependency Injection
Используется контейнер зависимостей Yii2:
```php
// Пример регистрации зависимости
Yii::$container->setSingleton(ContactService::class, [], [
    $app->params['adminEmail']
]);
```

## Принципы разделения слоев

1. **Presentation Layer** (Представление)
   - Контроллеры (backend/controllers/, frontend/controllers/)
   - Представления (backend/views/, frontend/views/)
   - Виджеты (core/widgets/, backend/widgets/, frontend/widgets/)

2. **Application Layer** (Приложение)
   - Сервисы (core/services/)
   - Формы (core/forms/)

3. **Domain Layer** (Домен)
   - Сущности (core/entities/)
   - Репозитории (core/repositories/)
   - Модели чтения (core/readModels/)

4. **Infrastructure Layer** (Инфраструктура)
   - Конфигурации (common/config/, console/config/, backend/config/, frontend/config/)
   - Миграции (console/migrations/)
   - Компоненты (common/components/)

## Принципы проектирования

### SOLID
- **Single Responsibility Principle** - Каждый класс имеет одну ответственность
- **Open/Closed Principle** - Классы открыты для расширения, но закрыты для модификации
- **Liskov Substitution Principle** - Объекты могут быть заменены их подтипами
- **Interface Segregation Principle** - Интерфейсы разделены по функциональности
- **Dependency Inversion Principle** - Зависимости направлены на абстракции

### DRY (Don't Repeat Yourself)
- Переиспользование кода через трейты, хелперы и базовые классы
- Единая точка изменения для конфигураций

### KISS (Keep It Simple, Stupid)
- Простые и понятные решения
- Минимизация сложности

### YAGNI (You Aren't Gonna Need It)
- Реализация только необходимого функционала
- Избегание преждевременной оптимизации