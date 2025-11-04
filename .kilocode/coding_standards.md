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