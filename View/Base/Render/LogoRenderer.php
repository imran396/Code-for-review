<?php
/**
 * Helper methods for header logo renderer
 *
 * SAM-4578: Page header-Logo or text doesn't seem to be updated at front-end.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 7, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\Render;

use Sam\Application\Url\Build\Config\Image\HeaderImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class LogoRenderer
 * @package Sam\View\Base
 */
class LogoRenderer extends CustomizableClass
{
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;
    use UrlBuilderAwareTrait;

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
        $accountId = $this->getSystemAccountId();
        $logoType = $this->getSettingsManager()->get(Constants\Setting::PAGE_HEADER_TYPE, $accountId);
        $output = match ($logoType) {
            Constants\SettingUi::PHT_LOGO => $this->renderImageLogo(),
            Constants\SettingUi::PHT_URL => $this->renderUrl(),
            Constants\SettingUi::PHT_TEXT => $this->renderTextLogo(),
            default => '',
        };
        return $output;
    }

    /**
     * @param string|null $url
     * @param int $accountId
     * @return string
     */
    protected function makeImageLogo(?string $url, int $accountId): string
    {
        $headerImageUrl = $this->getUrlBuilder()->build(
            HeaderImageUrlConfig::new()->construct($accountId)
        );
        $output = <<<HTML
<div id="logo-container" class="bidder">
    <div class="logo-container-inner">
       <a href="{$url}"><img src="{$headerImageUrl}"></a>
    </div>
</div>
HTML;
        return $output;
    }

    /**
     * @return string
     */
    protected function renderImageLogo(): string
    {
        $output = '';
        $accountId = $this->getSystemAccountId();
        $isLogoUploaded = $this->getSettingsManager()->get(Constants\Setting::PAGE_HEADER, $accountId) !== '';
        if ($isLogoUploaded) {
            $logoUrl = $this->getSettingsManager()->get(Constants\Setting::LOGO_LINK, $accountId);
            $output = $this->makeImageLogo($logoUrl, $accountId);
        }
        return $output;
    }

    /**
     * @return string
     */
    protected function renderUrl(): string
    {
        $output = '';
        $accountId = $this->getSystemAccountId();
        $pageHeader = $this->getSettingsManager()->get(Constants\Setting::PAGE_HEADER, $accountId);
        if ($pageHeader) {
            $logoUrl = (string)$this->getSettingsManager()->get(Constants\Setting::LOGO_LINK, $accountId);
            $output = $this->makeTextLogo($pageHeader, $logoUrl);
        }
        return $output;
    }

    /**
     * @param string $text
     * @param string $url
     * @return string
     */
    protected function makeTextLogo(string $text, string $url = ''): string
    {
        $output = '';
        $isText = trim($text) !== '';
        if ($isText) {
            $link = $url ? sprintf("<a href=\"%s\">%s</a>", $url, $text) : $text;
            $output = <<<HTML
<div id="logo-container" class="bidder">
    <div class="logo-container-inner">
       {$link}
    </div>
</div>
HTML;
        }
        return $output;
    }

    /**
     * @return string
     */
    protected function renderTextLogo(): string
    {
        $accountId = $this->getSystemAccountId();
        $text = $this->getSettingsManager()->get(Constants\Setting::PAGE_HEADER, $accountId);
        $output = $this->makeTextLogo($text);
        return $output;
    }
}
