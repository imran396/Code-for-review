<?php
/**
 * SAM-5651: Refactor Lot No auto filling service
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotNo\Fill\CustomField\Save;

use AuctionLotItem;
use Sam\AuctionLot\LotNo\Fill\CustomField\Load\LotNoByCustomFieldLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class LotNoByCustomFieldProducer
 * @package Sam\AuctionLot\LotNo\Fill\CustomField\Save
 */
class LotNoByCustomFieldProducer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LotNoByCustomFieldLoaderCreateTrait;

    /**
     * @const regular expression to parse custom field data and extract lot prefix/number/extension
     */
    private const LOT_NUM_REGEX = '/^([a-z]{0,20})(\d{0,10})([a-z]{0,3})$/i';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Produce lot# by custom field value
     *
     * @param AuctionLotItem $auctionLot
     * @return array ('LotNum' => x, 'LotNumExt' => x, 'LotNumPrefix' => x) | [];
     */
    public function produce(AuctionLotItem $auctionLot): array
    {
        $value = $this->getLotNoCustomFieldValue($auctionLot->LotItemId);
        return $this->detectLotNoParts($value);
    }

    /**
     * Detect lot# from passed value
     *
     * @param string|int|null $value
     * @return array ('LotNum' => x, 'LotNumExt' => x, 'LotNumPrefix' => x) | null;
     */
    private function detectLotNoParts(int|string|null $value): array
    {
        $lotNoParts = [];
        if (preg_match(self::LOT_NUM_REGEX, (string)$value, $matches)) {
            $lotNoParts = [
                'LotNum' => $matches[2],
                'LotNumExt' => $matches[3],
                'LotNumPrefix' => $matches[1],
            ];

            if (!$lotNoParts['LotNum'] || $lotNoParts['LotNum'] > $this->cfg()->get('core->db->mysqlMaxInt')) {
                $lotNoParts = [];
            }
        }
        return $lotNoParts;
    }

    /**
     * @param int $lotItemId
     * @return string|int|null
     */
    private function getLotNoCustomFieldValue(int $lotItemId): string|int|null
    {
        $value = $this->createLotNoByCustomFieldLoader()->loadCustomFieldValue($lotItemId);
        if (is_string($value)) {
            $value = preg_replace('/[^0-9a-z]+/i', '', $value);
        }
        return (string)$value !== '' ? $value : null;
    }
}
