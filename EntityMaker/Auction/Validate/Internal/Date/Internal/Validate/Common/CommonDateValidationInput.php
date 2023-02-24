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

namespace Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Common;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Dto\AuctionMakerConfigDto;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputDto;
use Sam\EntityMaker\Base\Common\Mode;

/**
 * Class CommonDateValidationInput
 * @package Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Common
 */
class CommonDateValidationInput extends CustomizableClass
{
    public readonly Mode $mode;
    public readonly ?string $publishDate;
    public readonly ?string $unpublishDate;
    public readonly ?string $startRegisterDate;
    public readonly ?string $endRegisterDate;
    public readonly ?string $startBiddingDate;

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
        ?string $publishDate,
        ?string $unpublishDate,
        ?string $startRegisterDate,
        ?string $endRegisterDate,
        ?string $startBiddingDate
    ): static {
        $this->mode = $mode;
        $this->publishDate = $publishDate;
        $this->unpublishDate = $unpublishDate;
        $this->startRegisterDate = $startRegisterDate;
        $this->endRegisterDate = $endRegisterDate;
        $this->startBiddingDate = $startBiddingDate;
        return $this;
    }

    public function fromMakerDto(
        AuctionMakerInputDto $inputDto,
        AuctionMakerConfigDto $configDto,
    ): static {
        return $this->construct(
            $configDto->mode,
            $inputDto->publishDate,
            $inputDto->unpublishDate,
            $inputDto->startRegisterDate,
            $inputDto->endRegisterDate,
            $inputDto->startBiddingDate
        );
    }

    public function logData(): array
    {
        return [
            'mode' => $this->mode->name,
            'publishDate' => $this->publishDate,
            'unpublishDate' => $this->unpublishDate,
            'startRegisterDate' => $this->startRegisterDate,
            'endRegisterDate' => $this->endRegisterDate,
            'startBiddingDate' => $this->startBiddingDate,
        ];
    }
}
