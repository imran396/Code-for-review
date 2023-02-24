<?php
/**
 * Helping methods for meta description, keywords, title rendering.
 * Some frontend pages have (description, keywords, title) setting_seo fields.
 * Some of fields may contain tags, some pages can have additional logic.
 *
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 6, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\ViewHelper;

use LotCategory;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants\ResponsiveRoute;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Core\Web\ControllerAction\ControllerActionCollection;
use Sam\Details\Auction\Web\SeoMeta\Builder as AuctionSeoBuilder;
use Sam\Details\Lot\Web\SeoMeta\Build\LotWebSeoMetaBuilder;
use Sam\Details\Lot\Web\SeoMeta\Build\LotWebSeoMetaBuilderCreateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Featured\FeaturedLotLoaderAwareTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class MetaData
 * @package Sam\View\Responsive\ViewHelper
 */
class MetaData extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use FeaturedLotLoaderAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotImageLoaderAwareTrait;
    use LotItemAwareTrait;
    use LotRendererAwareTrait;
    use LotWebSeoMetaBuilderCreateTrait;
    use ParamFetcherForRouteAwareTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;

    private const TYPE_DESCRIPTION = 'description';
    private const TYPE_KEYWORDS = 'keywords';
    private const TYPE_TITLE = 'title';

    /** @var string[] */
    protected array $auctionMetaFields = [
        Constants\Setting::AUCTION_PAGE_DESC,
        Constants\Setting::AUCTION_PAGE_KEYWORD,
        Constants\Setting::AUCTION_PAGE_TITLE
    ];
    /** @var string[] */
    protected array $lotItemMetaFields = [
        Constants\Setting::LOT_PAGE_DESC,
        Constants\Setting::LOT_PAGE_KEYWORD,
        Constants\Setting::LOT_PAGE_TITLE
    ];
    /** @var string */
    protected string $output = '';
    /** @var string */
    protected string $outputFacebook = '';

    // Hash table, page => setting_seo.fields
    protected array $metaData = [
        ResponsiveRoute::C_AUCTIONS => [
            ResponsiveRoute::AA_CATALOG => [
                self::TYPE_DESCRIPTION => Constants\Setting::AUCTION_PAGE_DESC,
                self::TYPE_KEYWORDS => Constants\Setting::AUCTION_PAGE_KEYWORD,
                self::TYPE_TITLE => Constants\Setting::AUCTION_PAGE_TITLE,
            ],
            ResponsiveRoute::AA_LIST => [
                self::TYPE_DESCRIPTION => Constants\Setting::AUCTION_LISTING_PAGE_DESC,
                self::TYPE_KEYWORDS => Constants\Setting::AUCTION_LISTING_PAGE_KEYWORD,
                self::TYPE_TITLE => Constants\Setting::AUCTION_LISTING_PAGE_TITLE,
            ],
            ResponsiveRoute::AA_INFO => [
                self::TYPE_DESCRIPTION => Constants\Setting::AUCTION_PAGE_DESC,
                self::TYPE_KEYWORDS => Constants\Setting::AUCTION_PAGE_KEYWORD,
                self::TYPE_TITLE => Constants\Setting::AUCTION_PAGE_TITLE,
            ],
            ResponsiveRoute::AA_LIVE_SALE => [
                self::TYPE_DESCRIPTION => Constants\Setting::AUCTION_PAGE_DESC,
                self::TYPE_KEYWORDS => Constants\Setting::AUCTION_PAGE_KEYWORD,
                self::TYPE_TITLE => Constants\Setting::AUCTION_PAGE_TITLE,
            ],
        ],
        ResponsiveRoute::C_INDEX => [
            ResponsiveRoute::AA_INDEX => [
                self::TYPE_DESCRIPTION => Constants\Setting::AUCTION_LISTING_PAGE_DESC,
                self::TYPE_KEYWORDS => Constants\Setting::AUCTION_LISTING_PAGE_KEYWORD,
                self::TYPE_TITLE => Constants\Setting::AUCTION_LISTING_PAGE_TITLE,
            ],
        ],
        ResponsiveRoute::C_LOGIN => [
            ResponsiveRoute::AL_INDEX => [
                self::TYPE_DESCRIPTION => Constants\Setting::LOGIN_DESC,
                self::TYPE_KEYWORDS => Constants\Setting::LOGIN_KEYWORD,
                self::TYPE_TITLE => Constants\Setting::LOGIN_TITLE,
            ],
        ],
        ResponsiveRoute::C_LOT_DETAILS => [
            ResponsiveRoute::ALO_INDEX => [
                self::TYPE_DESCRIPTION => Constants\Setting::LOT_PAGE_DESC,
                self::TYPE_KEYWORDS => Constants\Setting::LOT_PAGE_KEYWORD,
                self::TYPE_TITLE => Constants\Setting::LOT_PAGE_TITLE,
            ],
        ],
        ResponsiveRoute::C_SEARCH => [
            ResponsiveRoute::ASRCH_INDEX => [
                self::TYPE_DESCRIPTION => Constants\Setting::SEARCH_RESULTS_PAGE_DESC,
                self::TYPE_KEYWORDS => Constants\Setting::SEARCH_RESULTS_PAGE_KEYWORD,
                self::TYPE_TITLE => Constants\Setting::SEARCH_RESULTS_PAGE_TITLE,
            ],
        ],
        ResponsiveRoute::C_SIGNUP => [
            ResponsiveRoute::ASI_INDEX => [
                self::TYPE_DESCRIPTION => Constants\Setting::SIGNUP_DESC,
                self::TYPE_KEYWORDS => Constants\Setting::SIGNUP_KEYWORD,
                self::TYPE_TITLE => Constants\Setting::SIGNUP_TITLE,
            ],
        ],
    ];

    /**
     * @var array<string, string[]> setting_seo.auction_listing_page_title seo tags. Seo tag => [object name, object field]
     */
    protected array $seoTagObjectLink = [
        'auction_name' => ['auction', 'Name'],
        'auction_title' => ['auction', 'SeoMetaTitle'],
        'item_name' => ['lotItem', 'Name'],
        'item_title' => ['lotItem', 'SeoMetaTitle'],
    ];

    protected ?string $type = null;
    /** @var LotCategory[] */
    public ?array $lotCategories = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Render meta data
     * @param string $type ('description', 'keywords', 'title')
     * @return string
     */
    public function render(string $type): string
    {
        $this->output = '';
        $this->outputFacebook = '';
        $this->type = $type;

        $paramFetcherForRoute = $this->getParamFetcherForRoute();
        $auctionIdParamName = $this->detectAuctionIdParamName($paramFetcherForRoute->getControllerName(), $paramFetcherForRoute->getActionName());
        $auctionId = $paramFetcherForRoute->getIntPositiveOrZero($auctionIdParamName); // "0" for preview of unassigned lot
        $lotItemId = $paramFetcherForRoute->getIntPositive(Constants\UrlParam::R_LOT);
        $this->setAuctionId($auctionId);
        $this->setLotItemId($lotItemId);

        $caCollection = ControllerActionCollection::new()->construct($this->metaData);
        $has = $caCollection->has($paramFetcherForRoute->getControllerName(), $paramFetcherForRoute->getActionName());
        if ($has) {
            $templateColumns = $caCollection->get($paramFetcherForRoute->getControllerName(), $paramFetcherForRoute->getActionName());
            $templateColumn = $templateColumns[$type];
            $template = (string)$this->getSettingsManager()->getForSystem($templateColumn);
            if (in_array($templateColumn, $this->auctionMetaFields, true)) {
                if ($template !== '') {
                    $this->output = AuctionSeoBuilder::new()
                        ->setTemplateColumn($templateColumn)
                        ->render($this->getAuctionId(), $this->getSystemAccountId());
                    // Only for catalog lot page
                } elseif ($this->getLotItemId()) {
                    $this->renderLotItem();
                } else {
                    $this->renderAuction();
                }

                if ($templateColumn === Constants\Setting::AUCTION_PAGE_DESC) {
                    $this->renderFacebookMetaData();
                }
            } elseif (in_array($templateColumn, $this->lotItemMetaFields, true)) {
                $lotWebSeoMetaBuilder = $this->createLotWebSeoMetaBuilder()->construct(
                    $this->getLotItem()->AccountId,
                    $this->getSystemAccountId(),
                    $templateColumn,
                    $this->getLotItemId(),
                    $this->getAuctionId(),
                    [LotWebSeoMetaBuilder::OP_LOT_CATEGORIES => $this->getLotCategories()]
                );
                if ($templateColumn === Constants\Setting::LOT_PAGE_DESC) {
                    $this->renderFacebookMetaData();
                }
                $this->output = $lotWebSeoMetaBuilder->render();
            } else {
                $this->output = $template;
            }
        }
        $this->renderPagesWithoutAuctionParametersFields($paramFetcherForRoute->getControllerName(), $paramFetcherForRoute->getActionName());

        return ($type === self::TYPE_TITLE)
            ? HtmlRenderer::new()->title($this->output)
            : HtmlRenderer::new()->meta($this->output, ['name' => $type]) . $this->outputFacebook;
    }

    /**
     * @param string $controller
     * @param string $action
     */
    protected function renderPagesWithoutAuctionParametersFields(string $controller, string $action): void
    {
        if ($this->type === self::TYPE_TITLE) {
            if (
                $controller === ResponsiveRoute::C_AUCTIONS
                && $action === ResponsiveRoute::AA_BIDDING_HISTORY
            ) {
                $auctionLot = $this->getAuctionLotLoader()
                    ->load($this->getLotItemId(), $this->getAuctionId(), true);
                if ($auctionLot) {
                    $this->output .= sprintf(
                        $this->getTranslator()->translate('ITEM_BIDDING_HISTORY', 'item'),
                        $this->getLotRenderer()->renderLotNo($auctionLot),
                        $this->getAuctionRenderer()->renderSaleNo($this->getAuction())
                    );
                }
            }
            if (
                $controller === ResponsiveRoute::C_PROFILE
                && $action === ResponsiveRoute::APR_VIEW
            ) {
                $this->output = $this->getTranslator()->translate('USER_PROFILE_HTML_TITLE', 'user');
            }
            if ($this->output === '') {
                $this->output = $controller . ' - ' . $action;
            }
        }
    }

    /** Render methods */

    /**
     * Render auction
     */
    protected function renderAuction(): void
    {
        switch ($this->type) {
            case self::TYPE_DESCRIPTION:
                $this->output = $this->getAuction()
                    ? TextTransformer::new()->cut($this->getAuction()->Description, 160)
                    : '';
                break;
            case self::TYPE_KEYWORDS:
                $sampleLotNames = $this->getFeaturedLotLoader()
                    ->setAuction($this->getAuction())
                    ->loadNames();
                $this->output = implode(',', $sampleLotNames);
                break;
            case self::TYPE_TITLE:
                $this->output .= $this->getAuction()
                    ? $this->getAuctionRenderer()->renderName($this->getAuction())
                    : '';
                break;
        }
    }

    /**
     * Render lotItem
     */
    protected function renderLotItem(): void
    {
        $lotItem = $this->getLotItem();
        switch ($this->type) {
            case self::TYPE_DESCRIPTION:
                if ($lotItem) {
                    $this->output .= TextTransformer::new()->cut($lotItem->Description, 160);
                    $mainImage = $this->getLotImageLoader()->loadDefaultForLot($lotItem->Id, true);
                    if ($mainImage) {
                        $lotImageUrl = $this->getUrlBuilder()->build(
                            LotImageUrlConfig::new()->construct($mainImage->Id, Constants\Image::MEDIUM, $lotItem->AccountId)
                        );
                        $this->outputFacebook .= HtmlRenderer::new()
                            ->meta($lotImageUrl, ['property' => 'og:image']);
                    }
                }
                break;
            case self::TYPE_KEYWORDS:
                // Use categories names as keywords
                $lotCategories = $this->getLotCategories();
                if ($lotCategories) {
                    foreach ($lotCategories as $lotCategory) {
                        $this->output .= $lotCategory->Name . ',';
                    }
                    $this->output .= $this->getLotRenderer()->makeName($lotItem->Name, $this->getAuction()->TestAuction);
                }
                break;
            case self::TYPE_TITLE:
                $this->output .= $this->parseSeoTitle()
                    ?: $this->getLotRenderer()->makeName($lotItem->Name, $this->getAuction()->TestAuction);
                break;
        }
    }

    /**
     * Render facebook meta data
     */
    protected function renderFacebookMetaData(): void
    {
        if (
            $this->type === self::TYPE_DESCRIPTION
            && $this->getAuction()
        ) {
            $metaDataEntity = $this->getLotItem() ?: $this->getAuction();

            if ($metaDataEntity->FbOgDescription) {
                $this->outputFacebook .= HtmlRenderer::new()
                    ->meta($metaDataEntity->FbOgDescription, ['property' => 'og:description']);
            }
            if ($metaDataEntity->FbOgTitle) {
                $this->outputFacebook .= HtmlRenderer::new()
                    ->meta($metaDataEntity->FbOgTitle, ['property' => 'og:title']);
            }
            if ($metaDataEntity->FbOgImageUrl) {
                $this->outputFacebook .= HtmlRenderer::new()
                    ->meta($metaDataEntity->FbOgImageUrl, ['property' => 'og:image']);
            }
        }
    }

    /**
     * Parse seo title
     * Field setting_seo.auction_listing_page_title may contain the following tags: {auction_name}{auction_title}{item_name}{item_title}
     * For example 'Some text {auction_name|auction_title}{item_name}' will parse like: 'Some text (auction.name or auction.seoMetaTitle) lotItem.name'
     * @return string
     */
    protected function parseSeoTitle(): string
    {
        $auctionListingPageTitle = $this->getSettingsManager()->getForSystem(Constants\Setting::AUCTION_LISTING_PAGE_TITLE);
        preg_match_all('/{+(.*?)}/', $auctionListingPageTitle, $matches);
        $tags = [];
        $values = [];
        foreach ($matches[1] as $index => $match) {
            foreach (explode('|', $match) as $tag) {
                $value = isset($this->seoTagObjectLink[$tag])
                    ? $this->{$this->seoTagObjectLink[$tag][0]}->{$this->seoTagObjectLink[$tag][1]} : null;
                if ($value) {
                    $tags[$index] = $matches[0][$index];
                    $values[$index] = $value;
                    break;
                }
            }
        }
        return str_replace($tags, $values, $auctionListingPageTitle);
    }

    /**
     * It gives parameter name for fetching auction id
     * @param string $controller
     * @param string $action
     * @return string
     */
    protected function detectAuctionIdParamName(string $controller, string $action): string
    {
        return ($controller === Constants\ResponsiveRoute::C_LOT_DETAILS
            && $action === Constants\ResponsiveRoute::ALD_INDEX)
            ? Constants\UrlParam::R_CATALOG
            : Constants\UrlParam::R_ID;
    }

    public function getLotCategories(): array
    {
        if ($this->lotCategories === null) {
            $this->lotCategories = $this->getLotCategoryLoader()->loadForLot($this->getLotItemId(), true);
        }
        return $this->lotCategories;
    }
}
