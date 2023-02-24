<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\MailingListTemplates;

trait MailingListTemplatesWriteRepositoryAwareTrait
{
    protected ?MailingListTemplatesWriteRepository $mailingListTemplatesWriteRepository = null;

    protected function getMailingListTemplatesWriteRepository(): MailingListTemplatesWriteRepository
    {
        if ($this->mailingListTemplatesWriteRepository === null) {
            $this->mailingListTemplatesWriteRepository = MailingListTemplatesWriteRepository::new();
        }
        return $this->mailingListTemplatesWriteRepository;
    }

    /**
     * @param MailingListTemplatesWriteRepository $mailingListTemplatesWriteRepository
     * @return static
     * @internal
     */
    public function setMailingListTemplatesWriteRepository(MailingListTemplatesWriteRepository $mailingListTemplatesWriteRepository): static
    {
        $this->mailingListTemplatesWriteRepository = $mailingListTemplatesWriteRepository;
        return $this;
    }
}
