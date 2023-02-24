<?php
/**
 * Helping methods for auction fields rendering
 *
 * SAM-4105: Auction fields renderer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 13, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Render;

use Auction;
use DateTime;
use Exception;
use Sam\Application\Url\Build\Config\Image\AuctionImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Image\Load\AuctionImageLoaderAwareTrait;
use Sam\Core\Auction\Render\AuctionPureRenderer;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Transform\Html\HtmlRenderer;
use Sam\Date\CurrentDateTrait;
use Sam\Image\ImageHelper;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Locale\Formatter\DateTimeFormatter;
use Sam\Locale\Formatter\DateTimeFormatterAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class AuctionRenderer
 * @package Sam\Auction\Render
 */
class AuctionRenderer extends CustomizableClass implements AuctionRendererInterface
{
    use AuctionImageLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use DateTimeFormatterAwareTrait;
    use TimezoneLoaderAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;

    public const OP_ALLOWED_TAGS = OptionalKeyConstants::KEY_ALLOWED_TAGS; // array
    public const OP_SALE_NO_EXTENSION_SEPARATOR = OptionalKeyConstants::KEY_SALE_NO_EXTENSION_SEPARATOR; // string
    public const OP_TEST_AUCTION_PREFIX = OptionalKeyConstants::KEY_TEST_AUCTION_PREFIX; // string

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Render auction name, strip tags for admin pages, render unescaped for the public front pages
     * @param string|null $auctionName null when we render name of absent record
     * @param bool $isTestAuction
     * @param bool $isHtml
     * @param array $optionals = [
     *      self::OP_ALLOWED_TAGS => array,
     *      self::OP_TEST_AUCTION_PREFIX => string,
     * ]
     * @return string
     */
    public function makeName(
        ?string $auctionName,
        bool $isTestAuction = false,
        bool $isHtml = false,
        array $optionals = []
    ): string {
        $prefix = $isTestAuction // optimization
            ? $this->fetchTestAuctionPrefix($optionals)
            : '';
        $allowedTags = $isHtml
            ? $this->fetchAllowedTags($optionals)
            : [];
        return AuctionPureRenderer::new()->makeName((string)$auctionName, $isTestAuction, $prefix, $allowedTags);
    }

    /**
     * Render auction name considering a.test_auction option
     * @param Auction|null $auction
     * @param bool $isHtml
     * @param array $optionals = [
     *      self::OP_ALLOWED_TAGS => array,
     *      self::OP_TEST_AUCTION_PREFIX => string,
     * ]
     * @return string
     */
    public function renderName(?Auction $auction, bool $isHtml = false, array $optionals = []): string
    {
        $prefix = $auction && $auction->TestAuction  // optimization
            ? $this->fetchTestAuctionPrefix($optionals)
            : '';
        $allowedTags = $isHtml
            ? $this->fetchAllowedTags($optionals)
            : [];
        return AuctionPureRenderer::new()->makeNameByEntity($auction, $prefix, $allowedTags);
    }

    /**
     * Renders sale number + extension (with separator)
     * @param int|string|null $saleNo null means rendering for absent auction
     * @param string|null $saleNoExt null means rendering for absent auction
     * @param array $optionals = [
     *      self::OP_SALE_NO_EXTENSION_SEPARATOR => string,
     * ]
     * @return string
     */
    public function makeSaleNo(int|string|null $saleNo, ?string $saleNoExt, array $optionals = []): string
    {
        return AuctionPureRenderer::new()->makeSaleNo(
            (string)$saleNo,
            (string)$saleNoExt,
            $this->fetchSaleNoExtensionSeparator($optionals)
        );
    }

    /**
     * Render sale number with extension
     * @param Auction|null $auction
     * @param array $optionals = [
     *      self::OP_SALE_NO_EXTENSION_SEPARATOR => string,
     * ]
     * @return string
     */
    public function renderSaleNo(?Auction $auction, array $optionals = []): string
    {
        return AuctionPureRenderer::new()->makeSaleNoByEntity(
            $auction,
            $this->fetchSaleNoExtensionSeparator($optionals)
        );
    }

    /**
     * @param int $auctionStatus
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function makeAuctionStatusTranslated(
        int $auctionStatus,
        ?int $accountId = null,
        ?int $languageId = null
    ): string {
        $langStatuses = [
            Constants\Auction::AS_ACTIVE => 'AUCTION_STATUS_ACTIVE',
            Constants\Auction::AS_STARTED => 'AUCTION_STATUS_STARTED',
            Constants\Auction::AS_CLOSED => 'AUCTION_STATUS_CLOSED',
            Constants\Auction::AS_DELETED => 'AUCTION_STATUS_DELETED',
            Constants\Auction::AS_ARCHIVED => 'AUCTION_STATUS_ARCHIVED',
            Constants\Auction::AS_PAUSED => 'AUCTION_STATUS_PAUSED',
        ];
        $output = '';
        if (isset($langStatuses[$auctionStatus])) {
            $output = $this->getTranslator()->translate(
                $langStatuses[$auctionStatus],
                'auctions',
                $accountId,
                $languageId
            );
        }
        return $output;
    }

    /**
     * General auction status (In progress, Upcoming, Closed)
     * @param int $auctionStatus
     * @param string $auctionType
     * @param int|null $eventType
     * @param string $startDateUtcIso
     * @param string $endDateUtcIso
     * @return string
     * @throws Exception
     */
    public function makeGeneralStatus(
        int $auctionStatus,
        string $auctionType,
        ?int $eventType,
        string $startDateUtcIso,
        string $endDateUtcIso
    ): string {
        $status = $this->detectGeneralStatus(
            $auctionStatus,
            $auctionType,
            $eventType,
            $startDateUtcIso,
            $endDateUtcIso
        );

        if (!$status) {
            return ''; // should be impossible
        }

        $output = Constants\Auction::$generalStatusNames[$status];
        return $output;
    }

