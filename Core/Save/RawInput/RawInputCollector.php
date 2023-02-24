<?php
/**
 * This class should help store initial values of externally defined properties
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           4/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Save\RawInput;

use Sam\Core\Service\CustomizableClass;

/**
 * Class RawInputCollector
 * @package Sam\Core\Save\RawInput
 */
class RawInputCollector extends CustomizableClass
{
    public array $rawInput = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, if $property is assigned
     * @param string $property
     * @return bool
     */
    public function hasRawInput(string $property): bool
    {
        $has = array_key_exists($property, $this->rawInput);
        return $has;
    }

    /**
     * Return raw input value
     * @param string $property
     * @return mixed
     */
    public function getRawInput(string $property): mixed
    {
        $value = $this->hasRawInput($property) ? $this->rawInput[$property] : null;
        return $value;
    }

    /**
     * Set raw input data
     * @param string $property
     * @param mixed $value
     * @return static
     */
    public function setRawInput(string $property, mixed $value): static
    {
        if (!array_key_exists($property, $this->rawInput)) {
            // We set raw input value only once at instance initialization stage
            $this->rawInput[$property] = $value;
        }
        return $this;
    }
}
