# Общая документация проекта

## Архитектурные принципы проекта

### Общая архитектура

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

### Принципы Domain-Driven Design

#### Entities (Сущности)
- Расположены в `core/entities/`
- Представляют объекты предметной области с уникальной идентичностью
- Содержат бизнес-логику и правила
- Используют трейты для расширения функциональности

#### Repositories (Репозитории)
- Расположены в `core/repositories/`
- Отвечают за сохранение и извлечение сущностей
- Инкапсулируют логику доступа к данным
- Не содержат бизнес-логики

#### Read Models (Модели чтения)
- Расположены в `core/readModels/`
- Оптимизированы для чтения данных
- Могут отличаться от сущностей по структуре
- Используются для отображения в интерфейсе

#### Services (Сервисы)
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

## Структура проекта

### Общая структура

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

## Принципы написания кода в проекте

### Общие принципы

Проект следует строгим стандартам кодирования, основанным на PSR-12 с дополнительными требованиями:

#### Именование

1. **Классы**: Используют PascalCase
   ```php
   // Хорошо
   class UserForm extends Model
   
   // Плохо
   class user_form extends Model
   ```

2. **Методы**: Используют camelCase
   ```php
   // Хорошо
   public function getUserById($id)
   
   // Плохо
   public function get_user_by_id($id)
   ```

3. **Переменные**: Используют camelCase
   ```php
   // Хорошо
   $userName = 'John';
   
   // Плохо
   $user_name = 'John';
   ```

4. **Константы**: Используют UPPER_SNAKE_CASE
   ```php
   // Хорошо
   const STATUS_ACTIVE = 10;
   
   // Плохо
   const statusActive = 10;
   ```

#### Type Hinting

Все параметры методов и возвращаемые значения должны иметь строгую типизацию:

```php
// Хорошо
public function createUser(UserForm $form): User
{
    // ...
}

// Плохо
public function createUser($form)
{
    // ...
}
```

#### PHPDoc

Все классы, методы и свойства должны быть задокументированы:

```php
/**
 * Класс пользователя.
 * 
 * @property int $id Идентификатор пользователя
 * @property string $username Имя пользователя
 */
class User extends ActiveRecord
{
    /**
     * Создает нового пользователя.
     * 
     * @param string $username Имя пользователя
     * @param string $email Email пользователя
     * @return self
     */
    public static function create(string $username, string $email): self
    {
        // ...
    }
}
```

## Структура классов

### Entities (Сущности)

Сущности должны содержать только бизнес-логику и не зависеть от фреймворка:

```php
class User extends ActiveRecord
{
    use ModelTrait;
    use UserTrait;
    
    // Константы статусов
    public const STATUS_WAIT = 0;
    public const STATUS_ACTIVE = 10;
    
    // Поведения
    public function behaviors(): array
    {
        return [
            // ...
        ];
    }
    
    // Статические методы создания
    public static function create(string $username, string $email): self
    {
        // ...
    }
    
    // Методы бизнес-логики
    public function edit(string $username, string $email): void
    {
        // ...
    }
    
    // Правила валидации
    public function rules(): array
    {
        return [
            // ...
        ];
    }
    
    // Метки атрибутов
    public function attributeLabels(): array
    {
        return [
            // ...
        ];
    }
}
```

### Services (Сервисы)

Сервисы координируют работу между сущностями и репозиториями:

```php
class UserService
{
    use UserServiceTrait;
    
    /** @var UserRepository */
    private $users;
    
    /** @var RoleManager */
    private $roles;
    
    /** @var TransactionManager */
    private $transaction;
    
    public function __construct(
        UserRepository $users, 
        RoleManager $roles, 
        TransactionManager $transaction
    ) {
        $this->users = $users;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }
    
    public function create(UserForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->email
        );
        
        $this->transaction->wrap(function () use ($form, $user) {
            $this->users->save($user);
            $this->roles->assign($user->id, $form->role);
        });
        
        return $user;
    }
}
```

