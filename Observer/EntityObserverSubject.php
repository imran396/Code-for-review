<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer;

use Sam\Core\Service\CustomizableClass;

/**
 * Class EntityObserverSubject
 * @package Sam\Observer
 */
class EntityObserverSubject extends CustomizableClass
{
    protected object $entity;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param object $entity
     * @return static
     */
    public function construct(object $entity): static
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return object
     */
    public function getEntity(): object
    {
        return $this->entity;
    }

    /**
     * @return bool
     */
    public function isModified(): bool
    {
        return count($this->entity->__Modified) > 0;
    }

    /**
     * @param string $propertyName
     * @return bool
     */
    public function isPropertyModified(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->entity->__Modified);
    }

    /**
     * @param array $propertyNames
     * @return bool
     */
    public function isAnyPropertyModified(array $propertyNames): bool
    {
        foreach ($propertyNames as $propertyName) {
            if (array_key_exists($propertyName, $this->entity->__Modified)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $propertyName
     * @return mixed
     */
    public function getOldPropertyValue(string $propertyName): mixed
    {
        return $this->entity->__Modified[$propertyName] ?? null;
    }

    /**
     * @return array
     */
    public function getOldPropertyValues(): array
    {
        return $this->entity->__Modified;
    }
}
