<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Oct 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementFailed;

use Sam\Core\Service\CustomizableClass;


class SettlementFailedCallbackResponseHandlingInput extends CustomizableClass
{
    public readonly ?int $settlementId;
    public readonly string $threeDStatusResponse;
    public readonly bool $isReadOnlyDb;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $settlementId
     * @param string $threeDStatusResponse
     * @param bool $isReadOnlyDb
     * @return $this
     */
    public function construct(
        ?int $settlementId,
        string $threeDStatusResponse,
        bool $isReadOnlyDb
    ): static {
        $this->settlementId = $settlementId;
        $this->threeDStatusResponse = $threeDStatusResponse;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }
}
