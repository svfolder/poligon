<?php

namespace core\services\language;

use core\entities\Language\Language;
use core\forms\language\LanguageForm;
use core\repositories\language\LanguageRepository;

class LanguageService
{
    /** @var LanguageRepository */
    protected $languages;

    /**
     * LanguageService constructor
     * @var LanguageRepository
     */
    public function __construct(LanguageRepository $languages)
    {
        $this->languages = $languages;
    }

    public function find($id): ?Language
    {
        return $this->languages->find($id);
    }

    public function findAll($condition = null): array
    {
        return $this->languages->findAll($condition);
    }

    public function create(LanguageForm $form): Language
    {
        $language = Language::create(
            $form->name,
            $form->code,
            $form->status
        );
        $this->languages->save($language);
        return $language;
    }

    public function edit($id, LanguageForm $form): void
    {
        $language = $this->languages->get($id);
        $language->edit(
            $form->name,
            $form->code,
            $form->status
        );
        $this->languages->save($language);
    }

    public function remove($id): void
    {
        $language = $this->languages->get($id);
        $this->languages->delete($language);
    }

    public function removeAll(): void
    {
        $this->languages->deleteAll();
    }

    public function truncate(): void
    {
        $this->languages->truncate();
    }

    public function getRepository(): LanguageRepository
    {
        return $this->languages;
    }

    public function getLanguages(array $columns = ['code']): ?array
    {
        return $this->languages->getLanguages($columns);
    }
}
