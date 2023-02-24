<?php
/**
 *
 * SAM-4578: Refactor CssOverride view helper to customized class
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

namespace Sam\View\Admin\Render;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class CssOverride
 * @package Sam\View\Responsive\Render
 */
class CssOverride extends CustomizableClass
{
    use FileManagerCreateTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function render(): string
    {
        $cssFilePath = (string)$this->getSettingsManager()->getForSystem(Constants\Setting::ADMIN_CSS_FILE);
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
