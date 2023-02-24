<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\Internal\Validate\LotOverwriting;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotOverwritingValidationResult
 * @package Sam\Import\Csv\Lot\Internal\Validate\LotOverwriting
 */
class LotOverwritingValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_LOT_ITEM_OVERWRITING = 1;
    public const ERR_AUCTION_LOT_OVERWRITING = 2;

    protected const PAYLOAD_ITEM_NO = 'itemNo';
    protected const PAYLOAD_LOT_NO = 'lotNo';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(array $errorMessages = []): static
    {
        $defaultErrorMessages = [
            self::ERR_LOT_ITEM_OVERWRITING => 'Invalid lot item overwrite attempt',
            self::ERR_AUCTION_LOT_OVERWRITING => 'Invalid auction lot overwrite attempt'
        ];
        $this->getResultStatusCollector()->construct(array_replace($defaultErrorMessages, $errorMessages));
        return $this;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function getErrorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    public function addLotItemOverwritingError(string $itemNo): void
    {
        $this->getResultStatusCollector()->addError(
            self::ERR_LOT_ITEM_OVERWRITING,
            null,
            [
                self::PAYLOAD_ITEM_NO => $itemNo
            ]
        );
    }

    public function addAuctionLotOverwritingError(string $lotNo): void
    {
        $this->getResultStatusCollector()->addError(
            self::ERR_AUCTION_LOT_OVERWRITING,
            null,
            [
                self::PAYLOAD_LOT_NO => $lotNo
            ]
        );
    }

    public function extractItemNo(ResultStatus $resultStatus): string
    {
        return $resultStatus->getPayload()[self::PAYLOAD_ITEM_NO] ?? '';
    }

    public function extractLotNo(ResultStatus $resultStatus): string
    {
        return $resultStatus->getPayload()[self::PAYLOAD_LOT_NO] ?? '';
    }
}
