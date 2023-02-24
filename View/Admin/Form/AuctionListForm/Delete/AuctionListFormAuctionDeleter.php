<?php
/**
 * SAM-10981: Replace GET->POST for delete button at Admin Manage auctions page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionListForm\Delete;

use Sam\Auction\Delete\Update\AuctionDeleterCreateTrait;
use Sam\Auction\Delete\Validate\AuctionDeletionValidatorCreateTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Auction\SaleGroup\SaleGroupManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Messenger\AdminMessengerCreateTrait;

class AuctionListFormAuctionDeleter extends CustomizableClass
{
    use AdminMessengerCreateTrait;
    use AuctionDeletionValidatorCreateTrait;
    use AuctionDeleterCreateTrait;
    use AuctionLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use SaleGroupManagerAwareTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function delete(
        int $auctionId,
        int $editorUserId,
        bool $isReadOnlyDb = false
    ): void {
        $validationResult = $this->createAuctionDeletionValidator()->validate(
            $auctionId,
            $editorUserId,
            $isReadOnlyDb
        );

        if ($validationResult->hasError()) {
            $this->createAdminMessenger()->addError($validationResult->errorMessage());
            return;
        }

        $auction = $this->getAuctionLoader()->load($auctionId);
        $this->createAuctionDeleter()->delete($auction, $editorUserId);
        $this->getSaleGroupManager()->removeAuction($auction, $editorUserId);
        $auctionName = $this->getAuctionRenderer()->renderName($auction);
        $saleNo = $this->getAuctionRenderer()->renderSaleNo($auction);
        $this->createAdminMessenger()->addSuccess('Auction [' . $saleNo . ' - ' . $auctionName . '] has been deleted.');
    }
}
