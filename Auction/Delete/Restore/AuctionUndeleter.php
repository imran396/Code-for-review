<?php
/**
 * SAM-6856: Soft-deleted Auction restore
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Delete\Restore;

use Auction;
use Sam\Auction\Delete\Restore\Internal\Load\DataProviderCreateTrait;
use Sam\Auction\Load\Exception\CouldNotFindAuction;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Delete\Restore\AuctionCustomDataUndeleterCreateTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;
use Sam\Auction\Delete\Restore\AuctionUndeleteResult as Result;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class AuctionUndeleter
 * @package Sam\Auction\Delete\Restore
 */
class AuctionUndeleter extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use AuctionCustomDataUndeleterCreateTrait;
    use AuctionWriteRepositoryAwareTrait;
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Restore deleted auction
     *
     * @param int $auctionId
     * @param int $editorUserId
     * @return Result
     */
    public function undelete(int $auctionId, int $editorUserId): Result
    {
        $auction = $this->createDataProvider()->loadAuction($auctionId, true);
        $result = $this->validateAuction($auction);
        if ($result->hasError()) {
            return $result;
        }

        if (!$auction) {
            throw CouldNotFindAuction::withId($auctionId);
        }
        return $this->undeleteAuction($auction, $editorUserId);
    }

    /**
     * @param Auction $auction
     * @param int $editorUserId
     * @return Result
     */
    protected function undeleteAuction(Auction $auction, int $editorUserId): Result
    {
        $customDataRestoreResult = $this->createAuctionCustomDataUndeleter()
            ->undeleteForAuctionId($auction->Id, $editorUserId);
        $auction->toActive();
        $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);

        $result = Result::new()
            ->setAdminTranslator($this->getAdminTranslator())
            ->construct();
        $result->setRestoredAuction($auction);
        $result->setCustomDataRestoreResult($customDataRestoreResult);
        return $result;
    }

    /**
     * @param Auction|null $auction
     * @return Result
     */
    protected function validateAuction(?Auction $auction): Result
    {
        $result = Result::new()
            ->setAdminTranslator($this->getAdminTranslator())
            ->construct();

        if (!$auction) {
            $result->addError(Result::ERR_AUCTION_NOT_FOUND);
            return $result;
        }

        if (!$auction->isDeleted()) {
            $result->addError(Result::ERR_AUCTION_IS_NOT_DELETED);
            return $result;
        }

        return $result;
    }

}
