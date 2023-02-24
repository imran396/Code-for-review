<?php
/**
 * SAM-9373: Refactor play sound to avoid client side caching of stale files
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\SoundUrl;

use Sam\Application\Url\Build\Config\Asset\RtbMessageSoundUrlConfig;
use Sam\Application\Url\Build\Config\Asset\SoundUrlConfig;
use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Sound\LiveSale\Path\LiveSaleSoundFilePathResolverCreateTrait;
use Sam\Sound\RtbMessage\Path\RtbMessageSoundFilePathResolverCreateTrait;

/**
 * Class SoundUrlPathBuilder
 * @package Sam\Application\Url\Build\Internal\SoundUrl
 */
class SoundUrlPathBuilder extends CustomizableClass
{
    use FilePathHelperAwareTrait;
    use LiveSaleSoundFilePathResolverCreateTrait;
    use RtbMessageSoundFilePathResolverCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        return $this;
    }

    /**
     * Build prefixed url path with modification timestamp
     * @param string $urlPath
     * @param AbstractUrlConfig $urlConfig
     * @return string
     */
    public function build(string $urlPath, AbstractUrlConfig $urlConfig): string
    {
        $fileRootPath = '';
        if ($urlConfig instanceof RtbMessageSoundUrlConfig) {
            $fileRootPath = $this->createRtbMessageSoundFilePathResolver()
                ->detectFileRootPath($urlConfig->rtbMessageId(), $urlConfig->accountId());
        }

        if ($urlConfig instanceof SoundUrlConfig) {
            $fileRootPath = $this->createLiveSaleSoundFilePathResolver()
                ->detectFileRootPath($urlConfig->soundId(), $urlConfig->accountId());
        }

        if (!$fileRootPath) {
            return $urlPath;
        }

        $modifyTimeParam = $this->getFilePathHelper()->findModifyTimeUrlParam($fileRootPath);
        $urlPathMTime = UrlParser::new()->replaceParams($urlPath, $modifyTimeParam);
        return $urlPathMTime;
    }
}
