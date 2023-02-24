<?php
/**
 * Produce url path or base path for image like urls.
 * Url path should be prefixed according core->image->linkPrefix rule,
 * also file modification timestamp should be added as query string parameter.
 * Note, that we need ts for csv output too, since it identifies image change when keep filename.
 *
 * SAM-6695: Image link prefix detection do not provide default value and are not based on account of context
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\ImageUrl;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Image\Base\AbstractImageUrlConfig;
use Sam\Application\Url\Build\Config\Image\CaptchaImageUrlConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\File\FilePathHelperAwareTrait;

/**
 * Class ImageUrlPathBuilder
 * @package Sam\Application\Url\Build\Internal\ImageUrl
 */
class ImageUrlPathBuilder extends CustomizableClass
{
    use FilePathHelperAwareTrait;

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
        if (!$urlConfig instanceof AbstractImageUrlConfig) {
            return $urlPath;
        }

        $imageUrlPathMTime = $this->isModificationTimestamp($urlConfig)
            ? $this->getFilePathHelper()->appendUrlPathWithMTime($urlPath)
            : $urlPath;
        $imagePrefix = $this->isImagePrefix($urlConfig)
            ? path()->imagePrefix($urlConfig->accountId())
            : '';
        $prefixedUrlPath = $imagePrefix . $imageUrlPathMTime;
        return $prefixedUrlPath;
    }

    /**
     * No need in modification timestamp when it is dynamically generated captcha image,
     * because physical file does not exists.
     * @param AbstractUrlConfig $urlConfig
     * @return bool
     */
    protected function isModificationTimestamp(AbstractUrlConfig $urlConfig): bool
    {
        if ($urlConfig instanceof CaptchaImageUrlConfig) {
            return false;
        }
        return true;
    }

    /**
     * No need in image link prefix when it is dynamically generated captcha image,
     * because we want to keep the same domain for user session.
     * @param AbstractImageUrlConfig $urlConfig
     * @return bool
     */
    protected function isImagePrefix(AbstractImageUrlConfig $urlConfig): bool
    {
        if ($urlConfig instanceof CaptchaImageUrlConfig) {
            return false;
        }
        return true;
    }
}
