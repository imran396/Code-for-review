<?php
/**
 * Render header, footer
 * Get TermsAndConditions responsive footer from db, load {url:...} tags content if exist and save them into cache
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

use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Application\LookAndFeel\Customization\Switch\LookAndFeelCustomizationTumblerCreateTrait;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settings\TermsAndConditionsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class LayoutRenderer
 * @package Sam\View\Responsive\Render
 */
class LayoutRenderer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use CookieHelperCreateTrait;
    use FilesystemCacheManagerAwareTrait;
    use LookAndFeelCustomizationTumblerCreateTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;
    use TermsAndConditionsManagerAwareTrait;

    private const PART_FOOTER = 'footer';
    private const PART_HEADER = 'header';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $part
     * @return string
     */
    public function render(string $part): string
    {
        if ($this->createLookAndFeelCustomizationTumbler()->isOff()) {
            return '';
        }

        if ($this->createCookieHelper()->hasMapp()) {
            return '';
        }

        switch ($part) {
            case self::PART_HEADER:
                $content = $this->getSettingsManager()->getForSystem(Constants\Setting::RESPONSIVE_HEADER_ADDRESS);
                $content = $content ? HtmlRenderer::new()->div($content, ['id' => 'custom-header']) : '';
                break;
            case self::PART_FOOTER:
                $terms = $this->getTermsAndConditionsManager()->load(
                    $this->getSystemAccountId(),
                    Constants\TermsAndConditions::RESPONSIVE_FOOTER,
                    true
                );
                $content = $terms->Content ?? '';
                break;
            default:
                $content = '';
        }

        return $this->loadUrls($content);
    }

    /**
     * If content contains {url:...} tags, change these urls by their content
     * @param string $content
     * @return string
     */
    protected function loadUrls(string $content): string
    {
        if (strpos($content, '{url:')) {
            $content = preg_replace_callback('/({url:([^}]*))}/', [$this, 'replaceUrlByUrlContent'], $content);
        }
        return $content;
    }

    /**
     * Replace url by url content, save to cache
     * Get url tag content
     * @param array $matches
     * @return string
     */
    protected function replaceUrlByUrlContent(array $matches): string
    {
        if (!isset($matches[2])) {
            return '';
        }
        if (!$this->isValidUrl($matches[2])) {
            return '';
        }

        $url = $matches[2];

        $cacheManager = $this->getFilesystemCacheManager()
            ->setExtension('txt')
            ->setNamespace('header-address-url');
        $urlTagContent = $cacheManager->get($url);
        if (!$urlTagContent) {
            $urlTagContent = @file_get_contents($url) ?: '';
            $cacheManager->set($url, $urlTagContent, $this->cfg()->get('core->app->headerFooterRemoteUrlCachingTimeout'));
        }
        return $urlTagContent;
    }

    /**
     * Check if url is valid
     * @param string $url
     * @return bool
     */
    protected function isValidUrl(string $url): bool
    {
        return preg_match('/^https?:\/\//i', $url);
    }
}
