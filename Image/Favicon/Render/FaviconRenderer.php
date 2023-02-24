<?php
/**
 * SAM-11607: Custom favicon
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 14, 2023
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image\Favicon\Render;

use Sam\Application\Application;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Image\Favicon\Path\FaviconImagePathResolverCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;


class FaviconRenderer extends CustomizableClass
{
    use FaviconImagePathResolverCreateTrait;
    use LocalFileManagerCreateTrait;
    use SettingsManagerAwareTrait;

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
        $accountId = Application::getInstance()->getSystemAccountId();
        $pathResolver = $this->createFaviconImagePathResolver();
        $fileManager = $this->createLocalFileManager()->withRootPath('');
        $baseFaviconPath = $pathResolver->makeBaseFaviconFileRootPath($accountId);
        $customFaviconExist = $fileManager->exist($baseFaviconPath);

        if (!$customFaviconExist) {
            return '<link rel="shortcut icon" href="/favicon.ico" />';
        }

        $appleIconPath = $pathResolver->makeRenderAppleFaviconPath($accountId);
        $mediumIconPath = $pathResolver->makeRenderMediumFaviconPath($accountId);
        $smallIconPath = $pathResolver->makeRenderSmallFaviconPath($accountId);
        $output = '<link rel="apple-touch-icon" sizes="180x180" href="' . $appleIconPath . '">
                        <link rel="icon" type="image/png" sizes="32x32" href="' . $mediumIconPath . '">
                        <link rel="icon" type="image/png" sizes="16x16" href="' . $smallIconPath . '">
                        <meta name="msapplication-TileColor" content="#da532c">
                        <meta name="theme-color" content="#ffffff">';
        return $output;
    }
}
