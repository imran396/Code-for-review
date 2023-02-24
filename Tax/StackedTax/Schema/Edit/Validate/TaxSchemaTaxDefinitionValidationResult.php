<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Edit\Validate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxSchemaTaxDefinitionValidationResult
 * @package Sam\Tax\StackedTax\Schema\Edit\Validate
 */
class TaxSchemaTaxDefinitionValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const TAX_DEFINITION_ID_PAYLOAD_KEY = 'taxDefinitionId';
    public const TAX_DEFINITION_NAME_PAYLOAD_KEY = 'taxDefinitionName';

    public const ERR_TAX_DEFINITION_DOES_NOT_EXIST = 1;
    public const ERR_NOT_APPLICABLE_TAX_DEFINITION_GEO_TYPE = 2;

    public const ERROR_MESSAGES = [
        self::ERR_TAX_DEFINITION_DOES_NOT_EXIST => 'Tax definition does not exist',
        self::ERR_NOT_APPLICABLE_TAX_DEFINITION_GEO_TYPE => 'Tax definition geo type is not applicable for the tax schema',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    public function addError(int $errorCode, int $taxDefinitionId, string $taxDefinitionName): static
    {
        $this->getResultStatusCollector()->addError($errorCode, null, [
            self::TAX_DEFINITION_ID_PAYLOAD_KEY => $taxDefinitionId,
            self::TAX_DEFINITION_NAME_PAYLOAD_KEY => $taxDefinitionName,
        ]);
        return $this;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function getErrorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    public function getErrorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @return ResultStatus[]
     */
    public function getErrorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }

    /**
     * @return ResultStatus[]
     */
    public function getErrorStatusesForTaxDefinition(int $taxDefinitionId): array
    {
        return array_filter(
            $this->getErrorStatuses(),
            fn(ResultStatus $error) => $error->getPayload()[self::TAX_DEFINITION_ID_PAYLOAD_KEY] === $taxDefinitionId
        );
    }
}
