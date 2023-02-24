<?php
/**
 * Aware trait for MailingListTemplates entity
 *
 * SAM-4819: Entity aware traits
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/14/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\AwareTrait;

use Sam\Storage\Entity\Aggregate\MailingListTemplateAggregate;
use MailingListTemplates;

/**
 * Trait MailingListTemplateAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
trait MailingListTemplateAwareTrait
{
    protected ?MailingListTemplateAggregate $mailingListTemplateAggregate = null;

    /**
     * @return int|null
     */
    public function getMailingListTemplateId(): ?int
    {
        return $this->getMailingListTemplateAggregate()->getMailingListTemplateId();
    }

    /**
     * @param int|null $mailingListTemplateId
     * @return static
     */
    public function setMailingListTemplateId(?int $mailingListTemplateId): static
    {
        $this->getMailingListTemplateAggregate()->setMailingListTemplateId($mailingListTemplateId);
        return $this;
    }

    // --- MailingListTemplates ---

    /**
     * Return MailingListTemplates|null object
     * @param bool $isReadOnlyDb
     * @return MailingListTemplates|null
     */
    public function getMailingListTemplate(bool $isReadOnlyDb = false): ?MailingListTemplates
    {
        return $this->getMailingListTemplateAggregate()->getMailingListTemplate($isReadOnlyDb);
    }

    /**
     * @param MailingListTemplates|null $mailingListTemplate
     * @return static
     */
    public function setMailingListTemplate(?MailingListTemplates $mailingListTemplate): static
    {
        $this->getMailingListTemplateAggregate()->setMailingListTemplate($mailingListTemplate);
        return $this;
    }

    // --- MailingListTemplateAggregate ---

    /**
     * @return MailingListTemplateAggregate
     */
    protected function getMailingListTemplateAggregate(): MailingListTemplateAggregate
    {
        if ($this->mailingListTemplateAggregate === null) {
            $this->mailingListTemplateAggregate = MailingListTemplateAggregate::new();
        }
        return $this->mailingListTemplateAggregate;
    }
}
