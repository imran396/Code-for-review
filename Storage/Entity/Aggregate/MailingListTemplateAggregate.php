<?php
/**
 * SAM-4819: Entity aware traits
 *
 * Aggregate class can be used, when we need to operate we several MailingListTemplate entities in one class namespace.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\Aggregate;

use Sam\Report\MailingList\Load\MailingListTemplateLoaderAwareTrait;
use MailingListTemplates;

/**
 * Class MailingListTemplateAggregate
 * @package Sam\Storage\Entity\Aggregate
 */
class MailingListTemplateAggregate extends EntityAggregateBase
{
    use MailingListTemplateLoaderAwareTrait;

    private ?int $mailingListTemplateId = null;
    private ?MailingListTemplates $mailingListTemplate = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Empty aggregated entities
     * @return static
     */
    public function clear(): static
    {
        $this->mailingListTemplateId = null;
        $this->mailingListTemplate = null;
        return $this;
    }

    // --- mailing_list_templates.id ---

    /**
     * @return int|null
     */
    public function getMailingListTemplateId(): ?int
    {
        return $this->mailingListTemplateId;
    }

    /**
     * @param int|null $mailingListTemplateId
     * @return static
     */
    public function setMailingListTemplateId(?int $mailingListTemplateId): static
    {
        $mailingListTemplateId = $mailingListTemplateId ?: null;
        if ($this->mailingListTemplateId !== $mailingListTemplateId) {
            $this->clear();
        }
        $this->mailingListTemplateId = $mailingListTemplateId;
        return $this;
    }

    // --- MailingListTemplates ---

    /**
     * @return bool
     */
    public function hasMailingListTemplate(): bool
    {
        return ($this->mailingListTemplate !== null);
    }

    /**
     * Return MailingListTemplates object
     * @param bool $isReadOnlyDb
     * @return MailingListTemplates|null
     */
    public function getMailingListTemplate(bool $isReadOnlyDb = false): ?MailingListTemplates
    {
        if ($this->mailingListTemplate === null) {
            $this->mailingListTemplate = $this->getMailingListTemplateLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->load($this->mailingListTemplateId, $isReadOnlyDb);
        }
        return $this->mailingListTemplate;
    }

    /**
     * @param MailingListTemplates|null $mailingListTemplate
     * @return static
     */
    public function setMailingListTemplate(?MailingListTemplates $mailingListTemplate = null): static
    {
        if (!$mailingListTemplate) {
            $this->clear();
        } elseif ($mailingListTemplate->Id !== $this->mailingListTemplateId) {
            $this->clear();
            $this->mailingListTemplateId = $mailingListTemplate->Id;
        }
        $this->mailingListTemplate = $mailingListTemplate;
        return $this;
    }
}
