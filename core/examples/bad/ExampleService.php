<?php

namespace core\examples\bad;

use core\entities\Page\Page;
use core\repositories\page\PageRepository;
use core\helpers\CodeHelper; // ← Это хелпер — его нельзя инжектить!

/**
 * ПРИМЕР КАТЕГОРИЧЕСКИ ЗАПРЕЩЁННОГО СТИЛЯ КОДА (PHP 7.3)
 *
 * Этот класс демонстрирует антипаттерны, которые НЕ ДОПУСТИМЫ:
 *
 * 1. ОТСУТСТВИЕ TYPE HINTING в параметрах и возвращаемых значениях — ЗАПРЕЩЕНО
 * 2. ИСПОЛЬЗОВАНИЕ ПОЛНЫХ ПУТЕЙ вместо use — ЗАПРЕЩЕНО
 * 3. DI ЧЕРЕЗ new и Yii::createObject — ЗАПРЕЩЕНО
 * 4. ГЛОБАЛЬНЫЙ ДОСТУП к Yii::$app — ЗАПРЕЩЕНО
 * 5. ПУБЛИЧНЫЕ СВОЙСТВА — нарушают инкапсуляцию
 * 6. ПЛОХИЕ КОММЕНТАРИИ — дублируют типы, которые можно указать в сигнатуре
 * 7. ИМЕНА МЕТОДОВ — нечитаемые, слишком длинные
 * 8. ВНЕДРЕНИЕ ХЕЛПЕРОВ ЧЕРЕЗ DI — КАТЕГОРИЧЕСКИ ЗАПРЕЩЕНО
 *
 * НИКОГДА НЕ ПИШИ ТАК.
 *
 * Class ExampleService
 * @since PHP 7.3
 * @package core\examples\bad
 */
class ExampleService
{
    /**
     * ПЛОХО: использование полного пути в @var
     * ПРАВИЛЬНО: использовать use + короткое имя
     */
    protected $pages;

    // ПЛОХО: отсутствие @var
    // ПРАВИЛЬНО: /** @var SignatureRepository */
    protected $signatures;

    /**
     * ПЛОХО: публичное свойство
     * ПРАВИЛЬНО: protected/private + DI через конструктор
     */
    public $transaction;

    /**
     * ПЛОХО: приватное свойство без @var
     */
    private $language;

    /**
     * ПЛОХО: конструктор использует полные пути
     * ПРАВИЛЬНО: use core\services\TransactionManager
     *
     * ПЛОХО: DI через createObject и new — ЗАПРЕЩЕНО
     */
    public function __construct(
        \core\services\TransactionManager $transaction,
        \core\services\language\LanguageService $language
    ) {
        // ПЛОХО: создание через сервис-локатор — ЗАПРЕЩЕНО
        $this->pages = \Yii::createObject(PageRepository::class);

        // ПЛОХО: new используется напрямую — ЗАПРЕЩЕНО
        $this->signatures = new \core\repositories\signature\SignatureRepository();

        // ПЛОХО: публичное свойство — нарушает инкапсуляцию
        $this->transaction = $transaction;

        // ПЛОХО: приватное свойство без @var
        $this->language = $language;
    }

    /**
     * ПЛОХО: отсутствие type hinting у параметра
     * - параметр $id должен быть int
     * - возвращаемое значение должно быть ?Page
     *
     * ЗАПРЕЩЕНО: использовать @param и @return, если тип можно указать в сигнатуре
     *
     * @param $id — нельзя! тип должен быть в сигнатуре: find(int $id)
     * @return \core\entities\Page\Page|null — нельзя! должно быть: ?Page
     */
    public function find($id)
    {
        return $this->pages->find($id);
    }

    /**
     * ХОРОШО: @return Page[] — это единственное исключение
     * потому что невозможно указать тип элементов массива в PHP 7.3
     *
     * @return Page[]
     */
    public function findAll(): array
    {
        return $this->pages->findAll();
    }

