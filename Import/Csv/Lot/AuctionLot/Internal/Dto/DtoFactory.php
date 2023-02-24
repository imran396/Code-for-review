<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\AuctionLot\Internal\Dto;

use Auction;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerConfigDto;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputDto;
use Sam\EntityMaker\Base\Dto\ValidationStatus;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\Import\Csv\Lot\Internal\Dto\AuctionLot\AuctionLotEntityMakerDtoFactoryAwareTrait;
use Sam\Import\Csv\Lot\Internal\Dto\LotItem\LotItemEntityMakerDtoFactoryAwareTrait;
use Sam\Import\Csv\Lot\Internal\UpdatingEntity\DetectByAuctionLot\AuctionContextLotItemIdDetectorCreateTrait;
use Sam\Import\Csv\Read\CsvRow;

/**
 * Class AuctionLotImportCsvDtoFactory
 * @package Sam\Import\Csv\Lot\AuctionLot
 * @internal
 */
class DtoFactory extends CustomizableClass
{
    use AuctionContextLotItemIdDetectorCreateTrait;
    use AuctionLotEntityMakerDtoFactoryAwareTrait;
    use LotItemEntityMakerDtoFactoryAwareTrait;

    /**
     * @var int
     */
    private int $systemAccountId;
    /**
     * @var int
     */
    private int $auctionId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Auction $auction
     * @param int $editorUserId
     * @param int $lotAccountId
     * @param int $systemAccountId
     * @param array $customFields
     * @param string $encoding
     * @param bool $clearEmptyFields
     * @param bool $shouldReplaceBreaksWithHtml
     * @return static
     */
    public function construct(
        Auction $auction,
        int $editorUserId,
        int $lotAccountId,
        int $systemAccountId,
        array $customFields,
        string $encoding,
        bool $clearEmptyFields,
        bool $shouldReplaceBreaksWithHtml
    ): static {
        $this->getLotItemEntityMakerDtoFactory()->construct(
            $editorUserId,
            $lotAccountId,
            $systemAccountId,
            $customFields,
            $encoding,
            $clearEmptyFields,
            $shouldReplaceBreaksWithHtml,
            $auction->Id
        );
        $this->getAuctionLotEntityMakerDtoFactory()->construct(
            $auction,
            $editorUserId,
            $lotAccountId,
            $systemAccountId,
            $customFields,
            $encoding,
            $clearEmptyFields
        );

        $this->systemAccountId = $systemAccountId;
        $this->auctionId = $auction->Id;
        return $this;
    }

    /**
     * Construct DTO with prepared auction lot and lot item data
     *
     * @param CsvRow $row
     * @param ValidationStatus $validationStatus
     * @return Row
     */
    public function create(CsvRow $row, ValidationStatus $validationStatus = ValidationStatus::NONE): Row
    {
        $lotItemIdDetectionResult = $this->createAuctionContextLotItemIdDetector()->detect(
            $row,
            $this->auctionId,
            $this->systemAccountId
        );

        /**
         * @var LotItemMakerInputDto $lotItemInputDto
         * @var LotItemMakerConfigDto $lotItemConfigDto
         */
        [$lotItemInputDto, $lotItemConfigDto] = $this->getLotItemEntityMakerDtoFactory()->create(
            $row,
            $lotItemIdDetectionResult->lotItemId,
            $validationStatus
        );
        /**
         * @var AuctionLotMakerInputDto $auctionLotInputDto
         * @var AuctionLotMakerConfigDto $auctionLotConfigDto
         */
        [$auctionLotInputDto, $auctionLotConfigDto] = $this->getAuctionLotEntityMakerDtoFactory()->create(
            $row,
            $lotItemIdDetectionResult->lotItemId,
            $validationStatus
        );

        return Row::new()->construct($lotItemInputDto, $lotItemConfigDto, $auctionLotInputDto, $auctionLotConfigDto, $lotItemIdDetectionResult);
    }
}
