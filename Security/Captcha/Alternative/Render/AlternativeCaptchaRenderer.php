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

use Sam\Core\Service\CustomizableClass;

/**
 * Class AlternativeCaptchaRenderer
 * @package Sam\Security\Captcha\Alternative\Render
 */
class AlternativeCaptchaRenderer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Render honey pot text box and alternative checkbox
     *
     * @param string $textBoxHtml
     * @return string
     */
    public function render(string $textBoxHtml): string
    {
        $output = <<<HTML

<div class="info-alt" style="clear:both;text-align:left;">
    <label>optional</label>
    {$textBoxHtml}
</div>
<div class="checkbox-alt" style="clear:both;text-align:left;">
</div>

HTML;
        return $output;
    }
}
