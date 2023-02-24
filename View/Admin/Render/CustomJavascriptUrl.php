<?php
/**
 * Render custom javascript link
 *
 * SAM-11610: Custom admin .js
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 28, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Render;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class CustomJavascriptUrl
 * @package Sam\View\Responsive\Render
 */
class CustomJavascriptUrl extends CustomizableClass
{
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $customJsUrl = $this->getSettingsManager()->getForSystem(Constants\Setting::ADMIN_CUSTOM_JS_URL);
        $output = $customJsUrl ? HtmlRenderer::new()->script($customJsUrl) : '';
        return $output;
    }
}
