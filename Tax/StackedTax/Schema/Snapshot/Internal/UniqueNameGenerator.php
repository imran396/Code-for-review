<?php
/**
 * SAM-11239: Stacked Tax. Store invoice tax amounts per definition
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Snapshot\Internal;

use Sam\Core\Service\CustomizableClass;
use Sam\Tax\StackedTax\Definition\Load\TaxDefinitionLoaderCreateTrait;
use Sam\Tax\StackedTax\Schema\Load\TaxSchemaLoaderCreateTrait;
use Sam\Translation\AdminTranslatorAwareTrait;
use TaxDefinition;
use TaxSchema;

/**
 * Class UniqueNameGenerator
 * @package Sam\Tax\StackedTax\Schema\Snapshot\Internal
 */
class UniqueNameGenerator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use TaxSchemaLoaderCreateTrait;
    use TaxDefinitionLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function generateUniqueTaxSchemaName(string $name, int $accountId): string
    {
        $name = mb_substr($name, 0, TaxSchema::NAME_MAX_LENGTH);

        $taxSchema = $this->createTaxSchemaLoader()->loadLastSnapshotByName($name, $accountId);
        if ($taxSchema) {
            $name = $this->addTaxNameUniqueIndex($taxSchema->Name, TaxSchema::NAME_MAX_LENGTH);
        }

        return $name;
    }

    public function generateUniqueTaxDefinitionName(string $name, int $accountId): string
    {
        $name = mb_substr($name, 0, TaxDefinition::NAME_MAX_LENGTH);

        $taxDefinition = $this->createTaxDefinitionLoader()->loadLastSnapshotByName($name, $accountId);
        if ($taxDefinition) {
            $name = $this->addTaxNameUniqueIndex($taxDefinition->Name, TaxSchema::NAME_MAX_LENGTH);
        }

        return $name;
    }

    /**
     * Check name existence and adjust to be unique, eg. with "(n)" suffix: (1), (2), etc
     */
    protected function addTaxNameUniqueIndex(string $name, int $maxLength): string
    {
        if (preg_match('/^.*\((\d+)\)$/u', $name, $match)) {
            return str_replace('(' . $match[1] . ')', '(' . ((int)$match[1] + 1) . ')', $name);
        }

        return mb_substr($name, 0, $maxLength - 4) . ' (2)';
    }
}
