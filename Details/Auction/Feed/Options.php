<?php
/**
 * Store and prepare options for auction details data provider in general
 * But values from Options could be used in other parts of code, eg. Options->languageId
 * See available properties in parent.
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Mar 2, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Feed;

use Sam\Core\Constants;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * @property int|null $accountId - filtering by account id
 * @property string|null $accountName - filtering by account name
 * @property int|null $auctionId - filtering by auction id
 * @property int|null $itemsPerPage - records per page
 * @property int|null $languageId - for translations
 * @property string|null $order - ordering option
 * @property int|null $page - current page number, starts from 0
 * @property string|null $saleNo - filtering by sale#
 * @property array|null $status - filtering by regular status
 * @property string[]|null $type - filtering by auction type
 * @property int|null $userId
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
        $this->itemsPerPage = null;
        $this->languageId = null;
        $this->page = null;
        $this->order = null;
        $this->saleNo = null;
        $this->status = null;
        $this->type = null;
        $this->userId = null;
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

        $this->auctionId = $this->getParamFetcherForGet()->getIntPositive('id');

        $this->languageId = $this->getParamFetcherForGet()->getIntPositive('language')
            ?: (int)$this->getSettingsManager()->get(Constants\Setting::VIEW_LANGUAGE, $this->getSystemAccountId());

        $this->order = $this->getParamFetcherForGet()->getString('order') ?: 'status';

        $this->page = $this->getParamFetcherForGet()->getPageNumber();

        $this->saleNo = $this->getParamFetcherForGet()->getString('number');

        $statusParams = $this->getParamFetcherForGet()
            ->getArrayOfKnownSet('status', Constants\Auction::$generalStatusParams);
        if ($statusParams) {
            // transform 'in-progress,upcoming,closed' to '1,2,3'
            $statusParams = array_intersect(Constants\Auction::$generalStatusParams, $statusParams);
            $this->status = array_keys($statusParams);
        }

        $typeParams = $this->getParamFetcherForGet()
            ->getArrayOfKnownSet('type', Constants\Auction::$auctionTypeParams);
        if ($typeParams) {
            $typeParams = array_intersect(Constants\Auction::$auctionTypeParams, $typeParams);
            $this->type = array_keys($typeParams);  // transform to T,L,H
        }

        return $this;
    }
}