### Forms (Формы)

Формы используются для валидации входных данных:

```php
class UserForm extends Model
{
    public $username;
    public $email;
    public $role;
    
    /** @var User */
    private $user;
    
    public function __construct(User $user = null, $config = [])
    {
        if ($user) {
            $this->username = $user->username;
            $this->email = $user->email;
        }
        
        $this->user = $user;
        parent::__construct($config);
    }
    
    public function rules(): array
    {
        return [
            [['username', 'email', 'role'], 'required'],
            ['email', 'email'],
            [['username', 'email'], 'unique', 'targetClass' => User::class],
        ];
    }
}
```

### Repositories (Репозитории)

Репозитории инкапсулируют логику доступа к данным:

```php
class UserRepository
{
    use UserRepositoryTrait;
    
    public function get($id): User
    {
        if (!$user = User::findOne($id)) {
            throw new NotFoundException('User is not found.');
        }
        return $user;
    }
    
    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }
    
    public function delete(User $user): void
    {
        if (!$user->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}
```

## Обработка ошибок

### Исключения домена

Используются для бизнес-правил:

```php
class User
{
    public function ban(): void
    {
        if ($this->isBanned()) {
            throw new \DomainException('User is already banned.');
        }
        
        $this->status = self::STATUS_BANNED;
    }
}
```

### Исключения времени выполнения

Используются для системных ошибок:

```php
class UserRepository
{
    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }
}
```

## Конфигурационные файлы проекта

### Общая структура конфигурации

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

## Инструменты сбора документации

Проект предоставляет специальные инструменты для сбора и генерации документации, которые могут быть использованы Kilo Code для быстрого понимания структуры и содержания проекта.

### Console Controllers

#### DocController

Генерирует Markdown документацию для указанного класса на основе PHPDoc блоков.

**Команда:**
```bash
php yii doc/generate "core\entities\User\User"
```

**Функциональность:**
- Анализ PHPDoc блоков класса
- Извлечение описаний классов, свойств и методов
- Форматирование в Markdown
- Вывод документации в консоль

#### CollectCodeController

Собирает информацию о коде проекта по конфигурационному файлу.

**Команды:**
```bash
# Сбор кода по конфигурации
php yii collect-code

# Создание примера конфигурации
php yii collect-code/create

# Сбор кода из Git коммита
php yii collect-code/git [revision]
```

**Функциональность:**
- Сбор информации о структуре проекта
- Извлечение информации о классах
- Сохранение в YAML формате
- Поддержка исключений и включений файлов/директорий

**Конфигурационный файл (console/config/collect/config.yaml):**
```yaml
include:
  dirs:
    - '@core/entities'
    - '@core/services'
  files:
    - '@core/helpers/ArrayHelper.php'
exclude:
  dirs:
    - '@core/tests'
  files:
    - '@core/services/DebugService.php'
code_style:
  recommended:
    - '@core/examples/recommended/ExampleService.php'
  bad:
    - '@core/examples/bad/ExampleService.php'
```

#### StructureController

Генерирует лаконичное описание структуры проекта с неймспейсами.

**Команда:**
```bash
php yii structure/export
```

**Функциональность:**
- Рекурсивный обход директорий проекта
- Исключение ненужных директорий (vendor, frontend, api и т.д.)
- Генерация списка неймспейсов
- Сохранение в файл structure.txt

**Исключаемые директории:**
- /.git
- /vendor
- /frontend
- /api
- /node_modules
- /logs
- /tmp
- /.idea
- /backend/web
- /backend/gii
- /backend/components
- /backend/runtime
- /backend/tests
- /console/runtime
- /console/migrations
- /environments
- /bin
- /core/tests
- /common/tests
- /backend/controllers/kit
- /backend/views/kit
- /core/entities/Kit
- /core/forms/Kit
- /core/repositories/kit
- /core/services/kit