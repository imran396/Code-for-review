<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Boanerge Regidor
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/30/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Video\Load;

use Sam\Core\Service\CustomizableClass;
use LotItemCustData;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class LotVideoLoader
 * @package Sam\Lot\Video\Load
 */
class LotVideoLoader extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldLoaderCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load all images for lot
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return LotItemCustData[]
     */
    public function loadForLot(int $lotItemId, bool $isReadOnlyDb = false): array
    {
        $lotCustomDatas = [];
        if ($this->cfg()->get('core->lot->video->enabled')) {
            $youtubeLinkLotCustomFields = $this->createLotCustomFieldLoader()->loadYoutubeLinkFields($isReadOnlyDb);
            foreach ($youtubeLinkLotCustomFields as $youtubeLinkLotCustomField) {
                $lotCustomData = $this->createLotCustomDataLoader()
                    ->load($youtubeLinkLotCustomField->Id, $lotItemId, $isReadOnlyDb);
                if ($lotCustomData && $lotCustomData->Text) {
                    $lotCustomDatas[] = $lotCustomData;
                }
            }
        }
        return $lotCustomDatas;
    }
}
