<?php
/**
 * Data wrapper (class, property, values) for FilterConformityChecker
 *
 * SAM-4922: Entity Loader and Existence Checker approach integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
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
 * Class FilterDescriptor
 * @package Sam\Core\Filter
 */
class FilterDescriptor extends CustomizableClass
{
    /** @var string */
    public string $class = '';
    /** @var string */
    public string $property = '';
    /** @var array */
    public array $values = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $class
     * @param string $property
     * @param mixed $values
     * @return static
     */
    public function init(string $class, string $property, mixed $values): static
    {
        $this->class = $class;
        $this->property = $property;
        $this->values = is_array($values) ? $values : [$values];
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [$this->class, $this->property, $this->values];
    }
}
