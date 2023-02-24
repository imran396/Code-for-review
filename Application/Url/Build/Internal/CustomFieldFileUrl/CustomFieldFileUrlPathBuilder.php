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

namespace Sam\Application\Url\Build\Internal\CustomFieldFileUrl;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\CustomField\AuctionCustomFieldFileUrlConfig;
use Sam\Application\Url\Build\Config\CustomField\LotCustomFieldFileUrlConfig;
use Sam\Application\Url\Build\Config\CustomField\UserCustomFieldFileUrlConfig;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Path\PathResolverCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;

/**
 * Class SoundUrlPathBuilder
 * @package Sam\Application\Url\Build\Internal\SoundUrl
 */
class CustomFieldFileUrlPathBuilder extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use FilePathHelperAwareTrait;
    use PathResolverCreateTrait;

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
        if (
            !$urlConfig instanceof UserCustomFieldFileUrlConfig
            && !$urlConfig instanceof AuctionCustomFieldFileUrlConfig
            && !$urlConfig instanceof LotCustomFieldFileUrlConfig
        ) {
            return $urlPath;
        }

        $fileRootPath = '';
        if ($urlConfig instanceof UserCustomFieldFileUrlConfig) {
            $fileRootPath = $this->path()->sysRoot() . $urlConfig->fileBasePath();
        } elseif ($urlConfig instanceof AuctionCustomFieldFileUrlConfig) {
            $row = $this->getAuctionLoader()->loadSelected(['account_id'], $urlConfig->auctionId());
            $accountId = Cast::toInt($row['account_id'] ?? null);
            if ($accountId) {
                $fileRootPath = $this->path()->sysRoot() . $urlConfig->fileBasePath($accountId);
            }
        } elseif ($urlConfig instanceof LotCustomFieldFileUrlConfig) {
            $row = $this->getLotItemLoader()->loadSelected(['account_id'], $urlConfig->lotItemId());
            $accountId = Cast::toInt($row['account_id'] ?? null);
            if ($accountId) {
                $fileRootPath = $this->path()->sysRoot() . $urlConfig->fileBasePath($accountId);
            }
        }

        $modifyTimeParam = $this->getFilePathHelper()->findModifyTimeUrlParam($fileRootPath);
        $urlPathMTime = UrlParser::new()->replaceParams($urlPath, $modifyTimeParam);
        return $urlPathMTime;
    }
}
