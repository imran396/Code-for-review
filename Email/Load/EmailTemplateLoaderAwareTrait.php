<?php
/**
 * SAM-4502 : Email template modules
 * https://bidpath.atlassian.net/browse/SAM-4502
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/17/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Load;

/**
 * Trait EmailTemplateLoaderAwareTrait
 * @package Sam\Email\Load
 */
trait EmailTemplateLoaderAwareTrait
{
    /**
     * @var EmailTemplateLoader|null
     */
    protected ?EmailTemplateLoader $emailTemplateLoader = null;

    /**
     * @return EmailTemplateLoader
     */
    protected function getEmailTemplateLoader(): EmailTemplateLoader
    {
        if ($this->emailTemplateLoader === null) {
            $this->emailTemplateLoader = EmailTemplateLoader::new();
        }
        return $this->emailTemplateLoader;
    }

    /**
     * @param EmailTemplateLoader $emailTemplateLoader
     * @return static
     * @internal
     */
    public function setEmailTemplateLoader(EmailTemplateLoader $emailTemplateLoader): static
    {
        $this->emailTemplateLoader = $emailTemplateLoader;
        return $this;
    }
}
