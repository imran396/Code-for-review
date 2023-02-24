<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\MailingListTemplates;

trait MailingListTemplatesDeleteRepositoryCreateTrait
{
    protected ?MailingListTemplatesDeleteRepository $mailingListTemplatesDeleteRepository = null;

    protected function createMailingListTemplatesDeleteRepository(): MailingListTemplatesDeleteRepository
    {
        return $this->mailingListTemplatesDeleteRepository ?: MailingListTemplatesDeleteRepository::new();
    }

    /**
     * @param MailingListTemplatesDeleteRepository $mailingListTemplatesDeleteRepository
     * @return static
     * @internal
     */
    public function setMailingListTemplatesDeleteRepository(MailingListTemplatesDeleteRepository $mailingListTemplatesDeleteRepository): static
    {
        $this->mailingListTemplatesDeleteRepository = $mailingListTemplatesDeleteRepository;
        return $this;
    }
}
