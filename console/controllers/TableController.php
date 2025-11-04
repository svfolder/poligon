<?php

namespace console\controllers;

use core\entities\BaseEntity;
use core\services\RelationBuilder;
use core\services\TableService;
use yii\db\Exception;

class TableController extends \yii\console\Controller
{
    public function actionCat()
    {
        $db = \Yii::$app->db;
        $tableService = new TableService($db);

        $category = new BaseEntity('category');
        $category->addColumn('name', $category->string(255)->notNull());

        $product = new BaseEntity('product');
        $product->addColumn('name', $product->string(255)->notNull());

        // Создаем связь "один ко многим"
        RelationBuilder::createOneToMany($category, $product, 'category_id');

        try {
            // Передаем массив таблиц для создания
            $tableService->createTables([$category, $product]);
            echo "Tables 'category' and 'product' created successfully.\n";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    public function actionTags()
    {
        $db = \Yii::$app->db;
        $tableService = new TableService($db);

        $product = new BaseEntity('product');
        $product->addColumn('name', $product->string(255)->notNull());

        $tag = new BaseEntity('tag');
        $tag->addColumn('name', $tag->string(255)->notNull());

        // Создаем связь "многие ко многим"
        $crossTable = RelationBuilder::createManyToMany($product, $tag);

        try {
            // Передаем массив таблиц для создания
            $tableService->createTables([$product, $tag, $crossTable]);
            echo "Tables 'product', 'tag', and cross table created successfully.\n";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}