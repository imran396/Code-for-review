<?php
/**
 *
 * SAM-11530 : Auction Sales group : Groups are getting unlinked if user creates new group with same name
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionSettingForm\SaleGroup\Edit\Common;

use Auction;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionSettingSaleGroupEditingInput
 * @package Sam\View\Admin\Form\AuctionSettingForm\SaleGroup\Edit\Common
 */
class AuctionSettingSaleGroupEditingInput extends CustomizableClass
{
    public Auction $auction;
    public int $editorUserId;
    public string $saleGroupName;
    public array $auctionIds;
    public bool $isSimultaneousAuction;
    public bool $isAllowEmpty;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        Auction $auction,
        int $editorUserId,
        string $saleGroupName,
        array $auctionIds,
        bool $isSimultaneousAuction,
        bool $isAllowEmpty
    ): static {
        $this->auction = $auction;
        $this->editorUserId = $editorUserId;
        $this->saleGroupName = $saleGroupName;
        $this->auctionIds = $auctionIds;
        $this->isSimultaneousAuction = $isSimultaneousAuction;
        $this->isAllowEmpty = $isAllowEmpty;
        return $this;
    }

    public function logData(): array
    {
        return [
            'auction Name' => $this->auction->Name,
            'saleGroupName' => $this->saleGroupName,
            'auctionIds' => $this->auctionIds,
            'isSimultaneousAuction' => $this->isSimultaneousAuction,
            'isAllowEmpty' => $this->isAllowEmpty
        ];
    }
}
