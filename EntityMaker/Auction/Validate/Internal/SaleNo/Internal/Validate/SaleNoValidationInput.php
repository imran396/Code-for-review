<?php
/**
 * Validation input DTO
 *
 * SAM-8891: Auction entity-maker - Extract sale# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\SaleNo\Internal\Validate;

use InvalidServiceAccount;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Dto\AuctionMakerConfigDto;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputDto;
use Sam\EntityMaker\Base\Common\Mode;


class SaleNoValidationInput extends CustomizableClass
{
    /**
     * @var string
     */
    public string $saleNum;
    /**
     * @var string
     */
    public string $saleNumExt;
    /**
     * @var string
     */
    public string $saleFullNo;
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
    public bool $isSaleNumAssigned;
    /**
     * @var bool
     */
    public bool $isSaleFullNoAssigned;
    /**
     * @var Mode
     */
    public Mode $mode;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $saleNum
     * @param string $saleNumExt
     * @param string $saleFullNo
     * @param int|null $auctionId
     * @param int $accountId
     * @param bool $isSaleNumAssigned
     * @param bool $isSaleFullNoAssigned
     * @param Mode $mode
     * @return $this
     */
    public function construct(
        string $saleNum,
        string $saleNumExt,
        string $saleFullNo,
        ?int $auctionId,
        int $accountId,
        bool $isSaleNumAssigned,
        bool $isSaleFullNoAssigned,
        Mode $mode
    ): static {
        $this->saleNum = $saleNum;
        $this->saleNumExt = $saleNumExt;
        $this->saleFullNo = $saleFullNo;
        $this->auctionId = $auctionId;
        $this->accountId = $accountId;
        $this->isSaleNumAssigned = $isSaleNumAssigned;
        $this->isSaleFullNoAssigned = $isSaleFullNoAssigned;
        $this->mode = $mode;
        return $this;
    }


    /**
     * @param AuctionMakerInputDto $inputDto
     * @param AuctionMakerConfigDto $configDto
     * @return $this
     */
    public function fromMakerDto(
        AuctionMakerInputDto $inputDto,
        AuctionMakerConfigDto $configDto,
    ): static {
        if (!$configDto->serviceAccountId) {
            throw InvalidServiceAccount::withDefaultMessage();
        }

        return $this->construct(
            (string)$inputDto->saleNum,
            (string)$inputDto->saleNumExt,
            (string)$inputDto->saleFullNo,
            Cast::toInt($inputDto->id),
            $configDto->serviceAccountId,
            isset($inputDto->saleNum),
            isset($inputDto->saleFullNo),
            $configDto->mode
        );
    }

    public function logData(): array
    {
        return [
            'saleNum' => $this->saleNum,
            'saleNumExt' => $this->saleNumExt,
            'saleFullNo' => $this->saleFullNo,
            'auctionId' => $this->auctionId,
            'accountId' => $this->accountId,
            'isSaleNumAssigned' => $this->isSaleNumAssigned,
            'isSaleFullNoAssigned' => $this->isSaleFullNoAssigned,
            'mode' => $this->mode->name
        ];
    }
}
