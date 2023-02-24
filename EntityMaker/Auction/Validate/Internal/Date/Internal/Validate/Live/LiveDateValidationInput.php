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

namespace Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Live;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Dto\AuctionMakerConfigDto;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputDto;
use Sam\EntityMaker\Base\Common\Mode;

/**
 * Class LiveDateValidationInput
 * @package Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Live
 */
class LiveDateValidationInput extends CustomizableClass
{
    public readonly Mode $mode;
    public readonly ?int $auctionId;
    public readonly ?string $startClosingDate;
    public readonly ?string $endPrebiddingDate;
    public readonly ?string $biddingConsoleAccessDate;
    public readonly ?string $liveEndDate;

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
        ?string $startClosingDate,
        ?string $endPrebiddingDate,
        ?string $biddingConsoleAccessDate,
        ?string $liveEndDate
    ): static {
        $this->mode = $mode;
        $this->auctionId = $auctionId;
        $this->startClosingDate = $startClosingDate;
        $this->endPrebiddingDate = $endPrebiddingDate;
        $this->biddingConsoleAccessDate = $biddingConsoleAccessDate;
        $this->liveEndDate = $liveEndDate;
        return $this;
    }

    public function fromMakerDto(
        AuctionMakerInputDto $inputDto,
        AuctionMakerConfigDto $configDto
    ): static {
        return $this->construct(
            $configDto->mode,
            Cast::toInt($inputDto->id),
            $inputDto->startClosingDate,
            $inputDto->endPrebiddingDate,
            $inputDto->biddingConsoleAccessDate,
            $inputDto->liveEndDate
        );
    }

    public function logData(): array
    {
        return [
            'mode' => $this->mode,
            'startClosingDate' => $this->startClosingDate,
            'endPrebiddingDate' => $this->endPrebiddingDate,
            'biddingConsoleAccessDate' => $this->biddingConsoleAccessDate,
            'liveEndDate' => $this->liveEndDate,
        ];
    }
}
