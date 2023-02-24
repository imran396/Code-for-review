<?php
/**
 * Help methods for auction custom field data loading
 *
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 29, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Save;

use AuctionCustData;
use AuctionCustField;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AuctionCustData\AuctionCustDataWriteRepositoryAwareTrait;

/**
 * Class AuctionCustomDataUpdater
 * @package Sam\CustomField\Auction\Save
 */
class AuctionCustomDataUpdater extends CustomizableClass
{
    use AuctionCustDataWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Save custom field data and perform related actions
     * We never use second $auctionCustomField parameter in AuctionCustomDataUpdater,
     * but it should present, when we call call $this->dataUpdater->save() for UserCustomDataUpdater
     *
     * @param AuctionCustData $auctionCustomData
     * @param AuctionCustField $auctionCustomField
     * @param int $editorUserId
     * @return void
     */
    public function save(AuctionCustData $auctionCustomData, AuctionCustField $auctionCustomField, int $editorUserId): void
    {
        $this->getAuctionCustDataWriteRepository()->saveWithModifier($auctionCustomData, $editorUserId);
    }
}
