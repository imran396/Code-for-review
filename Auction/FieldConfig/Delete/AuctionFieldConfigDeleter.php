<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\FieldConfig\Delete;

use AuctionFieldConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AuctionFieldConfig\AuctionFieldConfigWriteRepositoryAwareTrait;

/**
 * Class AuctionFieldConfigDeleter
 * @package Sam\Auction\FieldConfig\Delete
 */
class AuctionFieldConfigDeleter extends CustomizableClass
{
    use AuctionFieldConfigWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Mark auction field config as deleted
     *
     * @param AuctionFieldConfig $auctionFieldConfig
     * @param int $editorUserId
     */
    public function delete(AuctionFieldConfig $auctionFieldConfig, int $editorUserId): void
    {
        $auctionFieldConfig->Active = false;
        $this->getAuctionFieldConfigWriteRepository()->saveWithModifier($auctionFieldConfig, $editorUserId);
    }
}
