<?php


namespace console\controllers;

use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\FileHelper;

class KitController extends Controller
{
    private $_clean_directory = [
        '@backend/components/' => '/backend/components/',
        '@backend/gii/' => '/backend/gii/',
        '@backend/controllers/kit/' => '/backend/controllers/kit/',
        '@backend/views/kit/' => '/backend/views/kit/',
        '@core/entities/Kit/' => '/core/entities/Kit/',
        '@core/forms/kit/' => '/core/forms/kit/',
        '@core/services/kit/' => '/core/services/kit/',
        '@core/repositories/kit/' => '/core/repositories/kit/',
        '@core/readModels/kit/' => '/core/readModels/kit/',
        '@core/traits/kit/' => '/core/traits/kit/',
    ];

    private $_only_files = [
        '@backend/components/' => '/backend/components/',
    ];

    private $_only_single_files = [
        '@core/behaviors/PropertyBehavior.php' => '/core/behaviors/PropertyBehavior.php',
        '@core/entities/Property.php' => '/core/entities/Property.php',
        '@core/forms/PropertyForm.php' => '/core/forms/PropertyForm.php',
        '@backend/config/main-local.php' => '/backend/config/main-local.php',

        '@core/helpers/MapKitHelper.php' => '/core/helpers/MapKitHelper.php',
        '@core/helpers/KitHelper.php' => '/core/helpers/KitHelper.php',
        '@core/helpers/MapHelper.php' => '/core/helpers/MapHelper.php',
        '@core/helpers/MapQueryHelper.php' => '/core/helpers/MapQueryHelper.php',
        '@core/helpers/MapSearchHelper.php' => '/core/helpers/MapSearchHelper.php',
        '@core/helpers/EntityMapHelper.php' => '/core/helpers/EntityMapHelper.php',
        '@core/helpers/DirectoryHelper.php' => '/core/helpers/DirectoryHelper.php',
        '@core/helpers/BaseMapHelper.php' => '/core/helpers/BaseMapHelper.php',
        '@core/helpers/SchemaHelper.php' => '/core/helpers/SchemaHelper.php',
        '@core/helpers/MixHelper.php' => '/core/helpers/MixHelper.php',

        '@core/dto/DTOClassInfo.php' => '/core/dto/DTOClassInfo.php',
    ];

    /**
     * @var string
     */
    private $source_path;

    /**
     * @throws ErrorException
     */
    protected function removeAll(): void
    {
        foreach ($this->_clean_directory as $directory => $destination) {
            $path = Yii::getAlias($directory);

            try {
                $this->cleanIncludeDirectories($path);
            } catch (Exception $e) {
                echo Console::ansiFormat($e->getMessage());
            }
        }
    }

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->source_path = Yii::getAlias("@root/../sv5kit.open");
    }

    /**
     * @throws ErrorException
     */
    public function actionRemove()
    {
        $this->removeAll();
        $this->copySingleFiles([
            '@backend/gii/GiiModule.php' => '/backend/gii/GiiModule.php',
        ]);
    }

    /**
     * @throws ErrorException
     */
    public function actionUpdate() : void
    {
        $this->removeAll();

        // Копируем только каталоги
        foreach ($this->_clean_directory as $directory => $source){
            $src = "{$this->source_path}{$source}";
            $dst = Yii::getAlias($directory);

            FileHelper::copyDirectory($src, $dst, ['recursive' => true]);
        }

        // Копируем только файлы в каталоге
        foreach ($this->_only_files as $directory => $source){
            $src = "{$this->source_path}{$source}";
            $dst = Yii::getAlias($directory);

            $files = FileHelper::findFiles($src, ['except' => ['.gitignore'], 'recursive' => false]);

            foreach ($files as $file){
                $file_name = basename($file);
                $file_dst = "{$dst}{$file_name}";

                copy($file, $file_dst);
            }
        }

        $this->copySingleFiles(
            $this->_only_single_files
        );
    }

    /**
     * @throws ErrorException
     */
    private function cleanIncludeDirectories($path)
    {
        $directories = FileHelper::findDirectories($path, ['recursive' => false]);

        foreach ($directories as $directory){
            FileHelper::removeDirectory($directory, ['recursive' => true]);
        }

        foreach (FileHelper::findFiles($path, ['except' => ['.gitignore']]) as $file){
            FileHelper::unlink($file);
        }
    }

    protected function copySingleFiles(array $files): void
    {
        foreach ($files as $file => $source) {
            $src = "{$this->source_path}{$source}";
            $dst = Yii::getAlias($file);

            copy($src, $dst);
        }
    }

}