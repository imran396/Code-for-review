<?php
/**
 * SAM-3528 : Captcha alternative on other pages
 * https://bidpath.atlassian.net/browse/SAM-3528
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/29/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Security\Captcha\Alternative\Render;

/**
 * Trait AlternativeCaptchaRendererAwareTrait
 * @package Sam\Security\Captcha\Alternative\Render
 */
trait AlternativeCaptchaRendererAwareTrait
{
    protected ?AlternativeCaptchaRenderer $alternativeCaptchaRenderer = null;

    /**
     * @return AlternativeCaptchaRenderer
     */
    protected function getAlternativeCaptchaRenderer(): AlternativeCaptchaRenderer
    {
        if ($this->alternativeCaptchaRenderer === null) {
            $this->alternativeCaptchaRenderer = AlternativeCaptchaRenderer::new();
        }
        return $this->alternativeCaptchaRenderer;
    }

    /**
     * @param AlternativeCaptchaRenderer $alternativeCaptchaRenderer
     * @return static
     * @internal
     */
    public function setAlternativeCaptchaRenderer(AlternativeCaptchaRenderer $alternativeCaptchaRenderer): static
    {
        $this->alternativeCaptchaRenderer = $alternativeCaptchaRenderer;
        return $this;
    }
}
