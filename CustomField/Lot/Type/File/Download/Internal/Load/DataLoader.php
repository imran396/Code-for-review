<?php
/**
 * SAM-5721  Refactor lot custom field file download for web
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Maxim Lyubetskiy
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       June 29, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Type\File\Download\Internal\Load;

use AuctionLotItem;
use LotItem;
use LotItemCustData;
use Sam\AuctionLot\Load\AuctionLotLoader;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoader;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @internal
 */
class DataLoader extends CustomizableClass
{
    use LotItemCustDataReadRepositoryCreateTrait;

    protected ?LotItem $lotItem = null;
    /** @var AuctionLotItem[]|null */
    protected ?array $auctionLots = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * @param int $lotCustomFieldId
     * @param int $lotItemId
     * @return array
     */
    public function loadCustomDataWithAccess(int $lotCustomFieldId, int $lotItemId): array
    {
        $select = ["licd.text", "licf.access"];
        $row = $this->createLotItemCustDataReadRepository()
            ->filterActive(true)
            ->filterLotItemCustFieldId($lotCustomFieldId)
            ->filterLotItemId($lotItemId)
            ->joinLotItemCustomField()
            ->select($select)
            ->loadRow();
        return [$row['text'] ?? null, $row['access'] ?? null];
    }

    /**
     * @param int $lotItemId
     * @return LotItem|null
     */
    public function loadLotItem(int $lotItemId): ?LotItem
    {
        if ($this->lotItem === null) {
            $this->lotItem = LotItemLoader::new()->load($lotItemId, true);
        }
        return $this->lotItem;
    }

    /**
     * @param int $lotItemId
     * @param string $fileName
     * @return LotItemCustData|null
     */
    public function loadLotCustomDataByLotItemIdAndFilename(int $lotItemId, string $fileName): ?LotItemCustData
    {
        return LotItemCustDataReadRepository::new()
            ->filterLotItemId($lotItemId)
            ->joinLotItemCustFieldFilterType(Constants\CustomField::TYPE_FILE)
            ->likeText($fileName)
            ->loadEntity();
    }

    /**
     * @param int $lotItemId
     * @return array
     */
    public function loadAuctionLotsByLotItemId(int $lotItemId): array
    {
        if ($this->auctionLots === null) {
            $this->auctionLots = AuctionLotLoader::new()->loadByLotItemId($lotItemId);
        }
        return $this->auctionLots;
    }
}
