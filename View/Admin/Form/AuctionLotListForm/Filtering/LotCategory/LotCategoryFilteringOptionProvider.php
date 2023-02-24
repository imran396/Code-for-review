<?php
/**
 * SAM-6780: Move sections' logic to separate Panel classes at Manage auction lots page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\Filtering\LotCategory;

use LotCategory;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Lot\Category\Tree\LotCategoryFullTreeManager;
use Sam\Lot\Category\Tree\LotCategoryFullTreeManagerAwareTrait;
use Sam\Qform\Control\ListBox\ListBoxOption;

/**
 * Class LotCategoryFilteringOptionProvider
 * @package Sam\View\Admin\Form\AuctionLotListForm\Filtering\LotCategory
 */
class LotCategoryFilteringOptionProvider extends CustomizableClass
{
    use LotCategoryFullTreeManagerAwareTrait;
    use OptionalsTrait;

    public const OP_LOT_CATEGORY_ALL = 'lotCategoryAll';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Load data in array of tuples [value, name]
     * @return array
     */
    public function loadTuples(): array
    {
        $results[] = [null, 'All'];
        /** @var LotCategory[] $lotCategories */
        $lotCategories = $this->fetchOptional(self::OP_LOT_CATEGORY_ALL);
        foreach ($lotCategories as $cat) {
            $prefix = str_repeat('-', $cat->Level);
            $results[] = [$cat->Id, $prefix . $cat->Name];
        }
        return $results;
    }

    /**
     * @return ListBoxOption[]
     */
    public function loadOptions(): array
    {
        $options[] = new ListBoxOption(null, 'All');
        /** @var LotCategory[] $lotCategories */
        $lotCategories = $this->fetchOptional(self::OP_LOT_CATEGORY_ALL);
        foreach ($lotCategories as $cat) {
            $prefix = str_repeat('-', $cat->Level);
            $options[] = new ListBoxOption($cat->Id, $prefix . $cat->Name);
        }
        return $options;
    }

    /**
     * @param array $optionals
     * @return void
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_LOT_CATEGORY_ALL] = $optionals[self::OP_LOT_CATEGORY_ALL]
            ?? static function (): array {
                return LotCategoryFullTreeManager::new()->load();
            };
        $this->setOptionals($optionals);
    }
}
