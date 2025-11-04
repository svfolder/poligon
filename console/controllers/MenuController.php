<?php
namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;
use core\helpers\CommandHelper;
use League\CLImate\CLImate;

class MenuController extends Controller
{
    private $lastCommand = null;

    public function actionIndex(): void
    {
        $commands = CommandHelper::getCommands(\Yii::getAlias('@console/controllers'));

        if (empty($commands)) {
            $this->stderr("Не найдено ни одной доступной команды.\n", Console::FG_RED);
            return;
        }

        while (true) {
            $this->clearScreen();
            echo "Доступные команды:\n";

            $menuOptions = [];
            foreach ($commands as $index => $command) {
                $menuOptions[(string)($index + 1)] = $command['name'];
                echo ($index + 1) . ". " . $command['name'] . "\n";
            }
            echo "0. Выход\n";

            $selected = $this->selectWithEscape("Выберите команду:", array_merge(['0' => 'Выход'], $menuOptions));
            if ($selected === null || $selected === '0') {
                break;
            }

            $commandIndex = (int)$selected - 1;
            if (!isset($commands[$commandIndex])) {
                $this->stderr("Неверный выбор. Попробуйте снова.\n", Console::FG_RED);
                continue;
            }

            $currentCommand = $commands[$commandIndex];

            $action = $this->selectAction($currentCommand['actions']);
            if ($action !== null) {
                $this->runCommand($currentCommand['name'], $action);
            }
        }
    }

    private function clearScreen(): void
    {
        Console::clearScreen();
    }

    /**
     * @param string $prompt
     * @param array $options
     * @return string|null
     */
    private function selectWithEscape(string $prompt, array $options): ?string
    {
        $climate = new CLImate();
        $optionsString = '[' . implode(',', array_keys($options)) . ']';
        $climate->out($prompt . ' ' . $optionsString . ': ');

        $input = $climate->input('')->prompt();

        if ($input === "\x1B") {
            return null;
        }

        if (isset($options[$input])) {
            return $input;
        }

        $climate->error("Неверный выбор. Попробуйте снова.");
        return $this->selectWithEscape($prompt, $options);
    }

    /**
     * @param array $actions
     * @return string|null
     */
    private function selectAction(array $actions): ?string
    {
        $climate = new CLImate();
        $climate->out("Доступные действия:\n");

        $menuOptions = [];
        foreach ($actions as $index => $action) {
            $menuOptions[(string)($index + 1)] = $action;
            $climate->out(($index + 1) . ". " . $action);
        }
        $climate->out("0. Назад");

        $selected = $this->selectWithEscape("Выберите действие:", array_merge(['0' => 'Назад'], $menuOptions));
        if ($selected === null || $selected === '0') {
            return null;
        }

        $selectedIndex = (int)$selected - 1;
        if (!isset($actions[$selectedIndex])) {
            $climate->error("Неверный выбор. Попробуйте снова.");
            return $this->selectAction($actions);
        }

        return $actions[$selectedIndex];
    }

    private function runCommand(string $command, string $action): void
    {
        $this->clearScreen();
        $this->stdout("Выполняется команда: {$command} {$action}\n", Console::FG_GREEN);

        try {
            $this->run("{$command}/{$action}");
            $this->lastCommand = [$command, $action];
        } catch (\Exception $e) {
            $this->stderr("Ошибка выполнения команды: " . $e->getMessage() . "\n", Console::FG_RED);
        }

        $this->stdout("\n");
        $this->stdout("Нажмите Enter для повтора команды или q для возврата в меню...\n");

        $this->handlePostCommandInput();
    }

    private function handlePostCommandInput(): void
    {
        $climate = new CLImate();
        $input = $climate->input('')->prompt();

        // Логирование ввода в текстовый файл
        file_put_contents(__DIR__ . '/input_log.txt', "Введенный символ: '" . $input . "' (ASCII: " . ord($input) . ")\n", FILE_APPEND);

        if ($input === '') {
            $this->repeatLastCommand();
        } elseif ($input === 'q') {
            $this->exitToMainMenu();
        } else {
            $this->exitToMainMenu();
        }
    }

    private function repeatLastCommand(): void
    {
        if ($this->lastCommand !== null) {
            [$lastCommand, $lastAction] = $this->lastCommand;
            $this->runCommand($lastCommand, $lastAction);
        }
    }

    private function exitToMainMenu(): void
    {
        return;
    }
}