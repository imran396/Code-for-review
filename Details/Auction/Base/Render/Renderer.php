<?php
/**
 * Rendering methods for placeholders of auction feed
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Feb 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Custom methods can be used there or in customized class
 *
 * Optional method called when rendering the custom auction field value
 * param AuctionCustField $customField the custom auction field object
 * param mixed $value the value
 * param ind $auctionId
 * return string the rendered value
 * public function AuctionCustomField_{Field name}_Render(AuctionCustField $customField, $value, $auctionId);
 *
 * {Field name} - Camel cased name of custom field (see TextTransformer::toCamelCase())
 */

namespace Sam\Details\Auction\Base\Render;

use AuctionCustField;
use DateTime;
use Exception;
use Laminas\Math\Rand;
use Sam\Account\Render\AccountRendererAwareTrait;
use Sam\Address\AddressFormatterCreateTrait;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionInfoUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveCatalogUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveLiveSaleUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Available\AuctionAvailabilityCheckerFactory;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Auction\Render\AuctionPureRenderer;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\CustomField\Auction\Help\AuctionCustomFieldHelperAwareTrait;
use Sam\CustomField\Auction\Qform\ViewControls;
use Sam\Date\CurrentDateTrait;
use Sam\Date\Render\DateRendererCreateTrait;
use Sam\Image\ImageHelper;
use Sam\Location\Render\LocationRendererAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class Renderer
 * @package Sam\Details
 */
class Renderer extends \Sam\Details\Core\Render\Renderer
{
    use AccountRendererAwareTrait;
    use AddressFormatterCreateTrait;
    use AuctionCustomFieldHelperAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use CurrentDateTrait;
    use DateRendererCreateTrait;
    use LocationRendererAwareTrait;
    use SettingsManagerAwareTrait;
    use UrlBuilderAwareTrait;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function construct(): static
    {
        return $this;
    }

    public function renderAccountId(array $row): string
    {
        return (string)$row['account_id'];
    }

    public function renderAccountName(array $row): string
    {
        return $row['account_name'];
    }

    public function renderAccountImageUrl(array $row): string
    {
        return $this->getAccountRenderer()->makeImageUrl((int)$row['account_id']);
    }

    public function renderAccountSiteUrl(array $row): string
    {
        return (string)$row['account_site_url'];
    }

    public function renderAccountImageTag(array $row): string
    {
        return $this->getAccountRenderer()->makeImageTag((int)$row['account_id']);
    }

    public function renderAuctioneer(array $row): string
    {
        return (string)$row['auctioneer_name'];
    }

    public function renderAuctionImageTag(array $row): string
    {
        $size = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->auctionList'));
        return $this->getAuctionRenderer()->makeImageTag((int)$row['image_id'], $size, (int)$row['account_id']);
    }

    public function renderAuctionImageUrl(array $row): string
    {
        $size = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->auctionList'));
        return $this->getAuctionRenderer()->makeImageUrl((int)$row['image_id'], $size, (int)$row['account_id']);
    }

