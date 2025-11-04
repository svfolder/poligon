<?php


namespace console\controllers;

use sixlive\DotenvEditor\DotenvEditor;
use Yii;
use yii\base\ErrorException;
use yii\console\Controller;
use yii\console\Request;
use yii\db\Exception;
use yii\helpers\Console;
use yii\helpers\FileHelper;
use yii\helpers\Url;


class ConfigController extends Controller
{

    /**
     * @var \XMLWriter
     */
    private $writer;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionSetup()
    {
        $domain = getDomain();
        $database = \nspl\a\first(explode('.', $domain));

        $editor = new DotenvEditor();
        $editor->load(Yii::getAlias("@root/.env"));

        $editor->set('HOST', $domain);
        $editor->set('NAME', $database);
        $editor->set('DB_NAME', $database);
        $editor->save();

        $this->stdout('.ENV build done!' . PHP_EOL);
    }

    public function actionDatabase()
    {
        $database = \nspl\a\first(explode('.', getDomain()));

        try {
            Yii::$app->db->createCommand("SHOW DATABASES()")->queryScalar();
        } catch (Exception $e) {
            if ($e->getCode() == 1049){
                try {
                    $command = Yii::$app->mysql->createCommand("CREATE DATABASE `{$database}` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;");
                    $command->execute();

                    $this->stdout("Create database [{$database}] success!" . PHP_EOL);
                } catch (Exception $e) {
                    Console::error($e->getMessage());
                }
            }
        }
    }

    public function actionMigrate()
    {
        $database = \nspl\a\first(explode('.', getDomain()));

        try {

            $tables = Yii::$app->db->createCommand("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '{$database}';")->queryScalar();
            if ($tables == 0){

                $command = "php yii migrate --interactive=0";
                exec($command, $result, $code);
                if ($code == 0){
                    $this->stdout("Migrated up successfully!" . PHP_EOL);
                }

            }

        } catch (\yii\base\Exception $e) {
            Console::error($e->getMessage());
        }
    }

    public function actionGit()
    {
        try {

            $folder = Yii::getAlias("@root/.git");

            if (is_dir($folder)){
                FileHelper::removeDirectory($folder);
                $this->stdout('Repository deleted!' . PHP_EOL);
            }

            exec("git init", $result, $code);
            if ($code == 0){
                $this->stdout("Repository created successfully!" . PHP_EOL);
            }

            $this->stdout('Repository configuration done!' . PHP_EOL);

        } catch (ErrorException $e) {
            Console::error($e->getMessage());
        }
    }

    public function actionCute()
    {
        try {

            $path = Yii::getAlias("@root/.gitignore");

            $lines = file($path, FILE_IGNORE_NEW_LINES);

            $index = array_search('# ignore demo CRUD', $lines);
            $content = array_slice($lines, 0, $index + 1);
            file_put_contents($path, implode(PHP_EOL, $content));

            $this->stdout('.gitignore has been truncated!' . PHP_EOL);

        } catch (Exception $e) {
            Console::error($e->getMessage());
        }

    }

