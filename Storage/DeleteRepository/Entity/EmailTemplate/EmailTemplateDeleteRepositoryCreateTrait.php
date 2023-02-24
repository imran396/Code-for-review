<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\EmailTemplate;

trait EmailTemplateDeleteRepositoryCreateTrait
{
    protected ?EmailTemplateDeleteRepository $emailTemplateDeleteRepository = null;

    protected function createEmailTemplateDeleteRepository(): EmailTemplateDeleteRepository
    {
        return $this->emailTemplateDeleteRepository ?: EmailTemplateDeleteRepository::new();
    }

    /**
     * @param EmailTemplateDeleteRepository $emailTemplateDeleteRepository
     * @return static
     * @internal
     */
    public function setEmailTemplateDeleteRepository(EmailTemplateDeleteRepository $emailTemplateDeleteRepository): static
    {
        $this->emailTemplateDeleteRepository = $emailTemplateDeleteRepository;
        return $this;
    }
}
