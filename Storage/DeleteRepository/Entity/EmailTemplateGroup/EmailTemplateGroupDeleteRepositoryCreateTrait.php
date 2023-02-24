<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\EmailTemplateGroup;

trait EmailTemplateGroupDeleteRepositoryCreateTrait
{
    protected ?EmailTemplateGroupDeleteRepository $emailTemplateGroupDeleteRepository = null;

    protected function createEmailTemplateGroupDeleteRepository(): EmailTemplateGroupDeleteRepository
    {
        return $this->emailTemplateGroupDeleteRepository ?: EmailTemplateGroupDeleteRepository::new();
    }

    /**
     * @param EmailTemplateGroupDeleteRepository $emailTemplateGroupDeleteRepository
     * @return static
     * @internal
     */
    public function setEmailTemplateGroupDeleteRepository(EmailTemplateGroupDeleteRepository $emailTemplateGroupDeleteRepository): static
    {
        $this->emailTemplateGroupDeleteRepository = $emailTemplateGroupDeleteRepository;
        return $this;
    }
}
