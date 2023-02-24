<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\EmailTemplateGroup;

trait EmailTemplateGroupReadRepositoryCreateTrait
{
    protected ?EmailTemplateGroupReadRepository $emailTemplateGroupReadRepository = null;

    protected function createEmailTemplateGroupReadRepository(): EmailTemplateGroupReadRepository
    {
        return $this->emailTemplateGroupReadRepository ?: EmailTemplateGroupReadRepository::new();
    }

    /**
     * @param EmailTemplateGroupReadRepository $emailTemplateGroupReadRepository
     * @return static
     * @internal
     */
    public function setEmailTemplateGroupReadRepository(EmailTemplateGroupReadRepository $emailTemplateGroupReadRepository): static
    {
        $this->emailTemplateGroupReadRepository = $emailTemplateGroupReadRepository;
        return $this;
    }
}
