<?php
/**
 * SAM-4629: Refactor custom lot report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\CustomList\Media\Base;

use Currency;
use Sam\Core\Service\CustomizableClass;
use LotCategory;
use LotImage;
use Sam\Core\Constants;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoader;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * This class contains helper methods for building rows of lot custom list report
 *
 * Class LotCustomListRowBuilderHelper
 * @package Sam\Report\Lot\CustomList\Media\Base
 */
class LotCustomListRowBuilderHelper extends CustomizableClass
{
    use CurrencyLoaderAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotImageLoaderAwareTrait;
    use SettingsManagerAwareTrait;

    /** @var array */
    private array $lotCategories = [];
    /** @var array */
    private array $lotCategoryPaths = [];
    /** @var array|LotImage[] */
    private array $lotImages = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return all category paths (array of category with its ancestors) for lot
     * @param int $lotItemId
     * @return array(LotCategory->Id => LotCategory[])
     */
    public function getLotCategoryPaths(int $lotItemId): array
    {
        $lotCategories = $this->getLotCategories($lotItemId);
        return array_map(
            function (LotCategory $lotCategory) {
                return $this->getCategoryPath($lotCategory);
            },
            $lotCategories
        );
    }

    /**
     * Return categories related to lot
     * @param int $lotItemId
     * @return array
     */
    public function getLotCategories(int $lotItemId): array
    {
        if (!array_key_exists($lotItemId, $this->lotCategories)) {
            $this->lotCategories[$lotItemId] = LotCategoryLoader::new()->loadForLot($lotItemId, true);
        }

        return $this->lotCategories[$lotItemId];
    }

    /**
     * Return array of all available currencies
     * @param int[] $ids
     * @return array|Currency[]
     */
    public function getCurrencies(array $ids): array
    {
        return array_filter(
            $this->getCurrencyLoader()->loadAll(),
            static function (Currency $currency) use ($ids) {
                return in_array($currency->Id, $ids, false);
            }
        );
    }

    /**
     * Return images related to lot
     * @param int $lotItemId
     * @return array
     */
    public function getLotImages(int $lotItemId): array
    {
        if (!array_key_exists($lotItemId, $this->lotImages)) {
            $this->lotImages[$lotItemId] = $this->getLotImageLoader()->loadForLot($lotItemId, [], true);
        }
        return $this->lotImages[$lotItemId];
    }

    /**
     * @param int $accountId
     * @return string
     */
    public function getEncoding(int $accountId): string
    {
        return $this->getSettingsManager()->get(Constants\Setting::DEFAULT_EXPORT_ENCODING, $accountId);
    }

    /**
     * Return path to the category
     * @param LotCategory $category
     * @return array
     */
    private function getCategoryPath(LotCategory $category): array
    {
        if (!array_key_exists($category->Id, $this->lotCategoryPaths)) {
            $this->lotCategoryPaths[$category->Id] = array_reverse(
                $this->getLotCategoryLoader()->loadCategoryWithAncestors($category)
            );
        }

        return $this->lotCategoryPaths[$category->Id];
    }
}
