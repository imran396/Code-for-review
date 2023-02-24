<?php
/**
 * SAM-8107: Issues related to Validation and Values of Buyer's Premium
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Validate\Internal\BuyersPremium;

use InvalidArgumentException;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Dto\AuctionMakerConfigDto;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputDto;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;

/**
 * Class BuyersPremiumInput
 * @package
 */
class BuyersPremiumValidationInput extends CustomizableClass
{
    public readonly Mode $mode;
    public readonly ?string $buyersPremiumString;
    public readonly ?array $buyersPremiumDataRows;
    public readonly int $entityContextAccountId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Mode $mode
     * @param array|null $buyersPremiumDataRows
     * @param string|null $buyersPremiumString
     * @param int $entityContextAccountId
     * @return $this
     */
    public function construct(
        Mode $mode,
        ?array $buyersPremiumDataRows,
        ?string $buyersPremiumString,
        int $entityContextAccountId
    ): static {
        $this->buyersPremiumString = $buyersPremiumString;
        $this->buyersPremiumDataRows = $buyersPremiumDataRows;
        $this->mode = $mode;
        $this->entityContextAccountId = $entityContextAccountId;
        return $this;
    }

    /**
     * @param LotItemMakerInputDto $inputDto
     * @param LotItemMakerConfigDto $configDto
     * @return $this
     */
    public function fromLotItemMakerDto(
        LotItemMakerInputDto $inputDto,
        LotItemMakerConfigDto $configDto
    ): static {
        return $this->construct(
            $configDto->mode,
            $inputDto->buyersPremiumDataRows,
            $inputDto->buyersPremiumString,
            $configDto->serviceAccountId
        );
    }

    /**
     * @param AuctionMakerInputDto $inputDto
     * @param AuctionMakerConfigDto $configDto
     * @return $this
     */
    public function fromAuctionMakerDto(
        AuctionMakerInputDto $inputDto,
        AuctionMakerConfigDto $configDto
    ): static {
        return $this->construct(
            $configDto->mode,
            $inputDto->buyersPremiumDataRows,
            $inputDto->buyersPremiumString,
            $configDto->serviceAccountId
        );
    }

    public function fromUserMakerDto(
        UserMakerInputDto $inputDto,
        UserMakerConfigDto $configDto,
        string $auctionType,
        int $entityContextAccountId
    ): static {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isHybrid($auctionType)) {
            return $this->construct(
                $configDto->mode,
                $inputDto->buyersPremiumHybridDataRows,
                $inputDto->buyersPremiumHybridString,
                $entityContextAccountId
            );
        }

        if ($auctionStatusPureChecker->isLive($auctionType)) {
            return $this->construct(
                $configDto->mode,
                $inputDto->buyersPremiumLiveDataRows,
                $inputDto->buyersPremiumLiveString,
                $entityContextAccountId
            );
        }

        if ($auctionStatusPureChecker->isTimed($auctionType)) {
            return $this->construct(
                $configDto->mode,
                $inputDto->buyersPremiumTimedDataRows,
                $inputDto->buyersPremiumTimedString,
                $entityContextAccountId
            );
        }

        throw new InvalidArgumentException("Auction type incorrect '{$auctionType}'");
    }

}
