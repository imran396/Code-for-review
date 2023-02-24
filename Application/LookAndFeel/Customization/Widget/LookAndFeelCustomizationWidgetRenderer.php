<?php
/**
 * SAM-11612: Tech support tool to easily and temporarily disable installation look and feel customizations
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\LookAndFeel\Customization\Widget;

use Sam\Application\LookAndFeel\Customization\Switch\LookAndFeelCustomizationTumblerCreateTrait;
use Sam\Application\Url\Build\Config\Base\OneBoolParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Lang\TranslatorAwareTrait;

/**
 * Class LookAndFeelCustomizationWidgetRenderer
 * @package Sam\Application\LookAndFeel\Customization\Widget
 */
class LookAndFeelCustomizationWidgetRenderer extends CustomizableClass
{
    use LookAndFeelCustomizationTumblerCreateTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function render(): string
    {
        if ($this->createLookAndFeelCustomizationTumbler()->isOn()) {
            return '';
        }

        $url = $this->getUrlBuilder()->build(
            OneBoolParamUrlConfig::new()->forWeb(Constants\Url::P_INDEX_ENABLE_CUSTOM_LOOK_AND_FEEL, true)
        );
        $transText = $this->getTranslator()->translate('GENERAL_ENABLE_LAYOUT_CUSTOMIZATION', 'general');
        $output = <<<HTML
<div class="customization-re-enable">
    <a href="{$url}" class="customization-re-enable-link">{$transText}</a>
</div>
HTML;
        return $output;
    }
}
