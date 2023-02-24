<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\LotItem\Internal\Dto;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Dto\ValidationStatus;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerConfigDto;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\Import\Csv\Lot\Internal\Dto\LotItem\LotItemEntityMakerDtoFactoryAwareTrait;
use Sam\Import\Csv\Lot\Internal\UpdatingEntity\DetectByLotItem\InventoryContextLotItemIdDetectorCreateTrait;
use Sam\Import\Csv\Read\CsvRow;

/**
 * Class DtoFactory
 * @package Sam\Import\Csv\Lot\LotItem
 * @internal
 */
class DtoFactory extends CustomizableClass
{
    use InventoryContextLotItemIdDetectorCreateTrait;
    use LotItemEntityMakerDtoFactoryAwareTrait;

    protected int $systemAccountId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $editorUserId
     * @param int $lotItemAccountId
     * @param int $systemAccountId
     * @param array $customFields
     * @param string $encoding
     * @param bool $clearEmptyFields
     * @param bool $shouldReplaceBreaksWithHtml
     * @param int|null $auctionId
     * @return static
     */
    public function construct(
        int $editorUserId,
        int $lotItemAccountId,
        int $systemAccountId,
        array $customFields,
        string $encoding,
        bool $clearEmptyFields,
        bool $shouldReplaceBreaksWithHtml,
        ?int $auctionId = null
    ): static {
        $this->getLotItemEntityMakerDtoFactory()->construct(
            $editorUserId,
            $lotItemAccountId,
            $systemAccountId,
            $customFields,
            $encoding,
            $clearEmptyFields,
            $shouldReplaceBreaksWithHtml,
            $auctionId
        );
        $this->systemAccountId = $systemAccountId;
        return $this;
    }

    /**
     * Construct DTO with prepared lot item data
     *
     * @param CsvRow $row
     * @param ValidationStatus $validationStatus
     * @return Row
     */
    public function create(CsvRow $row, ValidationStatus $validationStatus = ValidationStatus::NONE): Row
    {
        $lotItemIdDetectionResult = $this->createInventoryContextLotItemIdDetector()->detect($row, $this->systemAccountId);
        /**
         * @var LotItemMakerInputDto $lotItemInputDto
         * @var LotItemMakerConfigDto $lotItemConfigDto
         */
        [$lotItemInputDto, $lotItemConfigDto] = $this->getLotItemEntityMakerDtoFactory()->create(
            $row,
            $lotItemIdDetectionResult->lotItemId,
            $validationStatus
        );
        return Row::new()->construct($lotItemInputDto, $lotItemConfigDto, $lotItemIdDetectionResult);
    }
}