    /**
     * General auction status (In progress, Upcoming, Closed)
     * @param int $auctionStatus
     * @param string $auctionType
     * @param int|null $eventType
     * @param string $startDateUtcIso
     * @param string $endDateUtcIso
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     * @throws Exception
     */
    public function makeGeneralStatusTranslated(
        int $auctionStatus,
        string $auctionType,
        ?int $eventType,
        string $startDateUtcIso,
        string $endDateUtcIso,
        ?int $accountId = null,
        ?int $languageId = null
    ): string {
        $status = $this->detectGeneralStatus(
            $auctionStatus,
            $auctionType,
            $eventType,
            $startDateUtcIso,
            $endDateUtcIso
        );

        if (!$status) {
            return ''; // should be impossible
        }

        $langStatuses = [
            Constants\Auction::STATUS_IN_PROGRESS => 'GENERAL_SALEINPROGRESS',
            Constants\Auction::STATUS_UPCOMING => 'GENERAL_SALEUPCOMING',
            Constants\Auction::STATUS_CLOSED => 'GENERAL_SALECLOSED',
        ];
        $key = $langStatuses[$status];
        $output = $this->getTranslator()->translate($key, 'general', $accountId, $languageId);
        return $output;
    }

    /**
     * General auction status (In progress, Upcoming, Closed)
     * @param int $auctionStatus
     * @param string $auctionType
     * @param int|null $eventType
     * @param string $startDateUtcIso
     * @param string $endDateUtcIso
     * @return int|null
     * @throws Exception
     */
    protected function detectGeneralStatus(
        int $auctionStatus,
        string $auctionType,
        ?int $eventType,
        string $startDateUtcIso,
        string $endDateUtcIso
    ): ?int {
        $eventType = Cast::toInt($eventType, Constants\Auction::$eventTypes);
        $auctionStatus = Cast::toInt($auctionStatus, Constants\Auction::$auctionStatuses);
        $startDateUtc = new DateTime($startDateUtcIso);
        $endDateUtc = new DateTime($endDateUtcIso);
        $currentDateUtc = $this->getCurrentDateUtc();
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $status = null;
        if ($auctionStatusPureChecker->isTimed($auctionType)) {
            if ($auctionStatusPureChecker->isTimedOngoing($auctionType, $eventType)) {
                $status = Constants\Auction::STATUS_IN_PROGRESS;
            } elseif ($startDateUtc > $currentDateUtc) {
                $status = Constants\Auction::STATUS_UPCOMING;
            } elseif ($endDateUtc > $currentDateUtc) {
                $status = Constants\Auction::STATUS_IN_PROGRESS;
            } else {
                $status = Constants\Auction::STATUS_CLOSED;
            }
        } elseif ($auctionStatusPureChecker->isLiveOrHybrid($auctionType)) {
            if (
                $auctionStatusPureChecker->isActive($auctionStatus)
                && $startDateUtc > $currentDateUtc
            ) {
                $status = Constants\Auction::STATUS_UPCOMING;
            } elseif ($auctionStatusPureChecker->isStartedOrPaused($auctionStatus)) {
                $status = Constants\Auction::STATUS_IN_PROGRESS;
            } else {
                $status = Constants\Auction::STATUS_CLOSED;
            }
        }

        return $status;
    }

    /**
     * @param string|null $auctionType null leads to empty string
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function makeAuctionTypeTranslated(
        ?string $auctionType,
        ?int $accountId = null,
        ?int $languageId = null
    ): string {
        $langTypes = [
            Constants\Auction::TIMED => 'AUCTIONS_FORMAT_TIMED',
            Constants\Auction::LIVE => 'AUCTIONS_FORMAT_LIVE',
            Constants\Auction::HYBRID => 'AUCTIONS_FORMAT_HYBRID',
        ];
        $key = $langTypes[$auctionType] ?? '';
        $output = !empty($key)
            ? $this->getTranslator()->translate($key, 'auctions', $accountId, $languageId)
            : '';
        return $output;
    }

    /**
     * @param Auction $auction
     * @param string|null $size when null, then use value defined in config core->image->mapping->auctionList
     * @return string
     */
    public function renderImageTag(Auction $auction, ?string $size): string
    {
        $auctionImage = $this->getAuctionImageLoader()->loadDefault($auction->Id);
        $output = $auctionImage ? $this->makeImageTag($auctionImage->Id, $size, $auction->AccountId) : '';
        return $output;
    }

