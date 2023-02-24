<?php
/**
 * It produces output values that should be rendered in check edit form.
 * These are values that are intended for check field population and editing before save.
 *
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build\Internal\Template;

use Sam\Core\Service\CustomizableClass;
use Sam\Details\User\SettlementCheck\Address\Build\SettlementCheckAddressBuilderCreateTrait;
use Sam\Details\User\SettlementCheck\Payee\Build\SettlementCheckPayeeBuilderCreateTrait;
use Sam\Settlement\Check\Content\Build\Internal\Template\Internal\Load\DataProviderAwareTrait;
use Sam\Settlement\Check\Content\Build\Internal\Template\Internal\Parse\TemplateParser;
use Sam\Settlement\Check\Content\Common\Constants\PlaceholderConstants;
use SettlementCheck;

/**
 * Class TemplateBuilder
 * @package Sam\Settlement\Check
 */
class TemplatedFieldRenderer extends CustomizableClass
{
    use DataProviderAwareTrait;
    use SettlementCheckAddressBuilderCreateTrait;
    use SettlementCheckPayeeBuilderCreateTrait;

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
     * @param int $consignorUserId
     * @param int $settlementAccountId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function renderAll(
        int $settlementId,
        int $consignorUserId,
        int $settlementAccountId,
        bool $isReadOnlyDb = false
    ): array {
        $addressContent = $this->createSettlementCheckAddressBuilder()->construct($consignorUserId, $settlementAccountId)->build();
        $payeeContent = $this->renderPayee($consignorUserId, $settlementAccountId, $isReadOnlyDb);
        $memoContent = $this->renderMemo($settlementId, $settlementAccountId, $isReadOnlyDb);
        return [
            $addressContent,
            $payeeContent,
            $memoContent,
        ];
    }

    /**
     * Build the "Address" field content on the base of the "Check address template" defined in system parameters.
     * @param int $consignorUserId
     * @param int $settlementAccountId
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function renderAddress(
        int $consignorUserId,
        int $settlementAccountId,
        bool $isReadOnlyDb = false
    ): string {
        $content = $this->createSettlementCheckAddressBuilder()
            ->construct($consignorUserId, $settlementAccountId, $isReadOnlyDb)
            ->build();
        return $content;
    }

    /**
     * Build the "Payee" field content on the base of the "Check payee template" defined in system parameters.
     * @param int $consignorUserId
     * @param int $settlementAccountId
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function renderPayee(
        int $consignorUserId,
        int $settlementAccountId,
        bool $isReadOnlyDb = false
    ): string {
        $content = $this->createSettlementCheckPayeeBuilder()
            ->construct($consignorUserId, $settlementAccountId, $isReadOnlyDb)
            ->build();
        $content = $this->normalizePayee($content);
        return $content;
    }

    /**
     * Build the "Memo" field content on the base of the "Check memo template" defined in system parameters.
     * This is quick solution, we can create general query builder for the whole settlement domain and related entities.
     * @param int $settlementId
     * @param int $settlementAccountId
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function renderMemo(
        int $settlementId,
        int $settlementAccountId,
        bool $isReadOnlyDb = false
    ): string {
        $dataProvider = $this->getDataProvider();
        $templateParser = TemplateParser::new();

        $template = $dataProvider->loadMemoTemplate($settlementAccountId);
        $actualPlaceholders = $templateParser->extractPlaceholders($template, PlaceholderConstants::MEMO_PLACEHOLDERS);
        if (!$actualPlaceholders) {
            return $template;
        }

        $placeholdersToValues = [];
        $row = $dataProvider->loadMemoData($settlementId, $isReadOnlyDb);
        foreach ($row as $field => $value) {
            if ($field === 'account_name') {
                $placeholdersToValues[PlaceholderConstants::PL_ACCOUNT_NAME] = $value;
            } elseif ($field === 'settlement_no') {
                $placeholdersToValues[PlaceholderConstants::PL_SETTLEMENT_NO] = $value;
            }
        }
        $content = $templateParser->replacePlaceholders($template, $placeholdersToValues);
        $content = $this->normalizeMemo($content);
        return $content;
    }

    protected function normalizePayee(string $payeeContent): string
    {
        return mb_substr($payeeContent, 0, SettlementCheck::PAYEE_MAX_LENGTH);
    }

    protected function normalizeMemo(string $memoContent): string
    {
        return mb_substr($memoContent, 0, SettlementCheck::MEMO_MAX_LENGTH);
    }
}
