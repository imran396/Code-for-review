<?php
/**
 * Facade service that produces output values that should be rendered in check edit form.
 * These are values that are intended for check field population and editing before save.
 *
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

namespace Sam\Settlement\Check\Content\Build;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Content\Build\Internal\Amount\AmountRendererCreateTrait;
use Sam\Settlement\Check\Content\Build\Internal\AmountSpelling\AmountSpellingRendererCreateTrait;
use Sam\Settlement\Check\Content\Build\Internal\Load\DataProviderAwareTrait;
use Sam\Settlement\Check\Content\Build\Internal\Template\TemplatedFieldRendererCreateTrait;

/**
 * YV, SAM-9795. I think we need to rename SettlementCheckContentBuilder --> SettlementCheckContentPopulator and all its build*
 * methods as populate* because that's exactly what he does
 *
 *
 * Class SettlementCheckFieldBuilder
 * @package Sam\Settlement\Check
 */
class SettlementCheckContentRenderer extends CustomizableClass
{
    use AmountRendererCreateTrait;
    use AmountSpellingRendererCreateTrait;
    use DataProviderAwareTrait;
    use TemplatedFieldRendererCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build the all fields content that can be populated at the new check creation stage.
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return SettlementCheckAllContent
     */
    public function renderAllForNewCheckBySettlementId(int $settlementId, bool $isReadOnlyDb = false): SettlementCheckAllContent
    {
        $settlementData = $this->getDataProvider()->loadSettlementData($settlementId, $isReadOnlyDb);
        return $this->renderAllForNewCheck(
            $settlementId,
            $settlementData->consignorUserId,
            $settlementData->settlementAccountId,
            $isReadOnlyDb
        );
    }

    public function renderAllForNewCheck(
        int $settlementId,
        int $consignorUserId,
        int $settlementAccountId,
        bool $isReadOnlyDb = false
    ): SettlementCheckAllContent {
        [
            $addressContent,
            $payeeContent,
            $memoContent
        ] = $this->createTemplatedFieldRenderer()->renderAll(
            $settlementId,
            $consignorUserId,
            $settlementAccountId,
            $isReadOnlyDb
        );

        $amountRenderer = $this->createAmountRenderer();
        $amount = $amountRenderer->calcAmount($settlementId, null, $isReadOnlyDb);
        $amountSpellingRenderer = $this->createAmountSpellingRenderer();

        $dto = SettlementCheckAllContent::new()->construct(
            $addressContent,
            $payeeContent,
            $memoContent,
            $amountRenderer->formatNtoAmount($amount, $settlementAccountId),
            $amountSpellingRenderer->renderAmountSpelling($amount, $settlementAccountId)
        );
        return $dto;
    }

    /**
     * Build the "Address" field content on the base of the "Check address template" defined in system parameters.
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function renderAddress(int $settlementId, bool $isReadOnlyDb = false): string
    {
        $settlementData = $this->getDataProvider()->loadSettlementData($settlementId, $isReadOnlyDb);
        return $this->createTemplatedFieldRenderer()->renderAddress(
            $settlementData->consignorUserId,
            $settlementData->settlementAccountId,
            $isReadOnlyDb
        );
    }

    /**
     * Build the "Payee" field content on the base of the "Check payee template" defined in system parameters.
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function renderPayee(int $settlementId, bool $isReadOnlyDb = false): string
    {
        $settlementData = $this->getDataProvider()->loadSettlementData($settlementId, $isReadOnlyDb);
        return $this->createTemplatedFieldRenderer()->renderPayee(
            $settlementData->consignorUserId,
            $settlementData->settlementAccountId,
            $isReadOnlyDb
        );
    }

    /**
     * Build the "Memo" field content on the base of the "Check memo template" defined in system parameters.
     * This is quick solution, we can create general query builder for the whole settlement domain and related entities.
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function renderMemo(int $settlementId, bool $isReadOnlyDb = false): string
    {
        $settlementData = $this->getDataProvider()->loadSettlementData($settlementId, $isReadOnlyDb);
        return $this->createTemplatedFieldRenderer()->renderMemo(
            $settlementId,
            $settlementData->settlementAccountId,
            $isReadOnlyDb
        );
    }

    /**
     * Result with settlement "Balance Due" formatted according "US Number Formatting" system parameter and with thousand separators.
     * @param int $settlementId
     * @param int|null $settlementCheckId
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function renderAmount(int $settlementId, ?int $settlementCheckId, bool $isReadOnlyDb = false): string
    {
        $settlementData = $this->getDataProvider()->loadSettlementData($settlementId, $isReadOnlyDb);
        return $this->createAmountRenderer()->renderAmount($settlementId, $settlementData->settlementAccountId, $settlementCheckId, $isReadOnlyDb);
    }

    /**
     * Transform decimal value of the check amount field to spelling.
     * @param float|null $amount
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function renderAmountSpelling(?float $amount, int $settlementId, bool $isReadOnlyDb = false): string
    {
        $settlementData = $this->getDataProvider()->loadSettlementData($settlementId, $isReadOnlyDb);
        return $this->createAmountSpellingRenderer()->renderAmountSpelling($amount, $settlementData->settlementAccountId);
    }
}
