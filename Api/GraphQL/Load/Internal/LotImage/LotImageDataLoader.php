<?php
/**
 * SAM-10957: GraphQL item image extension
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\LotImage;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotImage\LotImageReadRepositoryCreateTrait;

/**
 * Class LotImageDataLoader
 * @package Sam\Api\GraphQL\Load\Internal\LotImage
 */
class LotImageDataLoader extends CustomizableClass
{
    use LotImageReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadForLot(array $lotItemIds, array $fields, bool $isReadOnlyDb): array
    {
        $repository = $this->createLotImageReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemId($lotItemIds)
            ->joinLotItem()
            ->orderByOrder()
            ->orderById();

        if (!in_array('lot_item_id', $fields, true)) {
            $fields[] = 'lot_item_id';
        }
        foreach ($fields as $field) {
            if ($field === 'account_id') {
                $repository
                    ->joinLotItem()
                    ->addSelect('li.account_id');
            } else {
                $repository->addSelect(Constants\Db::A_LOT_IMAGE . '.' . $field);
            }
        }

        $imagesForLot = array_fill_keys($lotItemIds, []);
        foreach ($repository->loadRows() as $image) {
            $imagesForLot[$image['lot_item_id']][] = $image;
        }

        return $imagesForLot;
    }
}
