<?php
/**
 * Clone lots from sourceAuction to targetAuction
 *
 * SAM-5668: Extract multiple lot cloning logic from controller action
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 15, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Save;

use Auction;
use Exception;
use LotItem;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\AdminLotEditUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Order\Reorder\AuctionLotReordererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class MultipleLotCloner
 * @package Sam\Lot\Save
 */
class MultipleLotCloner extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionLotReordererAwareTrait;
    use AuctionRendererAwareTrait;
    use DbConnectionTrait;
    use LotClonerCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use UrlBuilderAwareTrait;

    public const ERR_CLONING_LOTS = 1;
    public const ERR_SOURCE_AUCTION_UNAVAILABLE = 2;
    public const ERR_TARGET_AUCTION_UNAVAILABLE = 3;
    public const OK_LOTS_CLONED = 4;

    /**
     * @var string[]
     */
    protected array $clonedFields = [];
    /**
     * @var int[]
     */
    protected array $lotItemIds = [];
    /**
     * @var Auction|null
     */
    protected ?Auction $sourceAuction = null;
    /**
     * @var int|null
     */
    protected ?int $sourceAuctionId = null;
    /**
     * @var Auction|null
     */
    protected ?Auction $targetAuction = null;
    /**
     * @var int|null
     */
    protected ?int $targetAuctionId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Clone lots from sourceAuction to targetAuction by lotItemIds
     * @param int $editorUserId
     */
    public function clone(int $editorUserId): void
    {
        $db = $this->getDb();
        $db->transactionBegin();

        $lotEditLinks = [];
        foreach ($this->lotItemIds as $lotItemId) {
            $lotItem = $this->getLotItemLoader()->load($lotItemId, true);
            if (!$lotItem) {
                log_error("Available lot item not found for cloning" . composeSuffix(['li' => $lotItemId]));
                continue;
            }

            try {
                $targetLotItem = $this->createLotCloner()
                    ->setAccountId($this->sourceAuction->AccountId)
                    ->setFields($this->clonedFields)
                    ->setSourceAuction($this->sourceAuction)
                    ->setSourceLotItem($lotItem)
                    ->setTargetAuction($this->targetAuction)
                    ->cloneLot($editorUserId);
                $lotEditLinks[] = $this->createLotEditLink($targetLotItem);
            } catch (Exception $e) {
                $this->getResultStatusCollector()->addError(self::ERR_CLONING_LOTS, $this->createCloningErrorMessage($lotItem, $e->getMessage()));
                $db->transactionRollback();
                return;
            }
        }
        $db->transactionCommit();

        $this->getAuctionLotReorderer()->reorder($this->targetAuction, $editorUserId);

        $this->getResultStatusCollector()->addSuccess(self::OK_LOTS_CLONED, $this->createSuccessLink($lotEditLinks));
    }

    /**
     * Set clone fields, lot, auctionLot, customFields fields
     * @param array $clonedFields
     * @return static
     */
    public function cloneFields(array $clonedFields): static
    {
        $clonedLotFields = array_flip(Constants\LotCloner::$fieldNames);

        $fields = [];
        foreach ($clonedFields as $clonedField) {
            $fields[] = $clonedLotFields[$clonedField] ?? $clonedField;
        }
        $this->clonedFields = $fields;
        return $this;
    }

    /**
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @return string[]
     */
    public function successMessage(): array
    {
        return $this->getResultStatusCollector()->getSuccessMessages();
    }

    /**
     * Initialize result status collector with error codes and messages
     */
    public function initResultStatusCollector(): void
    {
        $this->getResultStatusCollector()->construct(
            [
                self::ERR_CLONING_LOTS => 'Error cloning lot',
                self::ERR_SOURCE_AUCTION_UNAVAILABLE => 'Available source auction not found',
                self::ERR_TARGET_AUCTION_UNAVAILABLE => 'Available destination auction not found',
            ],
            [
                self::OK_LOTS_CLONED => 'Lots are cloned',
            ]
        );
    }

    /**
     * Set cloning lot ids
     * @param int[] $lotItemIds
     * @return static
     */
    public function setLotItemIds(array $lotItemIds): static
    {
        $this->lotItemIds = $lotItemIds;
        return $this;
    }

    /**
     * Set source auction id
     * @param int|null $auctionId
     * @return static
     */
    public function setSourceAuctionId(?int $auctionId): static
    {
        $this->sourceAuctionId = Cast::toInt($auctionId, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * Set target auction id
     * @param int|null $auctionId
     * @return static
     */
    public function setTargetAuctionId(?int $auctionId): static
    {
        $this->targetAuctionId = Cast::toInt($auctionId, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $this->initResultStatusCollector();

        if (
            !$this->sourceAuctionId
            || !$this->getSourceAuction()
        ) {
            $this->getResultStatusCollector()->addError(self::ERR_SOURCE_AUCTION_UNAVAILABLE);
            return false;
        }

        if (
            !$this->targetAuctionId
            || !$this->getTargetAuction()
        ) {
            $this->getResultStatusCollector()->addError(self::ERR_TARGET_AUCTION_UNAVAILABLE);
            return false;
        }

        return true;
    }

    /**
     * Add cloning lot info to error
     * @param LotItem $lotItem
     * @param string $error
     * @return string
     */
    protected function createCloningErrorMessage(LotItem $lotItem, string $error): string
    {
        $auctionLot = $this->getAuctionLotLoader()->load($lotItem->Id, $this->sourceAuction->Id, true);
        return 'Error cloning lot' . composeSuffix(
                [
                    'lot#' => $this->getLotRenderer()->renderLotNo($auctionLot),
                    'item#' => $this->getLotRenderer()->renderItemNo($lotItem),
                    'message' => $error
                ]
            );
    }

    /**
     * Create lot edit link from targetLotItem
     * @param LotItem $targetLotItem
     * @return string
     */
    protected function createLotEditLink(LotItem $targetLotItem): string
    {
        $targetAuctionLot = $this->getAuctionLotLoader()->load($targetLotItem->Id, $this->targetAuction->Id);

        $url = $this->getUrlBuilder()->build(
            AdminLotEditUrlConfig::new()->forWeb($targetLotItem->Id, $this->targetAuction->Id)
        );
        $name = $this->getLotRenderer()->renderItemNo($targetLotItem) . ' / ' . $this->getLotRenderer()->renderLotNo($targetAuctionLot);

        return HtmlRenderer::new()->link($url, $name, ['target' => '_blank']);
    }

    /**
     * Merge lotEditLinks array to success report
     * @param array $lotEditLinks
     * @return string
     */
    protected function createSuccessLink(array $lotEditLinks): string
    {
        $saleNo = $this->getAuctionRenderer()->renderSaleNo($this->targetAuction);
        $auctionName = $this->getAuctionRenderer()->renderName($this->targetAuction);

        $url = $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_LOT_LIST, $this->targetAuction->Id)
        );
        $name = $auctionName . ' (sale# ' . $saleNo . ')';

        return HtmlRenderer::new()->link($url, $name, ['target' => '_blank'])
            . ', item# / lot#: ' . implode(', ', $lotEditLinks);
    }

    /**
     * @return Auction|null
     */
    protected function getSourceAuction(): ?Auction
    {
        if ($this->sourceAuction === null) {
            $this->sourceAuction = $this->getAuctionLoader()->load($this->sourceAuctionId);
        }
        return $this->sourceAuction;
    }

    /**
     * @return Auction|null
     */
    protected function getTargetAuction(): ?Auction
    {
        if ($this->targetAuction === null) {
            $this->targetAuction = $this->getAuctionLoader()->load($this->targetAuctionId);
        }
        return $this->targetAuction;
    }
}
