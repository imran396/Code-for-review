<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 15, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\Cache;

use Sam\Core\Service\CustomizableClass;
use Sam\Bidder\BidderInfo\BidderInfoRendererAwareTrait;

/**
 * Class AuctionBidderInfo
 */
class BidderInfoCacher extends CustomizableClass
{
    use BidderInfoRendererAwareTrait;

    private array $infos = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detect auction bidder info and cache
     * @param int $auctionId
     * @param int $targetBidderUserId
     * @param int|null $editorUserId
     * @param bool $hasEditorUserAdminRole
     * @param bool $hasEditorUserPrivilegeForCrossAccount
     * @param int|null $editorUserAccountId
     * @param int|null $lotItemAccountId
     * @param int $systemAccountId
     * @param int $languageId
     * @return string
     */
    public function getInfo(
        int $auctionId,
        int $targetBidderUserId,
        ?int $editorUserId,
        bool $hasEditorUserAdminRole,
        bool $hasEditorUserPrivilegeForCrossAccount,
        ?int $editorUserAccountId,
        ?int $lotItemAccountId,
        int $systemAccountId,
        int $languageId
    ): string {
        if (!isset($this->infos[$auctionId][$targetBidderUserId])) {
            $bidderInfo = $this->getBidderInfoRenderer()
                ->enableEditorUserAdminRole($hasEditorUserAdminRole)
                ->enableEditorUserPrivilegeForCrossAccount($hasEditorUserPrivilegeForCrossAccount)
                ->enableMaskUsernameIfEmail(true)
                ->enableReadOnlyDb(true)
                ->enableTranslation(true)
                ->setAuctionId($auctionId)
                ->setEditorUserAccountId($editorUserAccountId)
                ->setEditorUserId($editorUserId)
                ->setLanguageId($languageId)
                ->setLotItemAccountId($lotItemAccountId)
                ->setSystemAccountId($systemAccountId)
                ->setUserId($targetBidderUserId)
                ->render();
            $this->infos[$auctionId][$targetBidderUserId] = ' (' . $bidderInfo . ')';
        }
        return $this->infos[$auctionId][$targetBidderUserId];
    }
}