    /**
     * ПЛОХО: отсутствие type hinting у параметра
     * - параметр $form должен быть PageForm $form
     * - возвращаемое значение должно быть Page
     *
     * ЗАПРЕЩЕНО: использовать полные пути в сигнатуре
     *
     * @param \core\forms\Page\PageForm $form — нельзя! используй use
     * @return \core\entities\Page\Page — нельзя! используй use + type hint
     */
    public function create(\core\forms\Page\PageForm $form)
    {
        // ПЛОХО: полный путь вместо use
        $page = \core\entities\Page\Page::create($form->signature_id, $form->title);

        // ПЛОХО: глобальный доступ к базе данных — ЗАПРЕЩЕНО
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $this->pages->save($page);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $page;
    }

    /**
     * ПЛОХО: отсутствие type hinting
     * - параметр $id должен быть int
     * - метод ничего не возвращает — должен быть void
     *
     * ЗАПРЕЩЕНО: оставлять без type hinting
     */
    public function remove($id)
    {
        $page = $this->pages->get($id);
        $this->pages->delete($page);
    }

    /**
     * ПЛОХО: метод использует глобальное состояние
     * - Yii::$app — запрещено
     * - нет инкапсуляции
     * - нет type hinting
     */
    public function logError($message)
    {
        $logger = \Yii::$app->get('logger');
        $logger->error($message);
    }

    /**
     * ПЛОХО: имя метода нечитаемое и избыточное
     * ПЛОХО: комментарий бесполезен
     * ПЛОХО: отсутствие type hinting
     *
     * ЗАПРЕЩЕНО: давать такие имена
     * ЗАПРЕЩЕНО: оставлять параметры без типа
     *
     * @param $withCode — нельзя! если тип известен — укажи его
     */
    public function badNameVeryLongMethod($withCode)
    {
        return true;
    }

    /**
     * ПЛОХО: попытка внедрить хелпер через DI — КАТЕГОРИЧЕСКИ ЗАПРЕЩЕНО
     *
     * ХЕЛПЕР — это СТАТИЧЕСКИЙ класс-утилита.
     * Он не хранит состояние, не требует конфигурации, не может быть подменён.
     * Его нельзя и НЕ НАДО внедрять через DI.
     *
     * ПРАВИЛЬНО: использовать напрямую — CodeHelper::method()
     *
     * Пример неправильного внедрения:
     *
     *     private $codeHelper;
     *
     *     public function __construct(CodeHelper $codeHelper) {
     *         $this->codeHelper = $codeHelper;  // ← ЭТО АНТИПАТТЕРН!
     *     }
     *
     * ЗАПРЕЩЕНО:
     * - внедрять хелперы в конструктор
     * - хранить хелперы в свойствах
     * - передавать хелперы как зависимости
     *
     * ПРАВИЛЬНО:
     * - вызывать статические методы напрямую: CodeHelper::extractNamespace($content)
     */
    public function badAttemptToInjectHelper()
    {
        // ПЛОХО: если бы мы инжектировали CodeHelper — это антипаттерн
        // $this->codeHelper->extractNamespace($content);

        // ПРАВИЛЬНО: вызов напрямую
        // CodeHelper::extractNamespace($content);
    }

    /**
     * ПЛОХО: использование полного пути в @param
     * - нельзя писать \yii\base\Module — это нарушает правило "используй use"
     * - нужно: use yii\base\Module; и далее просто Module в PHPDoc
     *
     * ПРАВИЛЬНО:
     * use yii\base\Module;
     *
     * /**
     *  * @param string $id
     *  * @param Module $module
     *  * @param CodeCollectorService $service
     *  * @param array $config
     *  *\/
     * public function __construct($id, $module, CodeCollectorService $service, $config = [])
     *
     * ЗАПРЕЩЕНО:
     * - указывать полные пути в PHPDoc
     * - дублировать use через обратные слеши
     */
    public function __constructBadParamType($id, \yii\base\Module $module)
    {
        // демонстрация антипаттерна в сигнатуре и PHPDoc
    }
}