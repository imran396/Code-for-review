<?php
/**
 * Soft-deletes auction and related entities.
 * (!) Adjust soft-deleted auction restoring logic, when new deleting operations will be added. See, \Sam\Entity\Restore\Cli\Command\Handler\AuctionRestoreCommandHandler
 *
 * SAM-4039: Auction deleter class
 * SAM-6671: Auction deleter for v3.5
 *
 * @author        Oleg Kovalov
 * @version       SAM 2.0
 * @since         May 13, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 *
 */

namespace Sam\Auction\Delete\Update;

use Auction;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Delete\AuctionCustomDataDeleterCreateTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;

/**
 * Class Deleter
 * @package Sam\Auction\Delete
 */
class AuctionDeleter extends CustomizableClass
{
    use AuctionCustomDataDeleterCreateTrait;
    use AuctionWriteRepositoryAwareTrait;

    protected ?Auction $deletedAuction = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Auction $auction
     * @param int $editorUserId
     * @return void
     */
    public function delete(Auction $auction, int $editorUserId): void
    {
        $this->createAuctionCustomDataDeleter()->deleteForAuctionId($auction->Id, $editorUserId);
        $auction->toDeleted();
        $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);
        $this->deletedAuction = $auction;
    }

    /**
     * @return Auction
     */
    public function deletedAuction(): Auction
    {
        return $this->deletedAuction;
    }
}
