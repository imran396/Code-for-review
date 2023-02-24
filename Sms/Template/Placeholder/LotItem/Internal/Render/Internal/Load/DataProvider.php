<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\LotItem\Internal\Render\Internal\Load;

use Auction;
use LotCategory;
use LotImage;
use LotItemCustData;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class DataProvider
 * @package Sam\Sms\Template\Placeholder\LotItem\Render\Internal\Load
 * @internal
 */
class DataProvider extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotImageLoaderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @var string[]
     */
    protected array $lotCategoryNameCache = [];
    /**
     * @var int[]
     */
    protected array $lotImageIdCache = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadLotCategoryNames(int $lotItemId, bool $isReadOnlyDb = false): array
    {
        if (!isset($this->lotCategoryNameCache[$lotItemId])) {
            $lotCategories = $this->getLotCategoryLoader()->loadForLot($lotItemId, $isReadOnlyDb);
            $this->lotCategoryNameCache[$lotItemId] = array_map(
                static function (LotCategory $category) {
                    return $category->Name;
                },
                $lotCategories
            );
        }
        return $this->lotCategoryNameCache[$lotItemId];
    }

    public function loadLotImageIds(int $lotItemId, bool $isReadOnlyDb = false): array
    {
        if (!isset($this->lotImageIdCache[$lotItemId])) {
            $images = $this->getLotImageLoader()->loadForLot($lotItemId, [], $isReadOnlyDb);
            $this->lotImageIdCache[$lotItemId] = array_map(
                static function (LotImage $image) {
                    return $image->Id;
                },
                $images
            );
        }
        return $this->lotImageIdCache[$lotItemId];
    }

    public function loadUsername(?int $userId, bool $isReadOnlyDb = false): string
    {
        $user = $this->getUserLoader()->load($userId, $isReadOnlyDb);
        return $user->Username ?? '';
    }

    public function loadAuction(?int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb);
    }

    public function loadLotCustomFields(bool $isReadOnlyDb = false): array
    {
        return $this->createLotCustomFieldLoader()->loadAll($isReadOnlyDb);
    }

    public function loadLotCustomFieldData(int $customFieldId, int $lotItemId, bool $isReadOnlyDb = false): ?LotItemCustData
    {
        return $this->createLotCustomDataLoader()->load($customFieldId, $lotItemId, $isReadOnlyDb);
    }
}
