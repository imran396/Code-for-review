<?php
/**
 * SAM-4377: Invoice producer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           15.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Generate\Note;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Generate\Note\Consignor\InvoiceNoteConsignorInfoBuilderAwareTrait;
use Sam\Invoice\Legacy\Generate\Note\General\InvoiceNoteGeneralInfoBuilderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Core\Constants;

/**
 * Class InvoiceNoteBuilder
 * @package Sam\Invoice\Legacy\Generate\Note
 */
class LegacyInvoiceNoteBuilder extends CustomizableClass
{
    use InvoiceNoteConsignorInfoBuilderAwareTrait;
    use InvoiceNoteGeneralInfoBuilderAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $invoiceId
     * @return string
     */
    public function build(int $invoiceId): string
    {
        $consignorPaymentInfo = $this->getSettingsManager()->getForMain(Constants\Setting::ENABLE_CONSIGNOR_PAYMENT_INFO)
            ? $this->getInvoiceNoteConsignorInfoBuilder()->build($invoiceId) : '';

        return trim(
            $this->getInvoiceNoteGeneralInfoBuilder()->build($invoiceId) . PHP_EOL .
            PHP_EOL . $consignorPaymentInfo
        );
    }

}
