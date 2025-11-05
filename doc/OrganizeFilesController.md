# OrganizeFilesController

## Описание

`OrganizeFilesController` - это консольный контроллер для Yii2, который группирует PHP файлы по префиксам в их именах. Контроллер анализирует файлы в указанной директории и создает поддиректории на основе префикса до первого дефиса в имени файла. Все создаваемые папки с файлами помещаются в общую папку `component`.

## Установка

Контроллер уже включен в проект и доступен в `console/controllers/OrganizeFilesController.php`.

## Использование

Для запуска контроллера используйте команду Yii:

```bash
php yii organize-files
```

или

```bash
php yii organize-files/index
```

### Указание директории

аКонтроллер всегда использует директорию `@external` как путь по умолчанию. При указании параметра, он добавляется к базовой директории `@external`:

```bash
php yii organize-files subdirectory
```

или

```bash
php yii organize-files/index subdirectory
```

Это будет означать обработку директории `@external/subdirectory`.

Для обработки самой директории `@external` запустите команду без параметров:

```bash
php yii organize-files
```

## Принцип работы

1. Контроллер анализирует только PHP файлы в указанной директории (по умолчанию `@external`, или `@external/параметр` если указан)
2. Файлы с дефисом в имени группируются в поддиректории внутри общей директории `component`:
   - `user-controller.php` будет помещен в директорию `component/user/`
   - `blog-post-model.php` будет помещен в директорию `component/blog/`
3. Файлы без дефиса остаются в исходной директории
4. Системные файлы и директории (`.git`, `assets`, `partials` и др.) игнорируются

## Примеры

### Пример 1: Группировка файлов в текущей директории

```bash
php yii organize-files
```

Файлы:
- `user-controller.php`
- `blog-model.php`
- `config.php`

Будут организованы следующим образом:
```
./component/user/user-controller.php
./component/blog/blog-model.php
./config.php
```

### Пример 2: Группировка файлов в указанной директории

```bash
php yii organize-files /path/to/project
```

## Параметры

- `$directory` - директория для обработки (по умолчанию текущая директория)

## Исключения

Контроллер игнорирует следующие файлы и директории:
- `assets`
- `partials`
- `.kilocode`
- `.git`
- `.gitignore`