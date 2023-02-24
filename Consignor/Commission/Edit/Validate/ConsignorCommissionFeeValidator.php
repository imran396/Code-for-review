<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;

/**
 * This class contains methods for checking an entity-level consignor commission fee input data
 *
 * Class ConsignorCommissionFeeValidator
 * @package Sam\Consignor\Commission\Edit\Validate
 */
class ConsignorCommissionFeeValidator extends CustomizableClass
{
    use LotCustomFieldLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Checks if the input value is valid calculation method id or name
     *
     * @param int|string $calculationMethod
     * @return bool
     */
    public function isValidCalculationMethod(int|string $calculationMethod): bool
    {
        $isValid = true;
        if ($calculationMethod) {
            if (
                is_int($calculationMethod)
                || ctype_digit($calculationMethod)
            ) {
                $isValid = in_array((int)$calculationMethod, Constants\ConsignorCommissionFee::CALCULATION_METHODS, true);
            } else {
                $isValid = in_array($calculationMethod, Constants\ConsignorCommissionFee::CALCULATION_METHOD_NAMES, true);
            }
        }
        return $isValid;
    }

    /**
     * Check if an input value is a valid custom field reference or in the fee reference list
     *
     * @param string $feeReference
     * @return bool
     */
    public function isValidFeeReference(string $feeReference): bool
    {
        $isValid = true;
        if ($feeReference) {
            if (str_starts_with($feeReference, Constants\ConsignorCommissionFee::FEE_REFERENCE_CUSTOM_FIELD_PREFIX)) {
                $isValid = $this->isFeeReferenceCustomFieldNumeric($feeReference);
            } else {
                $isValid = in_array($feeReference, Constants\ConsignorCommissionFee::FEE_REFERENCES, true);
            }
        }
        return $isValid;
    }

    /**
     * @param string $feeReference
     * @return bool
     */
    protected function isFeeReferenceCustomFieldNumeric(string $feeReference): bool
    {
        $isNumeric = false;
        $customFieldId = str_replace(
            Constants\ConsignorCommissionFee::FEE_REFERENCE_CUSTOM_FIELD_PREFIX,
            '',
            $feeReference
        );
        if (ctype_digit($customFieldId)) {
            $customField = $this->createLotCustomFieldLoader()->load((int)$customFieldId);
            $isNumeric =
                $customField
                && in_array(
                    $customField->Type,
                    [Constants\CustomField::TYPE_INTEGER, Constants\CustomField::TYPE_DECIMAL],
                    true
                );
        }
        return $isNumeric;
    }
}
