<?php
/**
 * File-type custom auction field editing component
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: EditControls.php 13797 2013-07-09 15:26:18Z SWB\igors $
 * @since           Jul 20, 2013
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Qform\Component;

use RuntimeException;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;

/**
 * Class FileEdit
 * @method \Sam\CustomField\Base\Qform\Component\BaseEdit getBaseComponent()
 */
class FileEdit extends BaseEdit
{
    use AuctionLoaderAwareTrait;

    protected int $type = Constants\CustomField::TYPE_FILE;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return void
     */
    public function create(): void
    {
        // next 2 methods are independent each of other at the moment of 2019
        $this->getBaseComponent()->create();
        $this->refreshFilePath();
    }

    /**
     * Define FilePath property - absolute file path to permanent location
     *
     * @return void
     */
    public function refreshFilePath(): void
    {
        $auctionId = $this->getBaseComponent()->getRelatedEntityId();
        if ($auctionId) {
            $auction = $this->getAuctionLoader()->load($auctionId, true);
            if (!$auction) {
                throw new RuntimeException("Auction not found by id: " . $auctionId);
            }
            $dirName = $auction->AccountId;
        } else {
            // don't know related auction at new user create
            // auction id => account id => directory will be updated on save
            $dirName = 'unknown';
        }
        // We store all account auction files in one directory
        // we extend duplicated filename by id <filename>__<auction.id>.<extension>
        $this->getBaseComponent()->setFilePath(path()->uploadAuctionCustomFieldFile() . '/' . $dirName);
    }
}
