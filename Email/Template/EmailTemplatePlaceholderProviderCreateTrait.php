<?php
/**
 * SAM-7960: Refactor Email_Placeholders class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Template;

/**
 * Trait EmailTemplatePlaceholderProviderCreateTrait
 * @package Sam\Email\Template
 */
trait EmailTemplatePlaceholderProviderCreateTrait
{
    /**
     * @var EmailTemplatePlaceholderProvider|null
     */
    protected ?EmailTemplatePlaceholderProvider $emailTemplatePlaceholderProvider = null;

    /**
     * @return EmailTemplatePlaceholderProvider
     */
    protected function createEmailTemplatePlaceholderProvider(): EmailTemplatePlaceholderProvider
    {
        return $this->emailTemplatePlaceholderProvider ?: EmailTemplatePlaceholderProvider::new();
    }

    /**
     * @param EmailTemplatePlaceholderProvider $emailTemplatePlaceholderProvider
     * @return static
     * @internal
     */
    public function setEmailTemplatePlaceholderProvider(EmailTemplatePlaceholderProvider $emailTemplatePlaceholderProvider): static
    {
        $this->emailTemplatePlaceholderProvider = $emailTemplatePlaceholderProvider;
        return $this;
    }
}
