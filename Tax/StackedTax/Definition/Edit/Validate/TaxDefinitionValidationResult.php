<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Definition\Edit\Validate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxDefinitionValidationResult
 * @package Sam\Tax\StackedTax\Definition\Edit\Validate
 */
class TaxDefinitionValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_NAME_REQUIRED = 1;
    public const ERR_NAME_EXISTS = 2;
    public const ERR_TAX_TYPE_REQUIRED = 3;
    public const ERR_TAX_TYPE_INVALID = 4;
    public const ERR_GEO_TYPE_INVALID = 5;
    public const ERR_COUNTRY_REQUIRED = 6;
    public const ERR_COUNTRY_INVALID = 7;
    public const ERR_COUNTRY_NOT_ALLOWED = 8;
    public const ERR_STATE_REQUIRED = 9;
    public const ERR_STATE_NOT_ALLOWED = 10;
    public const ERR_COUNTY_REQUIRED = 11;
    public const ERR_COUNTY_NOT_ALLOWED = 12;
    public const ERR_CITY_REQUIRED = 13;
    public const ERR_CITY_NOT_ALLOWED = 14;
    public const ERR_TAX_DEFINITION_NOT_FOUND = 15;

    protected const ERROR_MESSAGES = [
        self::ERR_NAME_REQUIRED => 'Name required',
        self::ERR_NAME_EXISTS => 'Name already exists',
        self::ERR_TAX_TYPE_REQUIRED => 'Tax type required',
        self::ERR_TAX_TYPE_INVALID => 'Tax type invalid',
        self::ERR_GEO_TYPE_INVALID => 'Geo type invalid',
        self::ERR_COUNTRY_REQUIRED => 'Country required',
        self::ERR_COUNTRY_INVALID => 'Country invalid',
        self::ERR_COUNTRY_NOT_ALLOWED => 'Country not available for geo type',
        self::ERR_STATE_REQUIRED => 'State required',
        self::ERR_STATE_NOT_ALLOWED => 'State not available for geo type or country',
        self::ERR_COUNTY_REQUIRED => 'County required',
        self::ERR_COUNTY_NOT_ALLOWED => 'County not available for geo type or country',
        self::ERR_CITY_REQUIRED => 'City required',
        self::ERR_CITY_NOT_ALLOWED => 'City not available for geo type or country',
        self::ERR_TAX_DEFINITION_NOT_FOUND => 'Tax definition not found',
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

    public function addError(int $errorCode): static
    {
        $this->getResultStatusCollector()->addError($errorCode);
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
}
