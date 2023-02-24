<?php
/**
 * It is pure mutable service, that is intended to simplify operations on route related values.
 *
 * This is collection with useful methods, that helps to operate on next structure:
 * [
 *   "controller name 1" => [
 *       "action name 1.1" => <some value 1.1>,
 *       "action name 1.2" => <some value 1.2>,
 *       ...
 *   ],
 *   "controller name 2" => [
 *       "action name 2.1" => <some value 2.1>,
 *   ]
 *   ...
 * ]
 *
 * SAM-6846: ControllerActionCollection for simplifying operations on route related values
 * SAM-6756: Refactor Front-end breadcrumb module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 27, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Web\ControllerAction;

use Sam\Core\Service\CustomizableClass;

/**
 * Class ControllerActionCollection
 * @package Sam\Core\Web\ControllerAction
 */
class ControllerActionCollection extends CustomizableClass
{
    private array $items;

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Construct collection from expected structure.
     * @param array $items
     * @return $this
     */
    public function construct(array $items = []): static
    {
        $this->items = $items;
        return $this;
    }

    // --- Presence check ---

    /**
     * @param string $controller
     * @param string $action
     * @return bool
     */
    public function has(string $controller, string $action): bool
    {
        return $this->hasController($controller)
            && array_key_exists($action, $this->actions($controller));
    }

    /**
     * @param string $controller
     * @return bool
     */
    public function hasController(string $controller): bool
    {
        return array_key_exists($controller, $this->items);
    }

    // --- Read logic ---

    /**
     * Read value from collection
     * @param string $controller
     * @param string $action
     * @return mixed null means non-registered value and null value as well. Use has() to check.
     */
    public function get(string $controller, string $action): mixed
    {
        return $this->items[$controller][$action] ?? null;
    }

    /**
     * Convert collection to array
     * @return array
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * Read all actions (as key) with values
     * @param string $controller
     * @return array return empty array [] even when controller name is not registered
     */
    public function actions(string $controller): array
    {
        return $this->items[$controller] ?? [];
    }

    /**
     * To single-dimension array filled merely with values of this collection.
     * @return array
     */
    public function flatten(): array
    {
        $values = [];
        foreach ($this->items as $actions) {
            $values = array_merge($values, array_values($actions));
        }
        return $values;
    }

    // --- Write logic ---

    /**
     * Set value for specified controller and action. It overwrites existing value.
     * @param string $controller
     * @param string $action
     * @param $value
     * @return $this
     */
    public function set(string $controller, string $action, $value): static
    {
        if (!isset($this->items[$controller])) {
            $this->items[$controller] = [];
        }
        $this->items[$controller][$action] = $value;
        return $this;
    }

    /**
     * Apply function to every element of collection and assign result to value
     * @param callable $transformFn (string $controller, string $action, mixed $value)
     * @return $this
     */
    public function applyToAll(callable $transformFn): static
    {
        foreach ($this->items as $controller => $actions) {
            foreach ($actions as $action => $value) {
                $this->set($controller, $action, $transformFn($controller, $action, $value));
            }
        }
        return $this;
    }
}
