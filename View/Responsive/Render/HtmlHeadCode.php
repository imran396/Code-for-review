<?php
/**
 * Render custom head code
 *
 * SAM-11609: Custom <HEAD> section
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 22, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Render;

use Sam\Application\LookAndFeel\Customization\Switch\LookAndFeelCustomizationTumblerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class HtmlHeadCode
 * @package Sam\View\Responsive\Render
 */
class HtmlHeadCode extends CustomizableClass
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
        $headCode = $this->getSettingsManager()->getForSystem(Constants\Setting::RESPONSIVE_HTML_HEAD_CODE);
        return trim((string)$headCode) . "\n";
    }
}
