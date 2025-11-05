# Принципы написания кода в проекте

## Общие принципы

Проект следует строгим стандартам кодирования, основанным на PSR-12 с дополнительными требованиями:

### Именование

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

### Type Hinting

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

### PHPDoc

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

## Принципы SOLID

### Single Responsibility Principle

Каждый класс должен иметь одну причину для изменения:

```php
// Хорошо - разделение ответственностей
class UserService
{
    public function create(UserForm $form): User { /* ... */ }
}

class UserMailer
{
    public function sendWelcomeEmail(User $user): void { /* ... */ }
}

// Плохо - смешение ответственностей
class UserService
{
    public function create(UserForm $form): User 
    {
        // Создание пользователя
        // Отправка email
        // Логгирование
    }
}
```

### Open/Closed Principle

Классы должны быть открыты для расширения, но закрыты для модификации:

```php
// Хорошо - использование интерфейсов
interface UserRepositoryInterface
{
    public function save(User $user): void;
}

class DatabaseUserRepository implements UserRepositoryInterface
{
    public function save(User $user): void { /* ... */ }
}

class FileUserRepository implements UserRepositoryInterface
{
    public function save(User $user): void { /* ... */ }
}
```

### Dependency Inversion Principle

Зависимости должны быть направлены на абстракции:

