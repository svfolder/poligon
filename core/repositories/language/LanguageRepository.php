<?php

namespace core\repositories\language;

use core\entities\Language\Language;

class LanguageRepository
{
    public function get($id): Language
    {
        if (!$language = Language::findOne($id)) {
            throw new \core\repositories\NotFoundException('Language is not found.');
        }
        return $language;
    }

    public function getByName($name): Language
    {
        if (!$language = Language::findOne(['name' => $name])) {
            throw new \core\repositories\NotFoundException('Language is not found.');
        }
        return $language;
    }

    public function find($id): ?Language
    {
        return Language::findOne($id);
    }

    public function findAll($condition = null): array
    {
        return Language::find()->where($condition)->all();
    }

    public function findByName($name): ?Language
    {
        return Language::findOne(['name' => $name]);
    }

    public function save(Language $language): void
    {
        if (!$language->save()) {
            throw new \RuntimeException("Saving error. {$language->plainErrors()}");
        }
    }

    public function delete(Language $language): void
    {
        if (!$language->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function deleteAll(): void
    {
        Language::deleteAll();
    }

    public function truncate(): void
    {
        Language::clearAutoIncrement();
    }

    public function getLanguages(array $columns): array
    {
        return Language::find()->select($columns)->where(['status' => 1])->asArray()->column();
    }
}
