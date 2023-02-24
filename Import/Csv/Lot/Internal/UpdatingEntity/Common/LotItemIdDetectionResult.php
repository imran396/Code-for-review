<?php
/**
 * Value-Object that defines the way how existing lot item is detected, i.e. by item#, or by lot#, or not found existing lot yet.
 *
 * SAM-9462: Lot CSV import - fix item# and lot# uniqueness check
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\Internal\UpdatingEntity\Common;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotItemIdDetectionResult
 */
class LotItemIdDetectionResult extends CustomizableClass
{
    public const NOT_FOUND = 0;
    public const BY_ITEM_NO = 1;
    public const BY_LOT_NO = 2;

    public readonly ?int $lotItemId;
    public readonly int $detectionWay;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(?int $lotItemId, int $detectionWay): static
    {
        $this->lotItemId = $lotItemId;
        $this->detectionWay = $detectionWay;
        return $this;
    }

    public function constructByItemNo(int $lotItemId): static
    {
        return $this->construct($lotItemId, self::BY_ITEM_NO);
    }

    public function constructByLotNo(int $lotItemId): static
    {
        return $this->construct($lotItemId, self::BY_LOT_NO);
    }

    public function constructNotFound(): static
    {
        return $this->construct(null, self::NOT_FOUND);
    }

    public function isFoundByItemNo(): bool
    {
        return $this->detectionWay === self::BY_ITEM_NO;
    }

    public function isFoundByLotNo(): bool
    {
        return $this->detectionWay === self::BY_LOT_NO;
    }
}
