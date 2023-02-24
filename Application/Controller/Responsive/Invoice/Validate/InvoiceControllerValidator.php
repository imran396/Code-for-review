<?php
/**
 * SAM-5412: Validations at controller layer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/24/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Invoice\Validate;

use Sam\Application\Controller\Responsive\Invoice\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Application\Controller\Responsive\Invoice\Validate\InvoiceControllerValidationResult as Result;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;

/**
 * Class InvoiceControllerValidator
 * @package Sam\Application\Controller\Responsive\Invoice
 */
class InvoiceControllerValidator extends CustomizableClass
{
    use DataProviderCreateTrait;
    use EditorUserAwareTrait;
    use OptionalsTrait;

    // --- Input values ---
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool


    // --- Constructors ---

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /** To initialize instance properties
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    // --- Main method ---

    /**
     * Validate/Check if Auction ID exists, and not archived or deleted
     * @param int|null $invoiceId
     * @return InvoiceControllerValidationResult
     */
    public function validate(?int $invoiceId): InvoiceControllerValidationResult
    {
        $result = Result::new()->construct();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);

        $invoice = $this->createDataProvider()->loadInvoice($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            return $result->addError(Result::ERR_INVOICE_NOT_FOUND);
        }

        if (!$this->equalEditorUserId($invoice->BidderId)) {
            return $result->addError(Result::ERR_ACCESS_DENIED);
        }

        $isAllowed = $this->createDataProvider()->isAllowedForInvoice($invoice);
        if (!$isAllowed) {
            return $result->addError(Result::ERR_DOMAIN_AUCTION_VISIBILITY);
        }

        return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_READ_ONLY_DB] = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $this->setOptionals($optionals);
    }
}
