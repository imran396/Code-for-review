<?php
/**
 * Store and prepare options for lot details data provider in general
 * But values from Options could be used in other parts of code, eg. Options->languageId
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 9, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\Feed;

use Sam\Core\Constants;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * @property int|null $accountId
 * @property string|null $accountName
 * @property int|null $auctionId
 * @property array|null $auctionGeneralStatus
 * @property int[]|null $categoryId
 * @property string|null $featured - filtering by ali.sample_lot
 * @property int|null $lotItemId - filtering by li.id
 * @property int|null $itemNum - filtering by li.item_num
 * @property string|null $itemNumExt - filtering by li.item_num_ext
 * @property int|null $itemsPerPage - records per page
 * @property bool|null $isIncludeInReports
 * @property int|null $languageId - for translations
 * @property string|null $order
 * @property int|null $page - current page number, starts from 0
 * @property string[]|null $type - filtering by auction type
 * @property int|null $userId - filtering by user id
 */
class Options extends \Sam\Details\Core\Options
{
    use SettingsManagerAwareTrait;
    use ParamFetcherForGetAwareTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $systemAccountId): static
    {
        $this->setSystemAccountId($systemAccountId);
        $this->accountId = null;
        $this->accountName = null;
        $this->auctionId = null;
        $this->auctionGeneralStatus = null;
        $this->categoryId = null;
        $this->featured = null;
        $this->lotItemId = null;
        $this->itemNum = null;
        $this->itemNumExt = null;
        $this->languageId = null;
        $this->order = null;
        $this->page = null;
        $this->type = null;
        return $this;
    }

    /**
     * Produce option values based on GET request params
     */
    public function initByRequest(): static
    {
        if ($this->cfg()->get('core->portal->mainAccountId') === $this->getSystemAccountId()) {
            $this->accountName = $this->getParamFetcherForGet()->getString('account');
        }

        $this->auctionId = $this->getParamFetcherForGet()->getIntPositive('auction_id');

        $statusParams = $this->getParamFetcherForGet()
            ->getArrayOfKnownSet('auction_status', Constants\Auction::$generalStatusParams);
        if ($statusParams) {
            // transform 'in-progress,upcoming,closed' to '1,2,3'
            $statusParams = array_intersect(Constants\Auction::$generalStatusParams, $statusParams);
            $this->auctionGeneralStatus = array_keys($statusParams);
        }

        $this->categoryId = $this->getParamFetcherForGet()->getArrayOfIntPositive('category');

        $this->featured = $this->getParamFetcherForGet()->getForKnownSet('featured', [1]);

        $this->itemNum = $this->getParamFetcherForGet()->getIntPositive('item_num');

        $this->itemNumExt = $this->getParamFetcherForGet()->getString('item_num_ext');

        $this->languageId = $this->getParamFetcherForGet()->getIntPositive('language')
            ?: (int)$this->getSettingsManager()->get(Constants\Setting::VIEW_LANGUAGE, $this->getSystemAccountId());

        $this->lotItemId = $this->getParamFetcherForGet()->getIntPositive('id');

        $typeParams = $this->getParamFetcherForGet()
            ->getArrayOfKnownSet('type', Constants\Auction::$auctionTypeParams);
        if ($typeParams) {
            $typeParams = array_intersect(Constants\Auction::$auctionTypeParams, $typeParams);
            $this->type = array_keys($typeParams);  // transform to T,L,H
        }

        $defaultOrder = $this->auctionId ? 'order_num' : 'timeleft';
        $this->order = $this->getParamFetcherForGet()->getString('order') ?: $defaultOrder;

        $this->page = $this->getParamFetcherForGet()->getPageNumber();
        return $this;
    }
}
