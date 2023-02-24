<?php
/**
 * SAM-4722: Currency deleter
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\DeleteRepository\Entity\AuctionCurrency\AuctionCurrencyDeleteRepositoryCreateTrait;

/**
 * Class AuctionCurrencyDeleter
 * @package Sam\Currency\Delete
 */
class AuctionCurrencyDeleter extends CustomizableClass
{
    use AuctionCurrencyDeleteRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Remove all currencies from an auction
     * @param int $auctionId auction.id
     */
    public function deleteAll(int $auctionId): void
    {
        $this->createAuctionCurrencyDeleteRepository()
            ->filterAuctionId($auctionId)
            ->delete();
    }
}
