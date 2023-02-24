<?php
/**
 * SAM-6928: Sales staff user assignment and filtering control adjustments at the "User Edit" and the "Sales Report" pages
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SaleStaff\Autocomplete;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Report\SaleStaff\Autocomplete\Load\SalesStaffUserDataLoader;

/**
 * Class FilteringControlDataLoader
 * @package Sam\Report\Sales
 */
class SalesStaffAutocompleteListBuilder extends CustomizableClass
{
    use OptionalsTrait;

    // --- Incoming values ---

    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_LABEL_TPL = 'labelTpl'; // string
    public const OP_TRIM_CHARS = 'trimChars'; // string

    // --- Internal values ---

    protected const LABEL_TPL_DEF = '%s - %s %s'; //username - firstName lastName
    protected const TRIM_CHARS_DEF = ' -';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    public function constructForReadonlyDb(array $optionals = []): static
    {
        $optionals[self::OP_IS_READ_ONLY_DB] = true;
        return $this->construct($optionals);
    }

    public function buildOptions(?int $targetAccountId, string $searchTerm): array
    {
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $salesStaffRows = SalesStaffUserDataLoader::new()->loadAll($targetAccountId, $searchTerm, $isReadOnlyDb);

        $options = [];
        foreach ($salesStaffRows ?: [] as $row) {
            $resultData = [];
            $resultData['label'] = $this->makeLabel($row);
            $resultData['value'] = $row['id'];
            $options[] = $resultData;
        }
        return $options;
    }

    public function buildLabelBySalesStaffUserId(?int $userId): string
    {
        if (!$userId) {
            return '';
        }
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $row = SalesStaffUserDataLoader::new()->loadSingleByUserId($userId, $isReadOnlyDb);

        return $row ? $this->makeLabel($row) : '';
    }

    private function makeLabel(array $row): string
    {
        $labelTpl = (string)$this->fetchOptional(self::OP_LABEL_TPL);
        $trimChars = (string)$this->fetchOptional(self::OP_TRIM_CHARS);
        $label = sprintf($labelTpl, $row['username'], $row['first_name'], $row['last_name']);
        $label = trim($label, $trimChars);
        return $label;
    }

    /**
     * @param array $optionals
     */
    private function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_READ_ONLY_DB] = $optionals[self::OP_IS_READ_ONLY_DB] ?? true;
        $optionals[self::OP_LABEL_TPL] = $optionals[self::OP_LABEL_TPL] ?? self::LABEL_TPL_DEF;
        $optionals[self::OP_TRIM_CHARS] = $optionals[self::OP_TRIM_CHARS] ?? self::TRIM_CHARS_DEF;
        $this->setOptionals($optionals);
    }
}
