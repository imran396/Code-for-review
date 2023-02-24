<?php
/**
 * SAM-8892: Auction Lot entity maker - extract lot# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo\Internal\Validate;

use InvalidServiceAccount;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerConfigDto;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputDto;

/**
 * Class LotNoValidationDto
 * @package Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo\Internal\Validate
 */
class LotNoValidationInput extends CustomizableClass
{
    /**
     * @var int|null
     */
    public ?int $id;
    /**
     * @var string
     */
    public string $lotNum;
    /**
     * @var string
     */
    public string $lotNumExt;
    /**
     * @var string
     */
    public string $lotNumPrefix;
    /**
     * @var string
     */
    public string $lotFullNum;
    /**
     * @var int|null
     */
    public ?int $auctionId;
    /**
     * @var int
     */
    public int $accountId;
    /**
     * @var bool
     */
    public bool $isLotNumAssigned;
    /**
     * @var bool
     */
    public bool $isLotFullNumAssigned;
    /**
     * @var int
     */
    public int $editorUserId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $id
     * @param int|null $auctionId
     * @param int $accountId
     * @param string $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param string $lotFullNum
     * @param bool $isLotNumAssigned
     * @param bool $isLotFullNumAssigned
     * @param int $editorUserId
     * @return $this
     */
    public function construct(
        ?int $id,
        ?int $auctionId,
        int $accountId,
        string $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        string $lotFullNum,
        bool $isLotNumAssigned,
        bool $isLotFullNumAssigned,
        int $editorUserId
    ): static {
        $this->id = $id;
        $this->auctionId = $auctionId;
        $this->accountId = $accountId;
        $this->lotNum = $lotNum;
        $this->lotNumExt = $lotNumExt;
        $this->lotNumPrefix = $lotNumPrefix;
        $this->lotFullNum = $lotFullNum;
        $this->isLotNumAssigned = $isLotNumAssigned;
        $this->isLotFullNumAssigned = $isLotFullNumAssigned;
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * @param AuctionLotMakerInputDto $inputDto
     * @param AuctionLotMakerConfigDto $configDto
     * @return $this
     */
    public function fromMakerDto(
        AuctionLotMakerInputDto $inputDto,
        AuctionLotMakerConfigDto $configDto,
    ): static {
        if (!$configDto->serviceAccountId) {
            throw InvalidServiceAccount::withDefaultMessage();
        }

        return $this->construct(
            Cast::toInt($inputDto->id),
            Cast::toInt($inputDto->auctionId),
            $configDto->serviceAccountId,
            (string)$inputDto->lotNum,
            (string)$inputDto->lotNumExt,
            (string)$inputDto->lotNumPrefix,
            (string)$inputDto->lotFullNum,
            isset($inputDto->lotNum),
            isset($inputDto->lotFullNum),
            $configDto->editorUserId
        );
    }

    /**
     * @return array
     */
    public function logData(): array
    {
        return [
            'lotNum' => $this->lotNum,
            'lotNumExt' => $this->lotNumExt,
            'lotFullNum' => $this->lotFullNum,
            'auctionId' => $this->auctionId,
            'accountId' => $this->accountId,
            'isLotNumAssigned' => $this->isLotNumAssigned,
            'isLotFullNumAssigned' => $this->isLotFullNumAssigned
        ];
    }
}
