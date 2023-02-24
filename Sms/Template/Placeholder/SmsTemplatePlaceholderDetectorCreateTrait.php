<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder;

/**
 * Trait SmsTemplatePlaceholderDetectorCreateTrait
 * @package Sam\Sms\Template\Placeholder
 */
trait SmsTemplatePlaceholderDetectorCreateTrait
{
    protected ?SmsTemplatePlaceholderDetector $smsTemplatePlaceholderDetector = null;

    /**
     * @return SmsTemplatePlaceholderDetector
     */
    protected function createSmsTemplatePlaceholderDetector(): SmsTemplatePlaceholderDetector
    {
        return $this->smsTemplatePlaceholderDetector ?: SmsTemplatePlaceholderDetector::new();
    }

    /**
     * @param SmsTemplatePlaceholderDetector $smsTemplatePlaceholderDetector
     * @return static
     * @internal
     */
    public function setSmsTemplatePlaceholderDetector(SmsTemplatePlaceholderDetector $smsTemplatePlaceholderDetector): static
    {
        $this->smsTemplatePlaceholderDetector = $smsTemplatePlaceholderDetector;
        return $this;
    }
}
