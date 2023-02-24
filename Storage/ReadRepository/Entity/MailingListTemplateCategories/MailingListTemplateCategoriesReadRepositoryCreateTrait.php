<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\MailingListTemplateCategories;

trait MailingListTemplateCategoriesReadRepositoryCreateTrait
{
    protected ?MailingListTemplateCategoriesReadRepository $mailingListTemplateCategoriesReadRepository = null;

    protected function createMailingListTemplateCategoriesReadRepository(): MailingListTemplateCategoriesReadRepository
    {
        return $this->mailingListTemplateCategoriesReadRepository ?: MailingListTemplateCategoriesReadRepository::new();
    }

    /**
     * @param MailingListTemplateCategoriesReadRepository $mailingListTemplateCategoriesReadRepository
     * @return static
     * @internal
     */
    public function setMailingListTemplateCategoriesReadRepository(MailingListTemplateCategoriesReadRepository $mailingListTemplateCategoriesReadRepository): static
    {
        $this->mailingListTemplateCategoriesReadRepository = $mailingListTemplateCategoriesReadRepository;
        return $this;
    }
}
