<?php
/**
 * SAM-11530 : Auction Sales group : Groups are getting unlinked if user creates new group with same name
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionSettingForm\SaleGroup\Edit\Save;

use Auction;
use Sam\Auction\SaleGroup\SaleGroupManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;
use Sam\View\Admin\Form\AuctionSettingForm\SaleGroup\Edit\Common\AuctionSettingSaleGroupEditingInput;

/**
 * Class AuctionSettingSaleGroupEditingSaver
 * @package Sam\View\Admin\Form\AuctionSettingForm\SaleGroup\Edit\Save
 */
class AuctionSettingSaleGroupEditingSaver extends CustomizableClass
{
    use SaleGroupManagerAwareTrait;
    use AuctionWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function update(AuctionSettingSaleGroupEditingInput $input): Auction
    {
        $auction = $input->auction;
        if ($input->saleGroupName !== '') {
            $this->getSaleGroupManager()->addAuctions(
                $input->saleGroupName,
                $input->auctionIds,
                $input->editorUserId,
                $input->isSimultaneousAuction,
                $auction->Id
            );
            $auction->SaleGroup = $input->saleGroupName;
        } elseif ($input->isAllowEmpty) { // $name === ''
            // if user had clicked ok on the confirmation dialog
            $this->getSaleGroupManager()->removeAuctionsByAccountId(
                $input->saleGroupName,
                $auction->AccountId,
                $input->editorUserId,
                $auction->Id
            );
            $auction->SaleGroup = '';
        } else {
            return $auction;
        }
        $auction->Simultaneous = $input->isSimultaneousAuction;
        $this->getAuctionWriteRepository()->saveWithModifier($auction, $input->editorUserId);
        return $auction;
    }
}
