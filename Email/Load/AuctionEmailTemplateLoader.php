<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/17/20
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Load;

use AuctionEmailTemplate;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\AuctionEmailTemplate\AuctionEmailTemplateReadRepositoryCreateTrait;

/**
 * Class AuctionEmailTemplateLoader
 * @package Sam\Email\Load
 */
class AuctionEmailTemplateLoader extends EntityLoaderBase
{
    use AuctionEmailTemplateReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $auctionEmailTemplateId
     * @param bool $isReadOnlyDb
     * @return AuctionEmailTemplate|null - null means not found
     */
    public function load(?int $auctionEmailTemplateId, bool $isReadOnlyDb = false): ?AuctionEmailTemplate
    {
        if (!$auctionEmailTemplateId) {
            return null;
        }
        $emailTemplate = $this->createAuctionEmailTemplateReadRepository()
            ->filterId($auctionEmailTemplateId)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->loadEntity();
        return $emailTemplate;
    }

    /**
     * @param int $auctionId
     * @param string $key
     * @param bool $isReadOnlyDb
     * @return AuctionEmailTemplate|null - null means not found
     */
    public function loadByKeyAuctionId(int $auctionId, string $key, bool $isReadOnlyDb = false): ?AuctionEmailTemplate
    {
        if (!$auctionId || $key === '') {
            return null;
        }

        $auctionEmailTemplate = $this->createAuctionEmailTemplateReadRepository()
            ->filterAuctionId($auctionId)
            ->filterKey($key)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->loadEntity();
        return $auctionEmailTemplate;
    }
}
