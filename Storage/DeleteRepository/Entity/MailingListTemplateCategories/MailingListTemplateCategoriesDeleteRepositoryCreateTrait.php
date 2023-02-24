<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\MailingListTemplateCategories;

trait MailingListTemplateCategoriesDeleteRepositoryCreateTrait
{
    protected ?MailingListTemplateCategoriesDeleteRepository $mailingListTemplateCategoriesDeleteRepository = null;

    protected function createMailingListTemplateCategoriesDeleteRepository(): MailingListTemplateCategoriesDeleteRepository
    {
        return $this->mailingListTemplateCategoriesDeleteRepository ?: MailingListTemplateCategoriesDeleteRepository::new();
    }

    /**
     * @param MailingListTemplateCategoriesDeleteRepository $mailingListTemplateCategoriesDeleteRepository
     * @return static
     * @internal
     */
    public function setMailingListTemplateCategoriesDeleteRepository(MailingListTemplateCategoriesDeleteRepository $mailingListTemplateCategoriesDeleteRepository): static
    {
        $this->mailingListTemplateCategoriesDeleteRepository = $mailingListTemplateCategoriesDeleteRepository;
        return $this;
    }
}
