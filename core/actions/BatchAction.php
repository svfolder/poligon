<?php


namespace core\actions;

use ruskid\csvimporter\CSVImporter;
use ruskid\csvimporter\CSVReader;
use ruskid\csvimporter\MultipleImportStrategy;
use Yii;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\web\UploadedFile;


class BatchAction extends Action
{
    public $tableName = '';

    public function run()
    {

        $model = new DynamicModel(['file']);
        $model->addRule(['file'], 'file', ['extensions' => 'csv', 'skipOnEmpty' => true]);
        $model->addRule(['file'], 'required');

        if (Yii::$app->request->isPost){

            $model->load(Yii::$app->request->post());

            /** @var UploadedFile $file */
            $file = UploadedFile::getInstance($model, 'file');
            $model->file = $file->name;

            if($model->validate()){

                $importer = new CSVImporter;

                $importer->setData(new CSVReader([
                    'filename' => $file->tempName,
                    'fgetcsvOptions' => [
                        'delimiter' => ';'
                    ]
                ]));

                $numberRowsAffected = $importer->import(new MultipleImportStrategy([
                    'tableName' => $this->tableName,
                    'configs' => [['attribute' => 'name', 'value' => function($line) { return $line[0]; }, ], ],
                ]));

                if ($numberRowsAffected > 0){
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data loaded'));
                    return $this->controller->redirect(['index']);
                }

            }

        }

        return $this->controller->render('batch', ['model' => $model]);

    }

}