    /**
     * Return <img> tag for auction image
     * @param int $auctionImageId
     * @param string|null $size
     * @param int|null $accountId null for main account
     * @return string
     */
    public function makeImageTag(int $auctionImageId, ?string $size, ?int $accountId = null): string
    {
        if (!$auctionImageId) {
            return '';
        }
        $url = $this->makeImageUrl($auctionImageId, $size, $accountId);
        $output = HtmlRenderer::new()->makeImgHtmlTag('img', ['src' => $url]);
        return $output;
    }

    /**
     * Return url for auction image
     * @param int $auctionImageId
     * @param string|null $size
     * @param int|null $accountId null for main account
     * @return string
     */
    public function makeImageUrl(int $auctionImageId, ?string $size = null, ?int $accountId = null): string
    {
        if (!$auctionImageId) {
            return '';
        }

        $size = $size ?? ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->auctionList'));
        $auctionImageUrl = $this->getUrlBuilder()->build(
            AuctionImageUrlConfig::new()->construct($auctionImageId, $size, $accountId)
        );
        return $auctionImageUrl;
    }

    /**
     * @param Auction $auction
     * @return string
     */
    public function renderDates(Auction $auction): ?string
    {
        if ($auction->isLiveOrHybrid()) {
            return $this->getStartClosingDateWithTimezone($auction);
        }
        if ($auction->isTimedScheduled()) {
            return $this->getStartBiddingDateWithTimezone($auction) . ' - ' . $this->getEndDateWithTimezone($auction);
        }
        if ($auction->isTimedOngoing()) {
            return Constants\Auction::$eventTypeFullNames[Constants\Auction::ET_ONGOING];
        }
        return '';
    }

    /**
     * @param Auction $auction
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function renderDatesTranslated(
        Auction $auction,
        ?int $accountId = null,
        ?int $languageId = null
    ): ?string {
        if ($auction->isLiveOrHybrid()) {
            return $this->getStartClosingDateWithTimezone($auction);
        }
        if ($auction->isTimedScheduled()) {
            return $this->getStartBiddingDateWithTimezone($auction) . ' - ' . $this->getEndDateWithTimezone($auction);
        }
        if ($auction->isTimedOngoing()) {
            return $this->getTranslator()->translate('AUCTIONS_EVENT_TYPE', 'auctions', $accountId, $languageId);
        }
        return '';
    }

    /**
     * @param Auction $auction
     * @return string
     */
    protected function getEndDateWithTimezone(Auction $auction): string
    {
        $tzLocation = $this->getTimezoneLoader()->load($auction->TimezoneId, true)->Location ?? null;
        return $this->getDateTimeFormatter()->format(
            $auction->EndDate,
            $tzLocation,
            DateTimeFormatter::DATE_TYPE_FULL
        );
    }

    /**
     * @param Auction $auction
     * @return string
     */
    protected function getStartClosingDateWithTimezone(Auction $auction): string
    {
        $tzLocation = $this->getTimezoneLoader()->load($auction->TimezoneId, true)->Location ?? null;
        return $this->getDateTimeFormatter()->format(
            $auction->StartClosingDate,
            $tzLocation,
            DateTimeFormatter::DATE_TYPE_FULL
        );
    }

    /**
     * @param Auction $auction
     * @return string
     */
    protected function getStartBiddingDateWithTimezone(Auction $auction): string
    {
        $tzLocation = $this->getTimezoneLoader()->load($auction->TimezoneId, true)->Location ?? null;
        return $this->getDateTimeFormatter()->format(
            $auction->StartBiddingDate,
            $tzLocation,
            DateTimeFormatter::DATE_TYPE_FULL
        );
    }

    // --- Internal ---

    /**
     * Read Test Auction Prefix value from optional parameters or from installation config.
     * @param array $optionals
     * @return string
     */
    protected function fetchTestAuctionPrefix(array $optionals): string
    {
        return (string)($optionals[self::OP_TEST_AUCTION_PREFIX]
            ?? $this->cfg()->get('core->auction->test->prefix'));
    }

    /**
     * Read Allowed Html Tags from optional parameters or from installation config.
     * @param array $optionals
     * @return array
     */
    protected function fetchAllowedTags(array $optionals): array
    {
        return (array)($optionals[self::OP_ALLOWED_TAGS]
            ?? $this->cfg()->get('core->entity->htmlTagWhitelist')->toArray());
    }

    /**
     * Read Sale# Extension Separator from optional parameters or from installation config.
     * @param array $optionals
     * @return string
     */
    protected function fetchSaleNoExtensionSeparator(array $optionals): string
    {
        return (string)($optionals[self::OP_SALE_NO_EXTENSION_SEPARATOR]
            ?? $this->cfg()->get('core->auction->saleNo->extensionSeparator')
        );
    }
}
