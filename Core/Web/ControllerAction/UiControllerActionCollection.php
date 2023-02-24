<?php
/**
 * This is Ui-Controller-Action indexed collection of any values.
 * E.g. 1 => [ 'controller1' => ['action11' => 'value11', ...], 2 => [ ... ], ]
 *
 * SAM-6846: ControllerActionCollection for simplifying operations on route related values
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 31, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Web\ControllerAction;

use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;

/**
 * Class UiControllerActionCollection
 * @package Sam\Core\Web\ControllerAction
 */
class UiControllerActionCollection extends CustomizableClass
{
    /** @var ControllerActionCollection[] */
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
     * Constructor for input structures with Ui as key (See, integer Constants\AuctionParameters::UI_RESPONSIVE, UI_ADMIN, UI_CLI)
     * e.g. 1 => [ 'controller1' => ['action11' => 'value11', ...], 2 => [ ... ], ]
     * @param array $items
     * @return $this
     */
    public function construct(array $items = []): static
    {
        $this->items = $this->fromArray($items);
        return $this;
    }

    /**
     * Constructor for input structures with Ui Area as key,
     * e.g. 'admin' => [ 'controller1' => ['action11' => 'value11', ...], 'm' => [ ... ], ]
     * @param array $items
     * @return $this
     */
    public function fromUiAreaArray(array $items = []): static
    {
        $results = [];
        foreach ($items as $uiArea => $value) {
            // Ui area is the same as the directory
            $ui = Ui::new()->constructByDir($uiArea);
            $results[$ui->value()] = $value;
        }
        return $this->construct($results);
    }

    // --- Presence check ---

    /**
     * Check for presence of value
     * @param Ui $ui
     * @param string $controller
     * @param string $action
     * @return bool
     */
    public function has(Ui $ui, string $controller, string $action): bool
    {
        $caCollection = $this->findControllerActionCollectionForUi($ui);
        if (!$caCollection) {
            return false;
        }
        return $caCollection->has($controller, $action);
    }

    /**
     * Checks, if UI key registered in collection
     * @param Ui $ui
     * @return bool
     */
    public function hasUi(Ui $ui): bool
    {
        $caCollection = $this->findControllerActionCollectionForUi($ui);
        return (bool)$caCollection;
    }

    /**
     * Check if controller presents in collection for specific UI key
     * @param Ui $ui
     * @param string $controller
     * @return bool
     */
    public function hasUiController(Ui $ui, string $controller): bool
    {
        $caCollection = $this->findControllerActionCollectionForUi($ui);
        if (!$caCollection) {
            return false;
        }
        return $caCollection->hasController($controller);
    }

    // --- Read logic ---

    /**
     * Read value from collection
     * @param Ui $ui
     * @param string $controller
     * @param string $action
     * @return mixed null means non-registered value and null value as well. Use has() to check.
     */
    public function get(Ui $ui, string $controller, string $action): mixed
    {
        $caCollection = $this->findControllerActionCollectionForUi($ui);
        return $caCollection?->get($controller, $action);
    }

    /**
     * Convert collection to array (expected in main constructor)
     * @return array
     */
    public function toArray(): array
    {
        $result = [];
        foreach ($this->items as $ui => $caCollection) {
            $result[$ui] = $caCollection->toArray();
        }
        return $result;
    }

    // --- Write logic ---

    /**
     * Set value for specified ui, controller and action. It overwrites existing value.
     * @param Ui $ui
     * @param string $controller
     * @param string $action
     * @param $value
     * @return $this
     */
    public function set(Ui $ui, string $controller, string $action, $value): static
    {
        $uiType = (string)$ui;
        if (!isset($this->items[$uiType])) {
            $this->items[$uiType] = ControllerActionCollection::new()->construct();
        }

        $this->items[$uiType]->set($controller, $action, $value);
        return $this;
    }

    // --- Internal logic ---

    /**
     * Build internal storage structure with help of ControllerActionCollection records
     * @param array $arr
     * @return array
     */
    protected function fromArray(array $arr = []): array
    {
        $items = [];
        foreach ($arr as $ui => $caData) {
            $items[$ui] = ControllerActionCollection::new()->construct($caData);
        }
        return $items;
    }

    /**
     * @param Ui $ui
     * @return ControllerActionCollection|null null when does not exist
     */
    protected function findControllerActionCollectionForUi(Ui $ui): ?ControllerActionCollection
    {
        return $this->items[$ui->value()] ?? null;
    }
}
