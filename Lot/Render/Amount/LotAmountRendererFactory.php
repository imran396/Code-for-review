<?php
/**
 * SAM-10339: Fetch US_NUMBER_FORMATTING and KEEP_DECIMAL_INVOICE from entity accounts
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Render\Amount;

use Sam\Core\Service\CustomizableClass;
use Sam\Transform\Number\NumberFormatter;

class LotAmountRendererFactory extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $serviceAccountId
     * @param bool $shouldAddDecimalZerosForInteger
     * @return LotAmountRendererInterface
     */
    public function create(int $serviceAccountId, bool $shouldAddDecimalZerosForInteger = false): LotAmountRendererInterface
    {
        $numberFormatter = NumberFormatter::new()->construct($serviceAccountId, $shouldAddDecimalZerosForInteger);
        // $numberFormatter = NextNumberFormatter::new()->construct($serviceAccountId, $shouldAddDecimalZerosForInteger);
        $lotAmountRenderer = LotAmountRenderer::new()->construct($numberFormatter);
        return $lotAmountRenderer;
    }

    /**
     * @param int $serviceAccountId
     * @return LotAmountRendererInterface
     */
    public function createForInvoice(int $serviceAccountId): LotAmountRendererInterface
    {
        return $this->create($serviceAccountId, true);
    }

    /**
     * @param int $serviceAccountId
     * @return LotAmountRendererInterface
     */
    public function createForSettlement(int $serviceAccountId): LotAmountRendererInterface
    {
        return $this->create($serviceAccountId, true);
    }
}
