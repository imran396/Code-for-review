<?php
/**
 * SAM-6936: Form state consistency problem - enable longevityTracking by default
 * SAM-4898: Form state consistency problem
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Longevity;

use ReflectionClass;
use ReflectionProperty;
use Sam\Core\Service\CustomizableClass;

/**
 * Class FormStateCleaner
 * @package Sam\Qform\Longevity
 */
class FormStateCleaner extends CustomizableClass
{
    protected array $droppedDependencyObjectIds = [];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        return $this;
    }

    public function unsetRecursively($object): void
    {
        $objectId = spl_object_id($object);
        if (isset($this->droppedDependencyObjectIds[$objectId])) {
            // log_trace('Skip dropping dependencies of ' . (new ReflectionClass($object))->getName());
            return;
        } else {
            // log_trace('Start dropping dependencies of ' . (new ReflectionClass($object))->getName());
        }
        $this->droppedDependencyObjectIds[$objectId] = true;

        $reflectionClass = new ReflectionClass($object);
        $reflectionProperties = $reflectionClass->getProperties(
            ReflectionProperty::IS_PUBLIC
            | ReflectionProperty::IS_PROTECTED
            | ReflectionProperty::IS_PRIVATE
            | ReflectionProperty::IS_STATIC
        );

        $longevityEnabledProperties = [];
        foreach ($reflectionProperties as $reflectionProperty) {
            $reflectionProperty->setAccessible(true);
            $isInitialized = $reflectionProperty->isInitialized($object);
            if (!$isInitialized) {
                continue;
            }
            $property = $reflectionProperty->getValue($object);
            if (is_object($property)) {
                $propertyReflectionClass = new ReflectionClass($property);
                $isFormStateLongevity = $propertyReflectionClass->hasMethod('isFormStateLongevity')
                    && $property->isFormStateLongevity();
                if ($isFormStateLongevity) {
                    $longevityEnabledProperties[] = $property;
                } else {
                    $reflectionProperty->setValue($object, null);
                    // log_trace('Drop ' . (new ReflectionClass($object))->getName());
                }
            }
        }

        foreach ($longevityEnabledProperties as $property) {
            $this->unsetRecursively($property);
        }
    }
}
