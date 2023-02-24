<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template;

/**
 * Trait SmsTemplateRendererCreateTrait
 * @package Sam\Sms\Template
 */
trait SmsTemplateRendererCreateTrait
{
    protected ?SmsTemplateRenderer $smsTemplateRenderer = null;

    /**
     * @return SmsTemplateRenderer
     */
    protected function createSmsTemplateRenderer(): SmsTemplateRenderer
    {
        return $this->smsTemplateRenderer ?: SmsTemplateRenderer::new();
    }

    /**
     * @param SmsTemplateRenderer $smsTemplateRenderer
     * @return static
     * @internal
     */
    public function setSmsTemplateRenderer(SmsTemplateRenderer $smsTemplateRenderer): static
    {
        $this->smsTemplateRenderer = $smsTemplateRenderer;
        return $this;
    }
}