```php
// Хорошо - зависимость от интерфейса
class UserService
{
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}

// Плохо - зависимость от конкретной реализации
class UserService
{
    public function __construct(DatabaseUserRepository $repository)
    {
        $this->repository = $repository;
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

## Тестирование

### Unit-тесты

Классы должны быть покрыты unit-тестами:

```php
class UserTest extends TestCase
{
    public function testCreate(): void
    {
        $user = User::create('john', 'john@example.com');
        
        $this->assertEquals('john', $user->username);
        $this->assertEquals('john@example.com', $user->email);
    }
}
```

### Интеграционные тесты

Для тестирования взаимодействия компонентов:

```php
class UserServiceTest extends TestCase
{
    public function testCreateUser(): void
    {
        $form = new UserForm();
        $form->username = 'john';
        $form->email = 'john@example.com';
        
        $service = new UserService(
            new UserRepository(),
            new RoleManager(),
            new TransactionManager()
        );
        
        $user = $service->create($form);
        
        $this->assertInstanceOf(User::class, $user);
    }
}
```

## Примеры плохого и хорошего кода

### Плохой код

Примеры антипаттернов, которые НЕ ДОПУСТИМЫ в проекте:

1. **Отсутствие type hinting в параметрах и возвращаемых значениях** - ЗАПРЕЩЕНО
  ```php
  // Плохо: отсутствие type hinting у параметра
  public function find($id) // - параметр $id должен быть int, возвращаемое значение должно быть ?Page
  {
      return $this->pages->find($id);
  }

  // Плохо: отсутствие type hinting у параметра
  public function create(\core\forms\PageForm $form) // - параметр $form должен быть PageForm $form, возвращаемое значение должно быть Page
  {
      $page = \core\entities\Page\Page::create($form->signature_id, $form->title);
      return $page;
  }
  ```

2. **Использование полных путей вместо use** - ЗАПРЕЩЕНО
  ```php
  // Плохо: использование полного пути в @var
  protected $pages;

  // Плохо: конструктор использует полные пути
  public function __construct(
      \core\services\TransactionManager $transaction,
      \core\services\language\LanguageService $language
  ) {
      // Плохо: создание через сервис-локатор — ЗАПРЕЩЕНО
      $this->pages = \Yii::createObject(PageRepository::class);
  }

  // Плохо: полный путь вместо use
  $page = \core\entities\Page\Page::create($form->signature_id, $form->title);
  ```

3. **DI через new и Yii::createObject** - ЗАПРЕЩЕНО
  ```php
  // Плохо: DI через createObject и new — ЗАПРЕЩЕНО
  public function __construct() {
      $this->pages = \Yii::createObject(PageRepository::class); // DI через createObject - ЗАПРЕЩЕНО
      $this->signatures = new \core\repositories\signature\SignatureRepository(); // DI через new - ЗАПРЕЩЕНО
  }
  ```

4. **Глобальный доступ к Yii::$app** - ЗАПРЕЩЕНО
  ```php
  // Плохо: глобальный доступ к базе данных — ЗАПРЕЩЕНО
  $transaction = \Yii::$app->db->beginTransaction();

  // Плохо: метод использует глобальное состояние
  public function logError($message) {
      $logger = \Yii::$app->get('logger');
      $logger->error($message);
  }
  ```

5. **Публичные свойства** - нарушают инкапсуляцию
  ```php
  // Плохо: публичное свойство
  public $transaction;
  ```

6. **Плохие комментарии** - дублируют типы, которые можно указать в сигнатуре
  ```php
  // Плохо: комментарий бесполезен
  /**
   * @param $id — нельзя! тип должен быть в сигнатуре: find(int $id)
   * @return \core\entities\Page\Page|null — нельзя! должно быть: ?Page
   */
  public function find($id)
  ```

7. **Имена методов** - нечитаемые, слишком длинные
  ```php
  // Плохо: имя метода нечитаемое и избыточное
  public function badNameVeryLongMethod($withCode)
  ```

8. **Внедрение хелперов через DI** - КАТЕГОРИЧЕСКИ ЗАПРЕЩЕНО
  ```php
  // Плохо: попытка внедрить хелпер через DI — КАТЕГОРИЧЕСКИ ЗАПРЕЩЕНО
  private $codeHelper;

  public function __construct(CodeHelper $codeHelper) {
      $this->codeHelper = $codeHelper;  // ← ЭТО АНТИПАТТЕРН!
  }
  ```

### Хороший код

Примеры правильного применения принципов:

1. **Type hinting для параметров и возвращаемых значений** - Обязательно
  ```php
  // Хорошо: явное указание типов
  public function find(int $id): ?Page
  {
      return $this->pages->find($id);
  }

  public function create(PageForm $form): Page
  {
      $page = Page::create($form->signature_id, $form->title);
      return $page;
  }
  ```

2. **Использование оператора use для импорта классов** - Обязательно
  ```php
  use core\entities\Page\Page;
  use core\repositories\page\PageRepository;
  use core\forms\Page\PageForm;
  use core\services\TransactionManager;
  use core\services\language\LanguageService;

  // Хорошо: использование сокращенных имен
  public function __construct(
      PageRepository $pages,
      SignatureRepository $signatures,
      TransactionManager $transaction,
      LanguageService $language
  ) {
      $this->pages = $pages;
      $this->signatures = $signatures;
      $this->transaction = $transaction;
      $this->language = $language;
  }
  ```

3. **Внедрение зависимостей через конструктор** - Обязательно
  ```php
  // Хорошо: внедрение зависимостей через конструктор
  public function __construct(
      PageRepository $pages,
      SignatureRepository $signatures,
      TransactionManager $transaction,
      LanguageService $language
  ) {
      $this->pages = $pages;
      $this->signatures = $signatures;
      $this->transaction = $transaction;
      $this->language = $language;
  }
  ```

4. **Инкапсуляция** - Свойства класса должны быть protected или private
  ```php
  // Хорошо: защищенное свойство
  protected $transaction;

  // Хорошо: аннотация для свойства
  /** @var PageRepository */
  protected $pages;
  ```

5. **Использование внедренных зависимостей вместо глобального доступа компонентам приложения** - Обязательно
  ```php
  // Хорошо: использование внедренной зависимости
  $this->transaction->wrap(function () use ($form, $page) {
      $this->pages->save($page);
  });
  ```

6. **Использование хелперов через статические вызовы** - Обязательно
  ```php
  // Хорошо: вызов статических методов напрямую
  CodeHelper::extractNamespace($content);
  ```

7. **Четкое и понятное именование методов** - Обязательно
  ```php
  // Хорошо: четкое и понятное имя
  public function processCode($withCode)
  ```

8. **Комментарии только там, где они действительно необходимы** - Рекомендуется
  ```php
  /**
   * @return Page[] — это исключение, потому что невозможно указать тип элементов массива в PHP 7.3
   */
  public function findAll(): array
  ```

9. **Аннотации PHPDoc с короткими именами классов** - Обязательно
  ```php
  // Хорошо: использование коротких имен классов в аннотациях
  /** @var PageRepository */
  protected $pages;
  ```

