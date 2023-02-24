<?php
/**
 * Produce rtb response data
 *
 * SAM-5201: Apply constants for response data and keys
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/23/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Response\Concrete;

use RtbCurrent;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\BuyerGroup\Access\LotBuyerGroupAccessHelperAwareTrait;
use Sam\Lot\BuyerGroup\Validate\LotBuyerGroupExistenceCheckerAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;

/**
 * Class ResponseDataProducer
 * @package Sam\Rtb\Command\Response
 */
class BuyerGroupDataProducer extends CustomizableClass
{
    use LotBuyerGroupAccessHelperAwareTrait;
    use LotBuyerGroupExistenceCheckerAwareTrait;
    use LotItemLoaderAwareTrait;
    use TranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Generate data related to lot grouping via group id feature
     * @param RtbCurrent $rtbCurrent
     * @return array = [
     *  Constants\Rtb::RES_BUYER_GROUP_IDS => string, // TODO: int[]
     * ]
     */
    public function produceData(RtbCurrent $rtbCurrent): array
    {
        $lotItem = $this->getLotItemLoader()->load($rtbCurrent->LotItemId);
        $hasBuyerGroup = $this->getLotBuyerGroupExistenceChecker()->exist();
        if (!$lotItem || !$hasBuyerGroup) {
            return [];
        }
        $buyerGroupIds = $this->getLotBuyerGroupAccessHelper()->loadBuyerGroupIds($lotItem->Id);
        $data[Constants\Rtb::RES_BUYER_GROUP_IDS] = implode(',', $buyerGroupIds);
        return $data;
    }
}
