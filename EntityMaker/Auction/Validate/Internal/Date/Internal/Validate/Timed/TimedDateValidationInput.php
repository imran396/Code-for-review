<?php
/**
 * SAM-10450: Decouple auction date validation logic into internal services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Timed;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Dto\AuctionMakerConfigDto;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputDto;
use Sam\EntityMaker\Base\Common\Mode;

/**
 * Class TimedDateValidationInput
 * @package Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Timed
 */
class TimedDateValidationInput extends CustomizableClass
{
    public readonly Mode $mode;
    public readonly ?int $auctionId;
    public readonly ?string $eventType;
    public readonly ?int $eventTypeId;
    public readonly ?int $dateAssignmentStrategy;
    public readonly ?string $startClosingDate;
    public readonly ?string $startBiddingDate;
    public readonly ?string $timezone;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        Mode $mode,
        ?int $auctionId,
        ?string $eventType,
        ?int $eventTypeId,
        ?int $dateAssignmentStrategy,
        ?string $startClosingDate,
        ?string $startBiddingDate,
        ?string $timezone
    ): static {
        $this->mode = $mode;
        $this->auctionId = $auctionId;
        $this->eventType = $eventType;
        $this->eventTypeId = $eventTypeId;
        $this->dateAssignmentStrategy = $dateAssignmentStrategy;
        $this->startClosingDate = $startClosingDate;
        $this->startBiddingDate = $startBiddingDate;
        $this->timezone = $timezone;
        return $this;
    }

    public function fromMakerDto(
        AuctionMakerInputDto $inputDto,
        AuctionMakerConfigDto $configDto
    ): static {
        return $this->construct(
            $configDto->mode,
            Cast::toInt($inputDto->id),
            $inputDto->eventType,
            Cast::toInt($inputDto->eventTypeId),
            Cast::toInt($inputDto->dateAssignmentStrategy),
            $inputDto->startClosingDate,
            $inputDto->startBiddingDate,
            $inputDto->timezone
        );
    }

    public function logData(): array
    {
        return [
            'mode' => $this->mode->name,
            'auctionId' => $this->auctionId,
            'eventType' => $this->eventType,
            'eventTypeId' => $this->eventTypeId,
            'dateAssignmentStrategy' => $this->dateAssignmentStrategy,
            'startClosingDate' => $this->startClosingDate,
            'startBiddingDate' => $this->startBiddingDate,
            'timezone' => $this->timezone,
        ];
    }
}