    public function actionStorm() : void
    {
        $domain = getDomain();
        $path = Yii::getAlias("@root/.idea/{$domain}.iml");

        $this->writer = new \XMLWriter();
        $this->writer->openURI($path);
        $this->writer->setIndent(true);

        $this->writer->startDocument('1.0', 'UTF-8');

        $this->writer->startElement('module');
        $this->writer->writeAttribute('type', 'WEB_MODULE');
        $this->writer->writeAttribute('version', '4');

        $this->writer->startElement('component');
        $this->writer->writeAttribute('name', 'NewModuleRootManager');

        $this->writer->startElement('content');
        $this->writer->writeAttribute('url', 'file://$MODULE_DIR$');

        $this->sourceFolder('/core' );
        $this->sourceFolder('/console');
        $this->sourceFolder('/common');
        $this->sourceFolder('/backend');
        $this->sourceFolder('/api');
        $this->sourceFolder('/frontend');

        $this->sourceFolder('/tests', '', true);
        $this->sourceFolder('/common/tests', '', true);
        $this->sourceFolder('/frontend/tests', '', true);
        $this->sourceFolder('/backend/tests', '', true);
        $this->sourceFolder('/api/tests', '', true);

        $this->sourceFolder('/core/entities', 'core\entities', false, true);
        $this->sourceFolder('/core/helpers', 'core\helpers', false, true);

        $this->sourceFolder('/core/forms', 'core\forms', false, true);
        $this->sourceFolder('/core/forms/kit', 'core\forms\kit', false, true);
        $this->sourceFolder('/core/forms/auth', 'core\forms\auth', false, true);

        $this->sourceFolder('/core/readModels', 'core\readModels', false, true);
        $this->sourceFolder('/core/readModels/kit', 'core\readModels\kit', false, true);
        $this->sourceFolder('/core/readModels/auth', 'core\readModels\auth', false, true);

        $this->sourceFolder('/core/repositories', 'core\repositories', false, true);
        $this->sourceFolder('/core/repositories/kit', 'core\repositories\kit', false, true);
        $this->sourceFolder('/core/repositories/auth', 'core\repositories\auth', false, true);

        $this->sourceFolder('/core/services', 'core\services', false, true);
        $this->sourceFolder('/core/services/kit', 'core\services\kit', false, true);
        $this->sourceFolder('/core/services/auth', 'core\services\auth', false, true);

        $this->sourceFolder('/core/delegate', 'core\delegate', false, true);
        $this->sourceFolder('/core/widgets', 'core\widgets', false, true);
        $this->sourceFolder('/core/actions', 'core\actions', false, true);
        $this->sourceFolder('/core/access', 'core\access', false, true);
        $this->sourceFolder('/core/useCases', 'core\useCases', false, true);
        $this->sourceFolder('/core/rules', 'core\rules', false, true);
        $this->sourceFolder('/core/dispatchers', 'core\dispatchers', false, true);
        $this->sourceFolder('/core/events', 'core\events', false, true);
        $this->sourceFolder('/core/listeners', 'core\listeners', false, true);
        $this->sourceFolder('/core/traits', 'core\traits', false, true);
        $this->sourceFolder('/core/dto', 'core\dto', false, true);
        $this->sourceFolder('/core/jobs', 'core\dto', false, true);
        $this->sourceFolder('/core/validators', 'core\validators', false, true);

        $this->sourceFolder('/core/traits/kit', 'core\traits\kit', false, true);

        $this->sourceFolder('/common/widgets', 'common\widgets', false, true);

        $this->sourceFolder('/backend/controllers', 'backend\controllers', false, true);
        $this->sourceFolder('/backend/controllers/kit', 'backend\controllers\kit', false, true);
        $this->sourceFolder('/backend/controllers/auth', 'backend\controllers\auth', false, true);

        $this->sourceFolder('', '', false, true);
        $this->sourceFolder('', '', false, true);
        $this->sourceFolder('', '', false, true);
        $this->sourceFolder('', '', false, true);
        $this->sourceFolder('', '', false, true);

        $this->excludeFolder('/api/runtime');
        $this->excludeFolder('/backend/runtime');
        $this->excludeFolder('/backend/web/assets');
        $this->excludeFolder('/console/runtime');
        $this->excludeFolder('/frontend/runtime');
        $this->excludeFolder('/frontend/web/assets');
        $this->excludeFolder('/vagrant');
        $this->excludeFolder('/vendor');

        $this->writer->endElement();

        $this->writer->startElement('orderEntry');
        $this->writer->writeAttribute('type', 'inheritedJdk');
        $this->writer->endElement();

        $this->writer->startElement('orderEntry');
        $this->writer->writeAttribute('type', 'sourceFolder');
        $this->writer->endElement();

        $this->writer->endElement();
        $this->writer->endElement();
        $this->writer->endDocument();

        $this->stdout('Storm configuration done!' . PHP_EOL);
    }

    /**
     * @param string $folder
     * @param string $packagePrefix
     * @param bool $isTestSource
     * @param bool $generated
     */
    public function sourceFolder(string $folder, $packagePrefix = null, $isTestSource = false, $generated = false)
    {
        $this->writer->startElement("sourceFolder");
        $this->writer->writeAttribute('url', "file://\$MODULE_DIR\${$folder}");

        if ($isTestSource){
            $this->writer->writeAttribute('isTestSource', "true");
        }else{
            $this->writer->writeAttribute('isTestSource', "false");
        }

        if (!empty($packagePrefix)){
            $this->writer->writeAttribute('packagePrefix', "{$packagePrefix}");
        }

        if ($generated){
            $this->writer->writeAttribute('generated', "true");
        }

        $this->writer->endElement();
    }

    /**
     * @param string $folder
     */
    public function excludeFolder(string $folder)
    {
        $this->writer->startElement("excludeFolder");
        $this->writer->writeAttribute('url', "file://\$MODULE_DIR\${$folder}");
        $this->writer->endElement();
    }

}