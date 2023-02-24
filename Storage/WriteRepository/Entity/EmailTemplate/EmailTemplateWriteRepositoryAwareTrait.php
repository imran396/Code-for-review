<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\EmailTemplate;

trait EmailTemplateWriteRepositoryAwareTrait
{
    protected ?EmailTemplateWriteRepository $emailTemplateWriteRepository = null;

    protected function getEmailTemplateWriteRepository(): EmailTemplateWriteRepository
    {
        if ($this->emailTemplateWriteRepository === null) {
            $this->emailTemplateWriteRepository = EmailTemplateWriteRepository::new();
        }
        return $this->emailTemplateWriteRepository;
    }

    /**
     * @param EmailTemplateWriteRepository $emailTemplateWriteRepository
     * @return static
     * @internal
     */
    public function setEmailTemplateWriteRepository(EmailTemplateWriteRepository $emailTemplateWriteRepository): static
    {
        $this->emailTemplateWriteRepository = $emailTemplateWriteRepository;
        return $this;
    }
}
