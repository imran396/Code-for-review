<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\MailingListTemplateCategories;

trait MailingListTemplateCategoriesWriteRepositoryAwareTrait
{
    protected ?MailingListTemplateCategoriesWriteRepository $mailingListTemplateCategoriesWriteRepository = null;

    protected function getMailingListTemplateCategoriesWriteRepository(): MailingListTemplateCategoriesWriteRepository
    {
        if ($this->mailingListTemplateCategoriesWriteRepository === null) {
            $this->mailingListTemplateCategoriesWriteRepository = MailingListTemplateCategoriesWriteRepository::new();
        }
        return $this->mailingListTemplateCategoriesWriteRepository;
    }

    /**
     * @param MailingListTemplateCategoriesWriteRepository $mailingListTemplateCategoriesWriteRepository
     * @return static
     * @internal
     */
    public function setMailingListTemplateCategoriesWriteRepository(MailingListTemplateCategoriesWriteRepository $mailingListTemplateCategoriesWriteRepository): static
    {
        $this->mailingListTemplateCategoriesWriteRepository = $mailingListTemplateCategoriesWriteRepository;
        return $this;
    }
}
