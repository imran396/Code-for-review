<?php
/**
 * SAM-9721: Refactor and implement unit test for single invoice producer
 * SAM-4377: Invoice producer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           04.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Generate\Item\Single;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Generate\Item\Single\Internal\ArtistResaleRight\ArtistResaleRightChargeSaverCreateTrait;
use Sam\Invoice\Legacy\Generate\Item\Single\Internal\InvoiceItem\InvoiceItemSaverCreateTrait;
use Sam\Invoice\Legacy\Generate\Item\Single\LegacySingleInvoiceItemProductionResult as Result;
use Sam\Invoice\Legacy\Generate\Item\Single\LegacySingleInvoiceItemProductionInput as Input;

/**
 * Class SingleInvoiceItemProducer
 * @package Sam\Invoice\Legacy\Generate\Item
 */
class LegacySingleInvoiceItemProducer extends CustomizableClass
{
    use ArtistResaleRightChargeSaverCreateTrait;
    use InvoiceItemSaverCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Creates invoice item based on PreInvoiceItemDto data
     * @param Input $input
     * @return Result
     */
    public function produce(Input $input): Result
    {
        $invoiceItem = $this->createInvoiceItemSaver()->produceInvoiceItem($input);
        $invoiceAdditional = $this->createInvoiceAdditionalSaver()->produceArtistResaleRightCharge($input);
        return Result::new()->construct($invoiceItem, $invoiceAdditional);
    }
}
