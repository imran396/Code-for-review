<?php
/**
 * Compare checking and cached filter descriptors (class, property, values)
 *
 * SAM-4922: Entity Loader and Existence Checker approach integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff, Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 28, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\Conformity;

use Sam\Core\Service\CustomizableClass;

/**
 * Class FilterConformityChecker
 * @package Sam\Core\Filter\Conformity
 */
class FilterConformityChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Conformity checks, that checking filter set is superset of cached filter set,
     * or vice versa - cached filter set is subset of checking filter set.
     * Except empty set cases:
     * - When checking filter set is empty - it means any filter or Power set, then conformity is met for any cached filter set.
     * - When cached filter set is empty (but not null) and checking filter set is non-empty, then conformity is not met.
     * - When cached filter set is undefined (is null), then conformity is not met.
     *
     * @param FilterDescriptor[]|null $checkingFilterDescriptors
     * @param FilterDescriptor[]|null $cachedFilterDescriptors
     * @return bool
     */
    public function conform(?array $checkingFilterDescriptors, ?array $cachedFilterDescriptors): bool
    {
        if ($cachedFilterDescriptors === null) {
            return false;
        }

        if (!$checkingFilterDescriptors) {
            return true;
        }

        if (!$cachedFilterDescriptors) {
            return false;
        }

        $isMet = true;
        foreach ($checkingFilterDescriptors as $filterDescriptor) {
            [$checkingClass, $checkingProperty, $checkingValues] = $filterDescriptor->toArray();
            if (!$checkingValues) {
                continue;
            }
            // ll("Checking class: {$checkingClass}, property: {$checkingProperty}, values: " . json_encode($checkingValues));
            $isClassAndProperty = false;
            foreach ($cachedFilterDescriptors as $cachedFilterDescriptor) {
                [$cachedClass, $cachedProperty, $cachedValues] = $cachedFilterDescriptor->toArray();
                if (
                    $cachedClass === $checkingClass
                    && $cachedProperty === $checkingProperty
                ) {
                    $isClassAndProperty = true;
                    $intersected = array_intersect($cachedValues, $checkingValues);
                    $isMet = count($intersected) === count($cachedValues);
                    if (!$isMet) {
                        // ll("Class {$checkingClass} and property {$checkingProperty} found, values are not met. Checking: "
                        //     . json_encode($checkingValues) . ', cached: ' . json_encode($cachedValues));
                        break 2;
                    }
                }
            }
            if (!$isClassAndProperty) {
                $isMet = false;
                // ll("Class {$checkingClass} or property {$checkingProperty} not found");
                break 1;
            }
        }
        return $isMet;
    }
}
