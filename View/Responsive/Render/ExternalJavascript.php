<?php
/**
 * Render external javascript link
 *
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 14, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Render;

use Sam\Application\LookAndFeel\Customization\Switch\LookAndFeelCustomizationTumblerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class ExternalJavascript
 * @package Sam\View\Responsive\Render
 */
class ExternalJavascript extends CustomizableClass
{
    use LookAndFeelCustomizationTumblerCreateTrait;
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
        if ($this->createLookAndFeelCustomizationTumbler()->isOff()) {
            return '';
        }
        $externalJavascript = $this->getSettingsManager()->getForSystem(Constants\Setting::EXTERNAL_JAVASCRIPT);
        $externalJavascript = trim($externalJavascript);
        return $externalJavascript ? HtmlRenderer::new()->script($externalJavascript) : '';
    }
}
