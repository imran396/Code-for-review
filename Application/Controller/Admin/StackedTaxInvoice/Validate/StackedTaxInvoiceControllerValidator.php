<?php
/**
 * SAM-5151: Invoice generation and reverse proxy timeout improvements
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/26/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\StackedTaxInvoice\Validate;

use Sam\Account\Validate\MultipleTenantAccountSimpleChecker;
use Sam\Application\Controller\Admin\StackedTaxInvoice\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Application\Controller\Admin\StackedTaxInvoice\Validate\StackedTaxInvoiceControllerValidationResult as Result;
use Sam\Installation\Config\Repository\ConfigRepository;

class StackedTaxInvoiceControllerValidator extends CustomizableClass
{
    use DataProviderCreateTrait;
    use OptionalsTrait;

    // --- Input values ---
    public const OP_IS_MULTIPLE_TENANT = OptionalKeyConstants::KEY_IS_MULTIPLE_TENANT; // bool
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_MAIN_ACCOUNT_ID = OptionalKeyConstants::KEY_MAIN_ACCOUNT_ID; // int

    /** @var string[] */
    private array $invoiceAbsentActions = [
        Constants\AdminRoute::AMSTI_LIST,
        Constants\AdminRoute::AMSTI_GENERATE,
    ];

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
     * Validate/Check if Invoice ID exists, and not archived or deleted
     * @param int|null $invoiceId
     * @param string $auctionName
     * @param int $systemAccountId
     * @param int|null $editorUserId
     * @return StackedTaxInvoiceControllerValidationResult
     */
    public function validate(?int $invoiceId, string $auctionName, int $systemAccountId, ?int $editorUserId): StackedTaxInvoiceControllerValidationResult
    {
        $validationResult = Result::new()->construct();

        // check editor user privilege for invoice operations
        if (!$this->createDataProvider()->hasPrivilegeForManageInvoices($editorUserId)) {
            return $validationResult->addError(Result::ERR_INVOICE_ACCESS_DENIED);
        }

        // we don't require concrete invoice check at some pages (invoice list)
        if (in_array($auctionName, $this->getInvoiceAbsentActions(), true)) {
            return $validationResult->addSuccess();
        }

        return $this->validateConcreteInvoice($systemAccountId, $invoiceId, $validationResult);
    }

    /**
     * Perform validations related to concrete invoice record
     * @param int $systemAccountId
     * @param int|null $invoiceId
     * @param StackedTaxInvoiceControllerValidationResult $validationResult
     * @return StackedTaxInvoiceControllerValidationResult
     */
    protected function validateConcreteInvoice(int $systemAccountId, ?int $invoiceId, Result $validationResult): StackedTaxInvoiceControllerValidationResult
    {
        // check invoice existence
        $dataProvider = $this->createDataProvider();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $invoice = $dataProvider->loadInvoice($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            return $validationResult->addError(Result::ERR_INCORRECT_INVOICE_ID);
        }

        // check invoice availability
        if (!$invoice->isAmongAvailableInvoiceStatuses()) {
            return $validationResult->addError(Result::ERR_UNAVAILABLE_INVOICE);
        }

        // check that invoice's account availability
        $isFound = $dataProvider->isInvoiceAccountExist($invoice->AccountId, $isReadOnlyDb);
        if (!$isFound) {
            return $validationResult->addError(Result::ERR_INVOICE_ACCOUNT_NOT_FOUND);
        }

        // check access on portal account
        if (
            $invoice->AccountId !== $systemAccountId
            && !$this->isMainSystemAccount($systemAccountId)
        ) {
            return $validationResult->addError(Result::ERR_INVOICE_AND_PORTAL_ACCOUNTS_NOT_MATCH);
        }

        return $validationResult->addSuccess();
    }

    /**
     * @param int $systemAccountId
     * @return bool
     */
    protected function isMainSystemAccount(int $systemAccountId): bool
    {
        return MultipleTenantAccountSimpleChecker::new()->isMainAccount(
            $systemAccountId,
            (bool)$this->fetchOptional(self::OP_IS_MULTIPLE_TENANT),
            (int)$this->fetchOptional(self::OP_MAIN_ACCOUNT_ID)
        );
    }

    /**
     * @return string[]
     */
    public function getInvoiceAbsentActions(): array
    {
        return $this->invoiceAbsentActions;
    }

    /**
     * @param string[] $invoiceAbsentActions
     * @return static
     */
    public function setInvoiceAbsentActions(array $invoiceAbsentActions): static
    {
        $this->invoiceAbsentActions = ArrayCast::makeStringArray($invoiceAbsentActions);
        return $this;
    }

    /**
     * @param string|string[] $invoiceAbsentActions
     * @return string[]
     */
    public function withInvoiceAbsentActions(string|array $invoiceAbsentActions): array
    {
        $invoiceAbsentActions = ArrayCast::makeStringArray($invoiceAbsentActions);
        $invoiceAbsentActions = array_merge($invoiceAbsentActions ?: [], $this->getInvoiceAbsentActions());
        $invoiceAbsentActions = array_unique($invoiceAbsentActions);
        $this->setInvoiceAbsentActions($invoiceAbsentActions);
        return $invoiceAbsentActions;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_MULTIPLE_TENANT] = $optionals[self::OP_IS_MULTIPLE_TENANT]
            ?? static function (): bool {
                return (bool)ConfigRepository::getInstance()->get('core->portal->enabled');
            };
        $optionals[self::OP_MAIN_ACCOUNT_ID] = $optionals[self::OP_MAIN_ACCOUNT_ID]
            ?? static function (): int {
                return (int)ConfigRepository::getInstance()->get('core->portal->mainAccountId');
            };
        $optionals[self::OP_IS_READ_ONLY_DB] = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $this->setOptionals($optionals);
    }
}
