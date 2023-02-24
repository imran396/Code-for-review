<?php
/**
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 30, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Edit\Normalize;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionParametersNormalizerBase
 * @package Sam\Settings\Edit\Normalize
 */
abstract class AuctionParametersNormalizerBase extends CustomizableClass implements NormalizerInterface
{
    use PropertyMetadataProviderCreateTrait;

    /**
     * @inheritDoc
     */
    public function normalize(string $property, mixed $value): mixed
    {
        $propertyType = $this->getPropertyType($property);
        if ($value === '') {
            return $this->normalizeEmptyValue($property);
        }
        return match ($propertyType) {
            Constants\Type::T_INTEGER => $this->toInteger($value),
            Constants\Type::T_FLOAT => $this->toFloat($value),
            Constants\Type::T_BOOL => $this->toBoolean($value),
            Constants\Type::T_ARRAY => $this->toList($value),
            default => $value,
        };
    }

    /**
     * @param string $property
     * @return mixed
     */
    private function normalizeEmptyValue(string $property): mixed
    {
        if ($this->isNullableProperty($property)) {
            return null;
        }

        $propertyType = $this->getPropertyType($property);
        if ($propertyType === Constants\Type::T_STRING) {
            return '';
        }

        if ($propertyType === Constants\Type::T_FLOAT) {
            return 0.;
        }

        if ($propertyType === Constants\Type::T_INTEGER) {
            return 0;
        }

        return null;
    }

    /**
     * @param string $property
     * @return bool
     */
    private function isNullableProperty(string $property): bool
    {
        return $this->createPropertyMetadataProvider()->isNullable($property);
    }

    /**
     * @param string $property
     * @return string
     */
    private function getPropertyType(string $property): string
    {
        return $this->createPropertyMetadataProvider()->getType($property);
    }
}
