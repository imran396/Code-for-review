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

namespace Sam\Lot\Render\Amount\Dummy;

use Sam\Lot\Render\Amount\LotAmountRendererFactory;
use Sam\Lot\Render\Amount\LotAmountRendererInterface;

class DummyLotAmountRendererFactory extends LotAmountRendererFactory
{
    /**
     * @param int $serviceAccountId
     * @param bool $shouldAddDecimalZerosForInteger
     * @return LotAmountRendererInterface
     */
    public function create(int $serviceAccountId, bool $shouldAddDecimalZerosForInteger = false): LotAmountRendererInterface
    {
        return new DummyLotAmountRenderer();
    }

    /**
     * @param int $serviceAccountId
     * @return LotAmountRendererInterface
     */
    public function createForInvoice(int $serviceAccountId): LotAmountRendererInterface
    {
        return new DummyLotAmountRenderer();
    }

    /**
     * @param int $serviceAccountId
     * @return LotAmountRendererInterface
     */
    public function createForSettlement(int $serviceAccountId): LotAmountRendererInterface
    {
        return new DummyLotAmountRenderer();
    }
}
