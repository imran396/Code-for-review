<?php
/**
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build\Internal\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementData
 * @package Sam\Settlement\Check
 */
class SettlementData extends CustomizableClass
{
    public readonly int $settlementId;
    public readonly ?int $settlementAccountId;
    public readonly ?int $consignorUserId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $settlementId
     * @param int|null $settlementAccountId
     * @param int|null $consignorUserId
     * @return $this
     */
    public function construct(
        int $settlementId,
        ?int $settlementAccountId,
        ?int $consignorUserId
    ): static {
        $this->settlementId = $settlementId;
        $this->settlementAccountId = $settlementAccountId;
        $this->consignorUserId = $consignorUserId;
        return $this;
    }

    public function toArray(): array
    {
        return [
            $this->settlementId,
            $this->settlementAccountId,
            $this->consignorUserId
        ];
    }
}
