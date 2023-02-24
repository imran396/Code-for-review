<?php
/**
 * "Other lots"  Carousel . All actions of the widget.
 *
 * SAM-3506: Other lots on responsive lot detail page needs to be refactored
 * @see https://bidpath.atlassian.net/browse/SAM-3506
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 21, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\OtherLots;

use Auction;
use AuctionLotItem;
use DomainException;
use LotItem;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\AuctionLot\OtherLots\Carousel\Validator;
use Sam\AuctionLot\OtherLots\ShowStrategy\ShowStrategyInterface;
use Sam\AuctionLot\OtherLots\Storage\DataManagerInterface;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;

/**
 * Class Carousel
 * @package Sam\AuctionLot\OtherLots
 */
class Carousel extends CustomizableClass
{
    use LotRendererAwareTrait;
    use UrlParserAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * @var ShowStrategyInterface|null
     */
    private ?ShowStrategyInterface $showStrategy = null;

    /**
     * @var DataManagerInterface|null
     */
    private ?DataManagerInterface $dataManager = null;

    /**
     * @var ImageUrlCreator|null
     */
    private ?ImageUrlCreator $imageUrlCreator = null;

    /**
     * @var LotTitleCreator|null
     */
    private ?LotTitleCreator $lotTitleCreator = null;

    /**
     * @var Validator|null
     */
    private ?Validator $validator = null;

    /**
     * @var array
     */
    private array $getParams = [];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItem $lotItem
     * @param Auction $auction
     * @param int $action
     * @param int $page
     * @return array
     */
    public function getLotRows(LotItem $lotItem, Auction $auction, int $action, int $page = 0): array
    {
        $result = [];
        if (!$this->getValidator()->isAllowToCreate($auction, $lotItem)) {
            return [];
        }

        $showStrategy = $this->getShowStrategy();
        $auctionLots = $showStrategy->getAuctionLots($action, $page);
        $result = $this->mapToResultArray($auction, $auctionLots, $result);
        return $result;
    }

    /**
     * @param int $action
     * @param int $currentPage
     * @return int
     */
    public function getNextPageFromAction(int $action, int $currentPage): int
    {
        $showStrategy = $this->getShowStrategy();
        $nextPage = $showStrategy->getNextPageFromAction($action, $currentPage);
        return $nextPage;
    }

    /**
     * @param bool $refresh
     * @return int
     */
    public function countAllLots(bool $refresh = false): int
    {
        $showStrategy = $this->getShowStrategy();
        $count = $showStrategy->countAllAuctionLots($refresh);
        return $count;
    }

    /**
     * @param Auction $auction
     * @param AuctionLotItem[] $auctionLots
     * @param array $rows
     * @return array
     */
    protected function mapToResultArray(Auction $auction, array $auctionLots, array $rows): array
    {
        foreach ($auctionLots as $auctionLot) {
            $temp = [];
            $lotItemId = $auctionLot->LotItemId;
            $temp['lot_title'] = $this->getLotTitleCreator()->getTitle($auctionLot);
            $temp['image_url'] = $this->getImageUrlCreator()->getDefaultImageUrl($lotItemId);
            $temp['lot_number'] = $this->getLotRenderer()->renderLotNo($auctionLot);
            $temp['lot_url'] = $this->getUrl(
                $this->getGetParams(),
                $lotItemId,
                $auction->Id,
                $auctionLot->AccountId
            );
            $rows[] = $temp;
        }
        return $rows;
    }

    /**
     * @param array $getArray
     * @param int $lotItemId
     * @param int $auctionId
     * @param int $accountId
     * @return string
     */
    protected function getUrl(array $getArray, int $lotItemId, int $auctionId, int $accountId): string
    {
        $lotUrl = $this->getUrlBuilder()->build(
            ResponsiveLotDetailsUrlConfig::new()->forWeb(
                $lotItemId,
                $auctionId,
                null,
                [UrlConfigConstants::OP_ACCOUNT_ID => $accountId]
            )
        );
        if (isset($getArray[Constants\UrlParam::GA])) {
            unset($getArray[Constants\UrlParam::GA]);
        }
        $url = $this->getUrlParser()->replaceParams($lotUrl, $getArray);
        return $url;
    }

    //<editor-fold desc="Getters\Setters">

    /**
     * @return array
     */
    public function getGetParams(): array
    {
        return $this->getParams;
    }

    /**
     * @param array $params
     * @return static
     */
    public function setGetParams(array $params): static
    {
        $this->getParams = $params;
        return $this;
    }

    /**
     * @return ImageUrlCreator
     */
    public function getImageUrlCreator(): ImageUrlCreator
    {
        return $this->imageUrlCreator;
    }

    /**
     * @param ImageUrlCreator $imageUrlCreator
     * @return static
     */
    public function setImageUrlCreator(ImageUrlCreator $imageUrlCreator): static
    {
        $this->imageUrlCreator = $imageUrlCreator;
        return $this;
    }

    /**
     * @return LotTitleCreator
     */
    public function getLotTitleCreator(): LotTitleCreator
    {
        return $this->lotTitleCreator;
    }

    /**
     * @param LotTitleCreator $lotTitleCreator
     * @return static
     */
    public function setLotTitleCreator(LotTitleCreator $lotTitleCreator): static
    {
        $this->lotTitleCreator = $lotTitleCreator;
        return $this;
    }

    /**
     * @return Carousel\Validator
     */
    public function getValidator(): Carousel\Validator
    {
        if ($this->validator === null) {
            throw new DomainException("Please, set a validator. It is required. ");
        }
        return $this->validator;
    }

    /**
     * @param Validator $validator
     * @return static
     */
    public function setValidator(Carousel\Validator $validator): static
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * @return ShowStrategy\ShowStrategyInterface
     */
    public function getShowStrategy(): ShowStrategy\ShowStrategyInterface
    {
        return $this->showStrategy;
    }

    /**
     * @param ShowStrategyInterface $showStrategy
     * @return static
     */
    public function setShowStrategy(ShowStrategy\ShowStrategyInterface $showStrategy): static
    {
        $this->showStrategy = $showStrategy;
        return $this;
    }

    /**
     * @return Storage\DataManagerInterface
     */
    public function getDataManager(): Storage\DataManagerInterface
    {
        return $this->dataManager;
    }

    /**
     * @param DataManagerInterface $dataManager
     * @return static
     */
    public function setDataManager(Storage\DataManagerInterface $dataManager): static
    {
        $this->dataManager = $dataManager;
        return $this;
    }
    //</editor-fold>

}
