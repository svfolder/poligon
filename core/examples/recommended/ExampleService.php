<?php


namespace core\examples\recommended;


use core\entities\Language\Language;
use core\entities\Page\Page;
use core\entities\Page\PageLanguage;
use core\forms\Page\PageForm;
use core\repositories\page\PageRepository;
use core\repositories\signature\SignatureRepository;
use core\services\language\LanguageService;
use core\services\TransactionManager;

class ExampleService
{
    /** @var PageRepository */
    protected $pages;

    /** @var SignatureRepository */
    protected $signatures;

    /** @var TransactionManager */
    protected $transaction;

    /** @var LanguageService */
    protected $language;

    /**
     * PageService constructor
     * @var PageRepository
     * @var SignatureRepository
     * @var TransactionManager
     * @var LanguageService
     */
    public function __construct(
        PageRepository $pages,
        SignatureRepository $signatures,
        TransactionManager $transaction,
        LanguageService $language
    ) {
        $this->pages = $pages;
        $this->signatures = $signatures;
        $this->transaction = $transaction;
        $this->language = $language;
    }

    public function find($id): ?Page
    {
        return $this->pages->find($id);
    }

    /**
     * @return Page[]
     */
    public function findAll(): array
    {
        return $this->pages->findAll();
    }

    public function create(PageForm $form): Page
    {
        $page = Page::create(
            $form->signature_id,
            $form->title
        );

        $this->bindHands($form, $page);

        $this->bindSignatures($form, $page);

        $this->transaction->wrap(function () use ($form, $page) {
            $this->pages->save($page);
        });
        return $page;
    }

    public function edit($id, PageForm $form): void
    {
        $page = $this->pages->get($id);
        $page->edit(
            $form->signature_id,
            $form->title
        );

        $this->transaction->wrap(function () use ($form, $page) {

            $page->revokeHands();
            $this->pages->save($page);

            $this->bindHands($form, $page);

            $page->revokeSignatures();
            $this->pages->save($page);

            $this->bindSignatures($form, $page);

            $this->pages->save($page);
        });
    }

    public function remove($id): void
    {
        $page = $this->pages->get($id);
        $this->pages->delete($page);
    }

    public function removeAll(): void
    {
        $this->pages->deleteAll();
    }

    public function truncate(): void
    {
        $this->pages->truncate();
    }

    public function getRepository(): PageRepository
    {
        return $this->pages;
    }

    public function storeLanguages(Page $model)
    {
        $this->initLanguages($model);
        $this->saveLanguages($model);
    }

    public function initLanguages(Page $model)
    {
        $messages = [];
        /** @var Language $language */
        foreach ($this->language->findAll(['status' => 1]) as $language) {
            if (!isset($this->pageLanguages[$language->code])) {
                $pageLanguage = new PageLanguage();
                $pageLanguage->language = $language->code;
                $messages[$language->code] = $pageLanguage;
            } else {
                $messages[$language->code] = $model->pageLanguages[$language->code];
            }
        }
        $model->populateRelation('pageLanguages', $messages);
    }

    public function saveLanguages(Page $model)
    {
        $this->deleteLanguages($model->id);
        foreach ($model->pageLanguages as $language) {
            $model->link('pageLanguages', $language);
            $language->save();
        }
    }

    public function deleteLanguages($id): int
    {
        return PageLanguage::deleteAll(['page_id' => $id]);
    }

    public function bindHands(PageForm $form, Page $page)
    {
        foreach ($form->hands->existing as $id) {
            $signature = $this->signatures->get($id);
            $page->assignHand($signature->id);
        };
    }

    public function bindSignatures(PageForm $form, Page $page)
    {
        foreach ($form->signatures->existing as $id) {
            $signature = $this->signatures->get($id);
            $page->assignSignature($signature->id);
        };
    }
}