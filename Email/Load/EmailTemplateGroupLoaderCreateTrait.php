<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Load;

/**
 * Trait EmailTemplateGroupLoaderCreateTrait
 * @package Sam\Email\Load
 */
trait EmailTemplateGroupLoaderCreateTrait
{
    /**
     * @var EmailTemplateGroupLoader|null
     */
    protected ?EmailTemplateGroupLoader $emailTemplateGroupLoader = null;

    /**
     * @return EmailTemplateGroupLoader
     */
    protected function createEmailTemplateGroupLoader(): EmailTemplateGroupLoader
    {
        return $this->emailTemplateGroupLoader ?: EmailTemplateGroupLoader::new();
    }

    /**
     * @param EmailTemplateGroupLoader $loader
     * @return static
     * @internal
     */
    public function setEmailTemplateGroupLoader(EmailTemplateGroupLoader $loader): static
    {
        $this->emailTemplateGroupLoader = $loader;
        return $this;
    }
}