    public function renderAuctionInfoUrl(array $row): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveAuctionInfoUrlConfig::new()->forWeb(
                (int)$row['id'],
                $row['auction_seo_url'],
                [
                    UrlConfigConstants::OP_ACCOUNT_ID => (int)$row['account_id'],
                    UrlConfigConstants::OP_AUCTION_INFO_LINK => $row['auction_info_link']
                ]
            )
        );
    }

    public function renderAuctionType(array $row, bool $isTranslated): string
    {
        return $isTranslated
            ? $this->getAuctionRenderer()->makeAuctionTypeTranslated(
                $row['auction_type'],
                $this->getSystemAccountId(),
                $this->getLanguageId()
            )
            : AuctionPureRenderer::new()->makeAuctionType($row['auction_type']);
    }

    public function renderCatalogUrl(array $row): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveCatalogUrlConfig::new()->forWeb(
                (int)$row['id'],
                $row['auction_seo_url'],
                [UrlConfigConstants::OP_ACCOUNT_ID => (int)$row['account_id']]
            )
        );
    }

    public function renderCountry(array $row): string
    {
        return AddressRenderer::new()->countryName((string)$row['auction_held_in']);
    }

    public function renderCurrencySign(array $row): string
    {
        return (string)$row['currency_sign'];
    }

    public function renderCustomField(array $row, AuctionCustField $customField, ?string $parameters = null): string
    {
        $customField->Parameters = $parameters ?: $customField->Parameters;
        $alias = 'c' . DbTextTransformer::new()->toDbColumn($customField->Name);
        $rawValue = $row[$alias];
        $renderMethod = $this->getAuctionCustomFieldHelper()->makeCustomMethodName($customField->Name, 'Render');
        if (method_exists($this, $renderMethod)) {
            $output = $this->$renderMethod($customField, $rawValue, (int)$row['id']);
        } else {
            $output = ViewControls::new()
                ->enablePublic(true)
                ->renderByValue($customField, $rawValue, (int)$row['id']);
        }
        return $output;
    }

    public function renderDays(array $row): string
    {
        $output = '';
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isLive($row['auction_type'])) {
            $startDate = new DateTime($row['start_closing_date']);
            $endDate = new DateTime($row['end_date']);
            $output = $endDate->diff($startDate)->format('%a');
        }
        return $output;
    }

    public function renderDescription(array $row): string
    {
        return $row['description'];
    }

    public function renderEmail(array $row): string
    {
        return $row['email'];
    }

    public function renderId(array $row): string
    {
        return (string)$row['id'];
    }

    public function renderLiveSaleUrl(array $row): string
    {
        $liveSaleUrl = '';
        $auctionType = $row['auction_type'];
        $auctionId = (int)$row['id'];
        $auction = $this->getAuctionLoader()->load($auctionId);
        if ($auction === null) {
            log_error("Available auction not found" . composeSuffix(['a' => $auctionId]));
            return '';
        }

        $availabilityChecker = AuctionAvailabilityCheckerFactory::new()->create($auctionType);
        $isBiddingConsoleAvailable = $availabilityChecker->isBiddingConsoleAvailable($auction);
        if ($isBiddingConsoleAvailable) {
            $liveSaleUrl = $this->buildResponsiveLiveSaleUrl(
                $auctionId,
                $row['auction_seo_url'],
                (int)$row['account_id']
            );
        }
        return $liveSaleUrl;
    }

    protected function buildResponsiveLiveSaleUrl(int $auctionId, string $auctionSeoUrl, int $accountId): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveLiveSaleUrlConfig::new()->forWeb(
                $auctionId,
                $auctionSeoUrl,
                [UrlConfigConstants::OP_ACCOUNT_ID => $accountId]
            )
        );
    }

    public function renderInvoiceLocationAddress(array $row): string
    {
        return (string)$row['invoice_location_address'];
    }

    public function renderInvoiceLocationLogoTag(array $row): string
    {
        return $this->getLocationRenderer()->makeLogoTag((int)$row['invoice_location_id'], (int)$row['account_id']);
    }

    public function renderInvoiceLocationLogoUrl(array $row): string
    {
        return $this->getLocationRenderer()->makeLogoUrl((int)$row['invoice_location_id'], (int)$row['account_id']);
    }

    public function renderInvoiceLocationName(array $row): string
    {
        return (string)$row['invoice_location_name'];
    }

    public function renderInvoiceLocationCountry(array $row): string
    {
        return AddressRenderer::new()->countryName((string)$row['invoice_location_country']);
    }

    public function renderInvoiceLocationState(array $row): string
    {
        return AddressRenderer::new()->stateName(
            (string)$row['invoice_location_state'],
            (string)$row['invoice_location_country']
        );
    }

    public function renderInvoiceLocation(array $row): string
    {
        return $this->createAddressFormatter()->format(
            (string)$row['invoice_location_country'],
            (string)$row['invoice_location_state'],
            (string)$row['invoice_location_city'],
            (string)$row['invoice_location_zip'],
            (string)$row['invoice_location_address']
        );
    }

    public function renderEventLocationLogoTag(array $row): string
    {
        return $this->getLocationRenderer()->makeLogoTag((int)$row['event_location_id'], (int)$row['account_id']);
    }

    public function renderEventLocationLogoUrl(array $row): string
    {
        return $this->getLocationRenderer()->makeLogoUrl((int)$row['event_location_id'], (int)$row['account_id']);
    }

    public function renderEventLocationCountry(array $row): string
    {
        return AddressRenderer::new()->countryName((string)$row['event_location_country']);
    }

    public function renderEventLocationState(array $row): string
    {
        return AddressRenderer::new()->stateName(
            (string)$row['event_location_state'],
            (string)$row['event_location_country']
        );
    }

    public function renderEventLocation(array $row): string
    {
        return $this->createAddressFormatter()->format(
            (string)$row['event_location_country'],
            (string)$row['event_location_state'],
            (string)$row['event_location_city'],
            (string)$row['event_location_zip'],
            (string)$row['event_location_address']
        );
    }

    public function renderTotalLots(array $row): string
    {
        return (string)$row['total_lots'];
    }

    public function renderName(array $row): string
    {
        return $this->getAuctionRenderer()->makeName($row['name'], (bool)$row['test_auction'], true);
    }

    public function renderRegisterToBidUrl(array $row): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forWeb(
                Constants\Url::P_LOGIN_REDIRECT_FEED,
                (int)$row['id'],
                [UrlConfigConstants::OP_ACCOUNT_ID => (int)$row['account_id']]
            )
        );
    }

    public function renderSaleNo(array $row): string
    {
        return $this->getAuctionRenderer()->makeSaleNo($row['sale_num'], $row['sale_num_ext']);
    }

    public function renderShippingInfo(array $row): string
    {
        return $row['shipping_info'];
    }

    public function renderStatus(array $row, bool $isTranslated): string
    {
        $startDate = AuctionStatusPureChecker::new()->isTimed($row['auction_type'])
            ? (string)$row['start_bidding_date']
            : (string)$row['start_closing_date'];
        return $isTranslated
            ? $this->getAuctionRenderer()->makeGeneralStatusTranslated(
                (int)$row['auction_status_id'],
                (string)$row['auction_type'],
                (int)$row['event_type'],
                $startDate,
                (string)$row['end_date'],
                $this->getSystemAccountId(),
                $this->getLanguageId()
            )
            : $this->getAuctionRenderer()->makeGeneralStatus(
                (int)$row['auction_status_id'],
                (string)$row['auction_type'],
                (int)$row['event_type'],
                $startDate,
                (string)$row['end_date']
            );
    }

    public function renderTaxCountry(array $row): string
    {
        return AddressRenderer::new()->countryName((string)$row['tax_default_country']);
    }

    public function renderTaxPercent(array $row): string
    {
        return (string)$row['tax_percent'];
    }

    public function renderTaxStates(array $row): string
    {
        return (string)$row['tax_states'];
    }

    public function renderTermsAndConditions(array $row): string
    {
        return $row['terms_and_conditions'];
    }

    public function renderTimeLeft(array $row): string
    {
        $output = '';
        $secondsLeft = (int)$row['seconds_left'];
        $secondsBefore = (int)$row['seconds_before'];
        $auctionAccountId = (int)$row['account_id'];
        $isShowCountdownSeconds = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::SHOW_COUNTDOWN_SECONDS, $auctionAccountId);
        $timeLeftRandId = substr(md5(Rand::getBytes(32)), 0, 5);
        if ($secondsBefore > 0) {
            $output = $this->translate('AUCTIONS_SALESTARTSIN', 'auctions')
                . ' ' . '<span id="' . Constants\Placeholder::HTML_ID_TIME_LEFT_COUNTDOWN . $timeLeftRandId . '">'
                . $this->produceTimeLeft($secondsBefore, $isShowCountdownSeconds)
                . '</span>';
        } elseif ($secondsLeft > 0) {
            $output = $this->translate('AUCTIONS_SALEENDSIN', 'auctions')
                . ' ' . '<span id="' . Constants\Placeholder::HTML_ID_TIME_LEFT_COUNTDOWN . $timeLeftRandId . '">'
                . $this->produceTimeLeft($secondsLeft, $isShowCountdownSeconds)
                . '</span>';
        }
        return $output;
    }

    /**
     * Returns value of first field from result set
     */
    public function renderWavebidAuctionGuid(array $row): string
    {
        return $row['wavebid_auction_guid'];
    }

    /**
     * @param bool $alwaysSeconds false - don't display seconds, when $seconds value is larger than 1 hour
     */
    protected function produceTimeLeft(int $seconds, bool $alwaysSeconds): string
    {
        $displaySeconds = $alwaysSeconds || ($seconds > 0 && $seconds < 60 * 60);
        $output = $this->createDateRenderer()->renderTimeLeft($seconds);
        if (!$displaySeconds) {
            $output = preg_replace('/ \d+s/', '', $output);
        }
        return $output;
    }

    public function renderIsLiveOrHybrid(array $row): string
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $isLiveOrHybrid = $auctionStatusPureChecker->isLiveOrHybrid($row['auction_type']);
        return (string)$isLiveOrHybrid;
    }

    public function renderIsTimedScheduled(array $row): string
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $isTimedScheduled = $auctionStatusPureChecker->isTimedScheduled($row['auction_type'], (int)$row['event_type']);
        return (string)$isTimedScheduled;
    }

    public function renderIsTimedOngoing(array $row): string
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $isTimedOngoing = $auctionStatusPureChecker->isTimedOngoing($row['auction_type'], (int)$row['event_type']);
        return (string)$isTimedOngoing;
    }

    /**
     * Is auction closed
     */
    public function renderIsClosed(array $row): string
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $auctionType = $row['auction_type'];
        $auctionStatus = (int)$row['auction_status_id'];
        if ($auctionStatusPureChecker->isLiveOrHybrid($auctionType)) {
            return (string)$auctionStatusPureChecker->isClosed($auctionStatus);
        }

        $eventType = (int)$row['event_type'];
        if ($auctionStatusPureChecker->isTimedScheduled($auctionType, $eventType)) {
            try {
                $endDate = new DateTime($row['end_date']);
            } catch (Exception) {
                log_error(
                    "Auction end date not defined, consider auction as closed"
                    . composeSuffix(['a' => $row['auction_id']])
                );
                return '1';
            }
            $isAuctionClosed = $auctionStatusPureChecker->isClosed($auctionStatus)
                || $endDate->getTimestamp() < $this->getCurrentDateUtc()->getTimestamp();
            return (string)$isAuctionClosed;
        }

        return '';
    }

    /**
     * Check multiple tenant install or not
     */
    public function renderIsMultipleTenantInstall(): string
    {
        $isMultipleTenantInstall = $this->cfg()->get('core->portal->enabled');
        return (string)$isMultipleTenantInstall;
    }

    /**
     * Check single tenant install or not
     */
    public function renderIsSingleTenantInstall(): string
    {
        $isSingleTenantInstall = !$this->cfg()->get('core->portal->enabled');
        return (string)$isSingleTenantInstall;
    }
}
