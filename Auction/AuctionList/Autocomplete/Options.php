<?php
/**
 * Define list filtering conditions and rendering options.
 * Options class transfers all available options and supply some useful logic.
 *
 * SAM-4055: Auction list auto-complete
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy, Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           23 Mar, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\AuctionList\Autocomplete;

use InvalidArgumentException;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class Options
 */
class Options extends CustomizableClass
{
    use ParamFetcherForGetAwareTrait;
    use SystemAccountAwareTrait;

    private const RENDERING_TEMPLATE_DEF = '{date} - {sale_no} - {name}';

    /**
     * Filter by account id(s)
     * @var int[]
     */
    protected array $filterAccountIds = [];
    /**
     * @var string
     */
    protected string $accountListControlId = '';
    /**
     * Filter by auction id
     * @var int[]
     */
    protected array $filterAuctionIds = [];
    /**
     * @var string
     */
    protected string $auctionName = '';
    /**
     * Filter by auction status id(s)
     * @var int[]
     */
    protected array $filterAuctionStatusIds = [];
    /**
     * @var string
     */
    protected string $controlId = '';
    /**
     * Currency
     * @var int|null
     */
    protected ?int $filterCurrencyId = null;
    /**
     * @var string
     */
    protected string $currencyListControlId = '';
    /**
     * @var bool
     */
    protected bool $isReadOnlyDb = false;
    /**
     * Language id for translation
     * @var int|null
     */
    protected ?int $languageId = null;
    /**
     * @var int|null
     */
    protected ?int $limit = null; // 20
    /**
     * Default option/options
     * @var int[]
     */
    protected array $prioritizedAuctionIds = [];
    /**
     * Limit length of auction name
     * @var int|null
     */
    protected ?int $nameLengthLimit = null;
    /**
     * Output template for auction row
     * @var string
     */
    protected string $renderingTemplate = '';
    /**
     * Filter by total lot count is greater than value
     * @var int|null
     */
    protected ?int $totalLotsGreaterThan = null;
    /**
     * Filter by the user that is the bidder in auctions
     * @var int|null
     */
    protected ?int $filterBidderUserId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->filterAuctionStatusIds = Constants\Auction::$availableAuctionStatuses;
        $this->isReadOnlyDb = false;
        $this->limit = 20;
        $this->renderingTemplate = self::RENDERING_TEMPLATE_DEF;
        return $this;
    }

    /**
     * @return array
     */
    public function getJsImportValues(): array
    {
        if (!$this->controlId) {
            throw new InvalidArgumentException('Unknown ControlId of auction auto-complete');
        }
        $values = [
            'account_id' => $this->getFilterAccountIds(),
            'account_list_control_id' => $this->getAccountListControlId(),
            /** YV, 2020-12 (SAM-5795):
             * i think we need to accept only int for $this->auctionId (Instead of array of int Positive)
             * As you can see, we render only single first value from  $this->auctionId[] here. Why do we need an array, if we dont use it?
             * @see \Sam\Auction\AuctionList\Autocomplete\Options::initByGet for $paramFetcherForGet->getArrayOfIntPositive("auction_id");
             * These changes will simplify logic here @see \ManageSearchController::getNoneOptionForAutocompleteOutput too.
             */
            'auction_id' => $this->filterAuctionIds[0] ?? null,
            'auction_status' => $this->getFilterAuctionStatusIds(),
            'auction_status_id' => $this->getFilterAuctionStatusIds(),
            'currency_list_control_id' => $this->getCurrencyListControlId(),
            'is_read_only_db' => $this->isReadOnlyDb(),
            'language_id' => $this->getLanguageId(),
            'limit' => $this->getLimit(),
            'prioritized_auction_id' => $this->getPrioritizedAuctionIds(),
            'name_length_limit' => $this->getNameLengthLimit(),
            'rendering_template' => $this->getRenderingTemplate(),
            'total_lots' => $this->getTotalLotsGreaterThan(),
        ];

        return [
            $this->controlId . '_options' => $values,
        ];
    }

    /**
     * Init option values by GET params
     * @return static
     */
    public function initByGet(): static
    {
        $paramFetcherForGet = $this->getParamFetcherForGet();
        $key = Constants\UrlParam::RENDERING_TEMPLATE;
        if ($paramFetcherForGet->has($key)) {
            $renderingTemplate = $paramFetcherForGet->getString($key);
            $this->setRenderingTemplate($renderingTemplate);
        }
        $key = Constants\UrlParam::SEARCH_KEY;
        if ($paramFetcherForGet->has($key)) {
            $searchKey = $paramFetcherForGet->getString($key);
            $this->likeAuctionName($searchKey);
        }
        $key = Constants\UrlParam::LANGUAGE_ID;
        if ($paramFetcherForGet->has($key)) {
            $languageId = $paramFetcherForGet->getIntPositive($key);
            $this->setLanguageId($languageId);
        }
        $key = Constants\UrlParam::ACCOUNT_ID;
        if ($paramFetcherForGet->has($key)) {
            $accountId = $paramFetcherForGet->getArrayOfIntPositive($key);
            $this->filterAccountIds($accountId);
        }
        $key = Constants\UrlParam::AUCTION_ID;
        if ($paramFetcherForGet->has($key)) {
            $auctionId = $paramFetcherForGet->getInt($key);
            if ($auctionId === Constants\AuctionListAutocomplete::UNASSIGNED_AUCTION_ID) {
                $this->filterAuctionId($auctionId);
            } else {
                $filterAuctionIds = $paramFetcherForGet->getArrayOfIntPositive($key);
                $this->filterAuctionIds($filterAuctionIds);
            }
        }
        $key = Constants\UrlParam::AUCTION_STATUS;
        if ($paramFetcherForGet->has($key)) {
            $auctionStatusIds = $paramFetcherForGet
                ->getArrayOfKnownSet($key, Constants\Auction::$notDeletedAuctionStatuses);
            $this->filterAuctionStatusIds($auctionStatusIds);
        }
        $key = Constants\UrlParam::TOTAL_LOTS;
        if ($paramFetcherForGet->has($key)) {
            $totalLots = $paramFetcherForGet->getIntPositiveOrZero($key) ?? 0;
            $this->filterTotalLotsGreaterThan($totalLots);
        }
        $key = Constants\UrlParam::NAME_LENGTH_LIMIT;
        if ($paramFetcherForGet->has($key)) {
            $nameLengthLimit = $paramFetcherForGet->getIntPositive($key) ?? 0;
            $this->setNameLengthLimit($nameLengthLimit);
        }
        $key = Constants\UrlParam::IS_READ_ONLY_DB;
        if ($paramFetcherForGet->has($key)) {
            $isReadOnlyDb = $paramFetcherForGet->getBool($key) ?? false;
            $this->enableReadOnlyDb($isReadOnlyDb);
        }
        $key = Constants\UrlParam::CURRENCY;
        if ($paramFetcherForGet->has($key)) {
            $currencyId = $paramFetcherForGet->getIntPositive($key);
            $this->filterCurrencyId($currencyId);
        }
        $key = Constants\UrlParam::LIMIT;
        if ($paramFetcherForGet->has($key)) {
            $limit = $paramFetcherForGet->getIntPositive($key);
            $this->setLimit($limit);
        }
        $key = Constants\UrlParam::PRIORITIZED_AUCTION_ID;
        if ($paramFetcherForGet->has($key)) {
            $auctionIds = $paramFetcherForGet->getArrayOfInt($key);
            if (!in_array(Constants\AuctionListAutocomplete::UNASSIGNED_AUCTION_ID, $auctionIds, true)) {
                $auctionIds = $paramFetcherForGet->getArrayOfIntPositive($key);
            }
            $this->setPrioritizedAuctionIds($auctionIds);
        }
        $key = Constants\UrlParam::CONTEXT_WINNING_USER_ID;
        if ($paramFetcherForGet->has($key)) {
            $bidderUserId = $paramFetcherForGet->getIntPositive($key);
            $this->filterBidderUserId($bidderUserId);
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function isReadOnlyDb(): bool
    {
        return $this->isReadOnlyDb;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return static
     */
    public function enableReadOnlyDb(bool $isReadOnlyDb): static
    {
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getFilterAccountIds(): array
    {
        return $this->filterAccountIds;
    }

    /**
     * @param int|null $accountId null to drop filtering by account
     * @return static
     */
    public function filterAccountId(?int $accountId): static
    {
        $this->filterAccountIds = $accountId ? [$accountId] : [];
        return $this;
    }

    /**
     * @param int[] $accountIds
     * @return static
     */
    public function filterAccountIds(array $accountIds): static
    {
        $this->filterAccountIds = $accountIds;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountListControlId(): string
    {
        return $this->accountListControlId;
    }

    /**
     * @param string $accountListControlId
     * @return static
     */
    public function setAccountListControlId(string $accountListControlId): static
    {
        $this->accountListControlId = $accountListControlId;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getFilterAuctionIds(): array
    {
        return $this->filterAuctionIds;
    }

    /**
     * @param array $auctionIds
     * @return $this
     */
    public function filterAuctionIds(array $auctionIds): static
    {
        $this->filterAuctionIds = $auctionIds;
        return $this;
    }

    /**
     * @param int|null $auctionId null to drop filtering
     * @return static
     */
    public function filterAuctionId(?int $auctionId): static
    {
        $this->filterAuctionIds = $auctionId ? [$auctionId] : [];
        return $this;
    }

    /**
     * @return int[]
     */
    public function getFilterAuctionStatusIds(): array
    {
        return $this->filterAuctionStatusIds;
    }

    /**
     * @param int[] $filterAuctionStatusId [] to drop filtering
     * @return static
     */
    public function filterAuctionStatusIds(array $filterAuctionStatusId): static
    {
        $this->filterAuctionStatusIds = $filterAuctionStatusId;
        return $this;
    }

    public function getFilterBidderUserId(): ?int
    {
        return $this->filterBidderUserId;
    }

    public function filterBidderUserId(int $bidderUserId): static
    {
        $this->filterBidderUserId = $bidderUserId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTotalLotsGreaterThan(): ?int
    {
        return $this->totalLotsGreaterThan;
    }

    /**
     * @param int $totalLotsGreaterThan
     * @return static
     */
    public function filterTotalLotsGreaterThan(int $totalLotsGreaterThan): static
    {
        $this->totalLotsGreaterThan = $totalLotsGreaterThan;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getNameLengthLimit(): ?int
    {
        return $this->nameLengthLimit;
    }

    /**
     * @param int $nameLengthLimit
     * @return static
     */
    public function setNameLengthLimit(int $nameLengthLimit): static
    {
        $this->nameLengthLimit = $nameLengthLimit;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLanguageId(): ?int
    {
        return $this->languageId;
    }

    /**
     * @param int|null $languageId null to drop
     * @return static
     */
    public function setLanguageId(?int $languageId): static
    {
        $this->languageId = $languageId;
        return $this;
    }

    /**
     * @return string
     */
    public function getRenderingTemplate(): string
    {
        return $this->renderingTemplate;
    }

    /**
     * @param string $renderingTemplate
     * @return static
     */
    public function setRenderingTemplate(string $renderingTemplate): static
    {
        $this->renderingTemplate = $renderingTemplate;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     * @return static
     */
    public function setLimit(?int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuctionName(): string
    {
        return $this->auctionName;
    }

    /**
     * @param string $auctionName
     * @return static
     */
    public function likeAuctionName(string $auctionName): static
    {
        $this->auctionName = $auctionName;
        return $this;
    }

    /**
     * @return int|null null - no filtering by currency id
     */
    public function getFilterCurrencyId(): ?int
    {
        return $this->filterCurrencyId;
    }

    /**
     * @param int|null $currencyId null to drop filtering
     * @return static
     */
    public function filterCurrencyId(?int $currencyId): static
    {
        $this->filterCurrencyId = $currencyId;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyListControlId(): string
    {
        return $this->currencyListControlId;
    }

    /**
     * @param string $currencyListControlId
     * @return static
     */
    public function setCurrencyListControlId(string $currencyListControlId): static
    {
        $this->currencyListControlId = $currencyListControlId;
        return $this;
    }

    /**
     * @param string $controlId
     * @return static
     */
    public function setControlId(string $controlId): static
    {
        $this->controlId = $controlId;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getPrioritizedAuctionIds(): array
    {
        return $this->prioritizedAuctionIds;
    }

    /**
     * @param int[] $auctionIds
     * @return static
     */
    public function setPrioritizedAuctionIds(array $auctionIds): static
    {
        $this->prioritizedAuctionIds = $auctionIds;
        return $this;
    }
}
