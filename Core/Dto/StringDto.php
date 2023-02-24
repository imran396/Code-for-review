<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           3/21/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class StringDto
 * @package
 */
class StringDto extends CustomizableClass
{
    protected array $items = [];
    /** @var string[] */
    protected array $availableFields = [];
    /**
     * We trim() input by default
     * @var string[]
     */
    protected array $noTrimFields = [];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        return $this->items[$name] ?? null;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, mixed $value)
    {
        if (in_array($name, $this->availableFields, true)) {
            $this->items[$name] = (is_array($value)
                || in_array($name, $this->noTrimFields, true))
                ? $value : trim((string)$value);
        } else {
            throw new \LogicException(sprintf('Unknown Dto property "%s"', $name));
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name)
    {
        return array_key_exists($name, $this->items);
    }

    /**
     * @param string $name
     */
    public function __unset(string $name)
    {
        unset($this->items[$name]);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @param array $names
     * @return $this
     */
    protected function setAvailableProperties(array $names): static
    {
        $this->availableFields = $names;
        return $this;
    }
}
