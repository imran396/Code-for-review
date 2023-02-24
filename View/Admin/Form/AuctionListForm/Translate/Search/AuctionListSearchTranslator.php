<?php
/**
 *
 * SAM-12013: Implementation of admin side translation resource reorganization for v4.0 - Translation Manage Auctions page(list, info).
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 23, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionListForm\Translate\Search;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\AuctionListFormConstants;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class AuctionListSearchTranslator
 * @package Sam\View\Admin\Form\AuctionListForm\Translate\Search
 */
class AuctionListSearchTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    private const AUCTION_STATUS_TRANSLATION_KEYS = [
        AuctionListFormConstants::SAS_ALL => 'search_and_filter.auction_status.list_box.all',
        AuctionListFormConstants::SAS_ACTIVE => 'search_and_filter.auction_status.list_box.active',
        AuctionListFormConstants::SAS_CLOSED => 'search_and_filter.auction_status.list_box.closed',
        AuctionListFormConstants::SAS_ARCHIVED => 'search_and_filter.auction_status.list_box.archived',
    ];
    private const AUCTION_TYPE_TRANSLATION_KEYS = [
        Constants\Auction::TIMED => 'search_and_filter.auction_type.list_box.timed_online_auction',
        Constants\Auction::LIVE => 'search_and_filter.auction_type.list_box.live_auction',
        Constants\Auction::HYBRID => 'search_and_filter.auction_type.list_box.hybrid_auction',
    ];
    private const AUCTION_PUBLISHED_TRANSLATION_KEYS = [
        AuctionListFormConstants::PF_ALL => 'search_and_filter.published.list_box.all',
        AuctionListFormConstants::PF_PUBLISHED => 'search_and_filter.published.list_box.published_only',
        AuctionListFormConstants::PF_UNPUBLISHED => 'search_and_filter.published.list_box.un_published_only',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function translateStatus(string $mappingKey, string $language): string
    {
        $key = self::AUCTION_STATUS_TRANSLATION_KEYS[$mappingKey] ?? null;
        return $this->trans($key, $language);
    }

    public function translateType(string $mappingKey, string $language): string
    {
        $key = self::AUCTION_TYPE_TRANSLATION_KEYS[$mappingKey] ?? null;
        return $this->trans($key, $language);
    }

    public function translatePublished(string $mappingKey, string $language): string
    {
        $key = self::AUCTION_PUBLISHED_TRANSLATION_KEYS[$mappingKey] ?? null;
        return $this->trans($key, $language);
    }

    protected function trans(?string $key, string $language): string
    {
        $translation = '';
        if ($key) {
            $translation = $this->getAdminTranslator()->trans($key, [], 'admin_auction_list', $language);
        }
        return $translation;
    }
}
