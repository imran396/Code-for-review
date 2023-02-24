<?php
/**
 *
 * SAM-4748: Mailing List Template management classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-05
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\MailingList\Load;

/**
 * Trait MailingListTemplateLoaderAwareTrait
 * @package Sam\Report\MailingList\Load
 */
trait MailingListTemplateLoaderAwareTrait
{
    protected ?MailingListTemplateLoader $mailingListTemplateLoader = null;

    /**
     * @return MailingListTemplateLoader
     */
    protected function getMailingListTemplateLoader(): MailingListTemplateLoader
    {
        if ($this->mailingListTemplateLoader === null) {
            $this->mailingListTemplateLoader = MailingListTemplateLoader::new();
        }
        return $this->mailingListTemplateLoader;
    }

    /**
     * @param MailingListTemplateLoader $mailingListTemplateLoader
     * @return static
     * @internal
     */
    public function setMailingListTemplateLoader(MailingListTemplateLoader $mailingListTemplateLoader): static
    {
        $this->mailingListTemplateLoader = $mailingListTemplateLoader;
        return $this;
    }
}
