# Инструменты сбора документации

Проект предоставляет специальные инструменты для сбора и генерации документации, которые могут быть использованы Kilo Code для быстрого понимания структуры и содержания проекта.

## Console Controllers

### DocController

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

**Пример использования:**
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

### CollectCodeController

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

### StructureController

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

### ConfigController

Настройка конфигурации проекта.

**Команды:**
```bash
# Настройка .env файла
php yii config/setup

# Создание базы данных
php yii config/database

# Выполнение миграций
php yii config/migrate

# Инициализация Git репозитория
php yii config/git
```

## Services

### CodeCollectorService

Сервис для сбора кода проекта по конфигурации.

**Основные методы:**
- `collect()` - Сбор структуры кода на основе конфигурационного файла
- `collectFromGitCommit()` - Сбор кода из указанного коммита Git
- `saveConfig()` - Сохранение конфигурации в YAML-файл

**Функциональность:**
- Поддержка различных расширений файлов (php, js, ts, css, scss, less, html, phtml, twig, blade.php, json)
- Извлечение информации о классах через Reflection
- Сохранение в YAML формате
- Поддержка примеров стиля кода (рекомендуемый, плохой, запрещенный)

### CodeHelper

Хелпер для работы с кодом.

**Основные методы:**
- `extractNamespace()` - Извлечение namespace из содержимого PHP-файла
- `extractClassName()` - Извлечение имени класса из содержимого PHP-файла
- `getClassInfo()` - Получение информации о классе через Reflection
- `saveToYaml()` - Сохранение структуры проекта в YAML-файл
- `addToStructure()` - Добавление файла в структуру по частям пути

## DTO (Data Transfer Objects)

### DTOCollectConfig

Конфигурация для сбора кода.

**Свойства:**
- `include` - Включаемые директории и файлы
- `exclude` - Исключаемые директории и файлы
- `code_style` - Примеры стиля кода (рекомендуемый, плохой, запрещенный)

### DTOFileSet

Набор файлов и директорий.

**Свойства:**
- `dirs` - Список директорий
- `files` - Список файлов

## Использование инструментов для Kilo Code

### Быстрый старт

1. **Генерация документации по классу:**
   ```bash
   php yii doc/generate "core\services\CodeCollectorService"
   ```

2. **Сбор структуры проекта:**
   ```bash
   php yii structure/export
   ```

3. **Сбор информации о коде:**
   ```bash
   php yii collect-code
   ```

### Рекомендации для Kilo Code

1. **Используйте DocController** для получения детальной информации о конкретных классах
2. **Используйте StructureController** для понимания общей структуры проекта
3. **Используйте CollectCodeController** для получения полной информации о коде проекта
4. **Анализируйте сгенерированные YAML файлы** для быстрого понимания структуры

### Генерируемые файлы

- `console/runtime/project_code.yaml.txt` - Полная информация о коде проекта
- `console/runtime/codestyle.yaml.txt` - Примеры стиля кода
- `structure.txt` - Структура проекта с неймспейсами

Эти файлы содержат структурированную информацию, которую Kilo Code может быстро анализировать для понимания проекта.