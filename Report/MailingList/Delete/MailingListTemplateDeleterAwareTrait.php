<?php
/**
 *
 * SAM-4748: Mailing List Template management classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-06
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\MailingList\Delete;

/**
 * Trait MailingListTemplateDeleterAwareTrait
 * @package Sam\Report\MailingList\Delete
 */
trait MailingListTemplateDeleterAwareTrait
{
    protected ?MailingListTemplateDeleter $mailingListTemplateDeleter = null;

    /**
     * @return MailingListTemplateDeleter
     */
    protected function getMailingListTemplateDeleter(): MailingListTemplateDeleter
    {
        if ($this->mailingListTemplateDeleter === null) {
            $this->mailingListTemplateDeleter = MailingListTemplateDeleter::new();
        }
        return $this->mailingListTemplateDeleter;
    }

    /**
     * @param MailingListTemplateDeleter $mailingListTemplateDeleter
     * @return static
     * @internal
     */
    public function setMailingListTemplateDeleter(MailingListTemplateDeleter $mailingListTemplateDeleter): static
    {
        $this->mailingListTemplateDeleter = $mailingListTemplateDeleter;
        return $this;
    }
}
