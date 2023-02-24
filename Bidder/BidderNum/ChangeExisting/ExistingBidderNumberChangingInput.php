<?php
/**
 * SAM-5041: Soap API RegisterBidder improvement
 * SAM-10968: Reject bidder# assigning of flagged users
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\BidderNum\ChangeExisting;

use Sam\Core\Service\CustomizableClass;

/**
 * Class BidderNumberApplyingInput
 * @package Sam\Bidder\BidderNum\ChangeExisting
 */
class ExistingBidderNumberChangingInput extends CustomizableClass
{
    public string $bidderNumber;
    public int $userId;
    public int $auctionId;
    public int $editorUserId;
    public bool $isReadOnlyDb;
    /**
     * When false, then we reject to change existing bidder# of flagged user (BLK, NAA).
     * When true, then we allow to change existing bidder# of flagged user, e.g. by "RegisterBidder" SOAP with the "ForceUpdateBidderNumber" attribute of "Y" value.
     * It doesn't influence on disapproving (dropping of bidder#). I.e. bidder# can always be dropped for flagged and un-flagged user.
     * @var bool
     */
    public bool $canModifyFlaggedUser = false;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $bidderNumber,
        int $userId,
        int $auctionId,
        int $editorUserId,
        bool $canModifyFlaggedUser = false,
        bool $isReadOnlyDb = false,
    ): static {
        $this->bidderNumber = $bidderNumber;
        $this->userId = $userId;
        $this->auctionId = $auctionId;
        $this->editorUserId = $editorUserId;
        $this->canModifyFlaggedUser = $canModifyFlaggedUser;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }

    public function logData(): array
    {
        return [
            'bidder#' => $this->bidderNumber,
            'u' => $this->userId,
            'a' => $this->auctionId,
            'editor u' => $this->editorUserId,
        ];
    }
}
