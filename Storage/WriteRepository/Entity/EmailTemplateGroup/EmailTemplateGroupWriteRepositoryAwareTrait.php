<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\EmailTemplateGroup;

trait EmailTemplateGroupWriteRepositoryAwareTrait
{
    protected ?EmailTemplateGroupWriteRepository $emailTemplateGroupWriteRepository = null;

    protected function getEmailTemplateGroupWriteRepository(): EmailTemplateGroupWriteRepository
    {
        if ($this->emailTemplateGroupWriteRepository === null) {
            $this->emailTemplateGroupWriteRepository = EmailTemplateGroupWriteRepository::new();
        }
        return $this->emailTemplateGroupWriteRepository;
    }

    /**
     * @param EmailTemplateGroupWriteRepository $emailTemplateGroupWriteRepository
     * @return static
     * @internal
     */
    public function setEmailTemplateGroupWriteRepository(EmailTemplateGroupWriteRepository $emailTemplateGroupWriteRepository): static
    {
        $this->emailTemplateGroupWriteRepository = $emailTemplateGroupWriteRepository;
        return $this;
    }
}
