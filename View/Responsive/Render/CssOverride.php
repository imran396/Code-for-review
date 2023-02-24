<?php
/**
 * SAM-4578: Refactor CssOverride view helper to customized class
 * SAM-11791: Allow external CSS resource in the Custom Responsive Css setting
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 4, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Render;

use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Application\LookAndFeel\Customization\Switch\LookAndFeelCustomizationTumblerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class CssOverride
 * @package Sam\View\Responsive\Render
 */
class CssOverride extends CustomizableClass
{
    use CookieHelperCreateTrait;
    use LookAndFeelCustomizationTumblerCreateTrait;
    use FileManagerCreateTrait;
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
        if ($this->createCookieHelper()->hasMapp()) {
            $output = $this->renderCssLink();
        } else {
            $output = $this->renderCss();
        }
        return $output;
    }

    /**
     * @return string
     */
    protected function renderCssLink(): string
    {
        $cssPath = match ($this->createCookieHelper()->getMapp()) {
            'iPhone', 'iPad' => '/m/css/mapp/app-ios.css',
            'Android' => '/m/css/mapp/app-android.css',
            default => '',
        };
        $genericCssPath = '/m/css/mapp/app-generic.css';
        $ts = @filemtime(@realpath(path()->docRoot() . $genericCssPath));
        $ts = $ts !== false ? '?' . $ts : '';
        $url = $genericCssPath . $ts;

        $cssLink = HtmlRenderer::new()
            ->cssLink($url);

        if ($cssPath) {
            $ts = @filemtime(@realpath(path()->docRoot() . $cssPath));
            $ts = $ts !== false ? '?' . $ts : '';
            $cssLink = HtmlRenderer::new()
                ->cssLink($cssPath . $ts);
        }
        return $cssLink;
    }

    /**
     * @return string
     */
    protected function renderCss(): string
    {
        $cssFilePath = (string)$this->getSettingsManager()->getForSystem(Constants\Setting::RESPONSIVE_CSS_FILE);
        if ($cssFilePath === '') {
            return '';
        }

        if (UrlParser::new()->hasHost($cssFilePath)) {
            $cssContent = '@import url("' . $cssFilePath . '");';
            $css = HtmlRenderer::new()->style($cssContent);
            return $css;
        }

        $fileManager = $this->createFileManager();
        $relativeCssFilePath = substr(path()->docRoot() . $cssFilePath, strlen(path()->sysRoot()));
        $ts = '';
        if ($fileManager->exist($relativeCssFilePath)) {
            $ts = str_starts_with($cssFilePath, '/')
                ? @filemtime(@realpath(path()->docRoot() . $cssFilePath))
                : false;
            $ts = $ts !== false ? '?' . $ts : '';
        }
        $cssContent = '@import url("' . $cssFilePath . $ts . '");';
        $css = HtmlRenderer::new()->style($cssContent);
        return $css;
    }
}
