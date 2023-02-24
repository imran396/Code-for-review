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

namespace Sam\Application\Controller\Admin\PlaceBid\Timed;

use Sam\Application\Controller\Admin\PlaceBid\Internal\Command\PlaceBidCommandValidatorCreateTrait;
use Sam\Application\Controller\Admin\PlaceBid\PlaceBidCommand;
use Sam\Application\Controller\Admin\PlaceBid\PlaceBidValidatorInterface;
use Sam\AuctionLot\Load\AuctionLotCacheLoaderAwareTrait;
use Sam\Bidding\OnIncrementBid\OnIncrementBidServiceCreateTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Special validator for timed auction bid made by admin
 *
 * Class TimedBidValidator
 * @package Sam\Application\Controller\Admin\PlaceBid\Internal\Timed
 */
class TimedBidValidator extends CustomizableClass implements PlaceBidValidatorInterface
{
    use AuctionLotCacheLoaderAwareTrait;
    use OnIncrementBidServiceCreateTrait;
    use PlaceBidCommandValidatorCreateTrait;
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVALID_COMMAND = 1;
    public const ERR_OFF_INCREMENT_BID = 2;

    protected const ERROR_MESSAGES = [
        self::ERR_INVALID_COMMAND => 'Invalid command',
        self::ERR_OFF_INCREMENT_BID => '',
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
     * @return static
     */
    public function construct(): static
    {
        return $this;
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

        $onIncrementBidService = $this->createOnIncrementBidService()->construct(
            $command->maxBidAmount,
            $command->lotItemId,
            $command->auctionId
        );
        if (!$onIncrementBidService->validate()) {
            $collector->addError(
                self::ERR_OFF_INCREMENT_BID,
                $onIncrementBidService->buildCleanMessage()
            );
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
}
