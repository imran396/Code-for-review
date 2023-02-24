<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Process;

use AuctionLotItem;
use LotItem;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use User;

/**
 * Contains errors that occurred while processing the row
 *
 * Class RowProcessResult
 */
class RowProcessResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_AUCTION_LOT_NOT_FOUND = 1;
    public const ERR_LOT_ITEM_NOT_FOUND = 2;
    public const ERR_WRONG_INPUT_ABSENT_HP_BUT_PRESENT_WB = 3;

    protected const ERROR_MESSAGES = [
        self::ERR_AUCTION_LOT_NOT_FOUND => 'Auction lot not found',
        self::ERR_LOT_ITEM_NOT_FOUND => 'Lot item not found',
        self::ERR_WRONG_INPUT_ABSENT_HP_BUT_PRESENT_WB => 'Incorrect input, because hammer price is absent, when winning bidder email presents'
    ];

    public readonly ?LotItem $lotItem;
    public readonly ?AuctionLotItem $auctionLot;
    public readonly ?User $winningUser;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItem|null $lotItem
     * @param AuctionLotItem|null $auctionLot
     * @param User|null $winningUser
     * @return static
     */
    public function construct(?LotItem $lotItem = null, ?AuctionLotItem $auctionLot = null, ?User $winningUser = null): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        $this->lotItem = $lotItem;
        $this->auctionLot = $auctionLot;
        $this->winningUser = $winningUser;
        return $this;
    }

    // --- Mutation logic ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    // --- Query logic ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(string $glue = ', '): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }
}
