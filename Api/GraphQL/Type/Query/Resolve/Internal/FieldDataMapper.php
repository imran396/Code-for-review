<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\Internal;

use Sam\Core\Service\CustomizableClass;

/**
 * Class FieldDataMapper
 * @package Sam\Api\GraphQL\Type\Query\Resolve\Internal
 */
class FieldDataMapper extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function mapDataToType(array $objectValue, array $typeFieldToDataMap): array
    {
        $mapping = array_flip($typeFieldToDataMap);
        $result = [];
        foreach ($mapping as $dataFieldName => $accountFieldName) {
            if (array_key_exists($dataFieldName, $objectValue)) {
                $result[$accountFieldName] = $objectValue[$dataFieldName];
            }
        }
        return $result;
    }

    public function hasMappingForAllReferencedFields(array $referencedFields, array $typeFieldToDataMap): bool
    {
        return !array_diff($referencedFields, array_keys($typeFieldToDataMap));
    }

    public function collectDataFields(array $referencedFields, array $typeFieldToDataMap): array
    {
        $dataFields = array_map(
            static fn(string $referencedField) => $typeFieldToDataMap[$referencedField] ?? null,
            $referencedFields
        );
        return array_filter($dataFields);
    }
}
