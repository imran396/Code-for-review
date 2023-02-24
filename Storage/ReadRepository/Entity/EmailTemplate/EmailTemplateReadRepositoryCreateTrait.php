<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\EmailTemplate;

trait EmailTemplateReadRepositoryCreateTrait
{
    protected ?EmailTemplateReadRepository $emailTemplateReadRepository = null;

    protected function createEmailTemplateReadRepository(): EmailTemplateReadRepository
    {
        return $this->emailTemplateReadRepository ?: EmailTemplateReadRepository::new();
    }

    /**
     * @param EmailTemplateReadRepository $emailTemplateReadRepository
     * @return static
     * @internal
     */
    public function setEmailTemplateReadRepository(EmailTemplateReadRepository $emailTemplateReadRepository): static
    {
        $this->emailTemplateReadRepository = $emailTemplateReadRepository;
        return $this;
    }
}
