<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\MailingListTemplates;

trait MailingListTemplatesReadRepositoryCreateTrait
{
    protected ?MailingListTemplatesReadRepository $mailingListTemplatesReadRepository = null;

    protected function createMailingListTemplatesReadRepository(): MailingListTemplatesReadRepository
    {
        return $this->mailingListTemplatesReadRepository ?: MailingListTemplatesReadRepository::new();
    }

    /**
     * @param MailingListTemplatesReadRepository $mailingListTemplatesReadRepository
     * @return static
     * @internal
     */
    public function setMailingListTemplatesReadRepository(MailingListTemplatesReadRepository $mailingListTemplatesReadRepository): static
    {
        $this->mailingListTemplatesReadRepository = $mailingListTemplatesReadRepository;
        return $this;
    }
}
