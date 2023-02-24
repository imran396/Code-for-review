<?php
/**
 * SAM-6481: Add bidding options on the admin - auction - lots - added lots table
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\PlaceBid\LiveOrHybrid;

use AuctionLotItem;
use InvalidArgumentException;
use Sam\Application\Controller\Admin\PlaceBid\Internal\Command\PlaceBidCommandValidatorCreateTrait;
use Sam\Application\Controller\Admin\PlaceBid\PlaceBidCommand;
use Sam\Application\Controller\Admin\PlaceBid\PlaceBidValidatorInterface;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidding\AbsenteeBid\Validate\AbsenteeBidAmountValidator;
use Sam\Bidding\AbsenteeBid\Validate\AbsenteeBidAmountValidatorCreateTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\RtbGeneralHelperAwareTrait;

/**
 * Special validator for live and hybrid absentee bids made by admin
 *
 * Class LiveOrHybridAbsenteeBidValidator
 * @package Sam\Application\Controller\Admin\PlaceBid\Internal\LiveOrHybrid
 */
class LiveOrHybridAbsenteeBidValidator extends CustomizableClass implements PlaceBidValidatorInterface
{
    use AbsenteeBidAmountValidatorCreateTrait;
    use AuctionLotLoaderAwareTrait;
    use PlaceBidCommandValidatorCreateTrait;
    use ResultStatusCollectorAwareTrait;
    use RtbGeneralHelperAwareTrait;

    public const ERR_INVALID_COMMAND = 1;
    public const ERR_BIDDING_DISABLED = 2;
    public const ERR_INVALID_AMOUNT = 3;

    protected const ERROR_MESSAGES = [
        self::ERR_INVALID_COMMAND => 'Invalid command',
        self::ERR_BIDDING_DISABLED => 'Bidding is disabled. Lot is already started in the live sale',
        self::ERR_INVALID_AMOUNT => 'Invalid amount',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param PlaceBidCommand $command
     * @return bool
     */
    public function validate(PlaceBidCommand $command): bool
    {
        $collector = $this->getResultStatusCollector()->construct(static::ERROR_MESSAGES);

        $commandValidator = $this->createPlaceBidCommandValidator();
        if (!$commandValidator->validate($command)) {
            $collector->addError(self::ERR_INVALID_COMMAND, $commandValidator->errorMessage());
            return false;
        }

        // Check if lot is running in clerk console, then disable absentee bids
        if ($this->getRtbGeneralHelper()->isRunningLot($command->auctionId, $command->lotItemId)) {
            $collector->addError(self::ERR_BIDDING_DISABLED);
            return false;
        }

        // Validate bid
        $auctionLot = $this->loadAuctionLotItem($command->lotItemId, $command->auctionId);
        $amountValidator = $this->constructAbsenteeBidAmountValidator($command->maxBidAmount, $auctionLot, $command->bidderId);
        if (!$amountValidator->validate()) {
            $collector->addError(self::ERR_INVALID_AMOUNT, $amountValidator->getErrorMessageForWebAdmin());
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @param float $maxBid
     * @param AuctionLotItem $auctionLot
     * @param int $userId
     * @return AbsenteeBidAmountValidator
     */
    protected function constructAbsenteeBidAmountValidator(
        float $maxBid,
        AuctionLotItem $auctionLot,
        int $userId
    ): AbsenteeBidAmountValidator {
        $absenteeBidAmountValidator = $this->createAbsenteeBidAmountValidator()
            ->setEditorUserId($userId)
            ->setAuctionLot($auctionLot)
            ->setMaxBid($maxBid);
        return $absenteeBidAmountValidator;
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @return AuctionLotItem
     */
    protected function loadAuctionLotItem(int $lotItemId, int $auctionId): AuctionLotItem
    {
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId);
        if (!$auctionLot) {
            throw new InvalidArgumentException(
                'Available auction lot item not found '
                . composeSuffix(['a' => $auctionId, 'li' => $lotItemId])
            );
        }
        return $auctionLot;
    }
}
