<?php
/**
 * Auction Details general template parser
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
 */

namespace Sam\Details\Auction\Base\Render;

use Sam\Core\Constants;
use Sam\Details\Core\Placeholder\Placeholder;

/**
 * Class TemplateParser
 * @package Sam\Details
 * @property Renderer $renderer
 */
class TemplateParser extends \Sam\Details\Core\Render\TemplateParser
{
    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    protected function produceValueForRegularPlaceholder(Placeholder $placeholder, array $row): string
    {
        $key = $placeholder->getKey();
        if ($key === Constants\AuctionDetail::PL_ACCOUNT_ID) {
            $value = $this->getRenderer()->renderAccountId($row);
        } elseif ($key === Constants\AuctionDetail::PL_ACCOUNT_NAME) {
            $value = $this->getRenderer()->renderAccountName($row);
        } elseif ($key === Constants\AuctionDetail::PL_ACCOUNT_IMAGE_TAG) {
            $value = $this->getRenderer()->renderAccountImageTag($row);
        } elseif ($key === Constants\AuctionDetail::PL_ACCOUNT_SITE_URL) {
            $value = $this->getRenderer()->renderAccountSiteUrl($row);
        } elseif ($key === Constants\AuctionDetail::PL_ACCOUNT_IMAGE_URL) {
            $value = $this->getRenderer()->renderAccountImageUrl($row);
        } elseif ($key === Constants\AuctionDetail::PL_AUCTIONEER) {
            $value = $this->getRenderer()->renderAuctioneer($row);
        } elseif ($key === Constants\AuctionDetail::PL_TYPE) {
            $value = $this->getRenderer()->renderAuctionType($row, false);
        } elseif ($key === Constants\AuctionDetail::PL_TYPE_LANG) {
            $value = $this->getRenderer()->renderAuctionType($row, true);
        } elseif ($key === Constants\AuctionDetail::PL_CATALOG_URL) {
            $value = $this->getRenderer()->renderCatalogUrl($row);
        } elseif ($key === Constants\AuctionDetail::PL_COUNTRY) {
            $value = $this->getRenderer()->renderCountry($row);
        } elseif ($key === Constants\AuctionDetail::PL_CURRENCY_SIGN) {
            $value = $this->getRenderer()->renderCurrencySign($row);
        } elseif ($key === Constants\AuctionDetail::PL_DAYS) {
            $value = $this->getRenderer()->renderDays($row);
        } elseif ($key === Constants\AuctionDetail::PL_DESCRIPTION) {
            $value = $this->getRenderer()->renderDescription($row);
        } elseif ($key === Constants\AuctionDetail::PL_EMAIL) {
            $value = $this->getRenderer()->renderEmail($row);
        } elseif ($key === Constants\AuctionDetail::PL_ID) {
            $value = $this->getRenderer()->renderId($row);
        } elseif ($key === Constants\AuctionDetail::PL_IMAGE_TAG) {
            $value = $this->getRenderer()->renderAuctionImageTag($row);
        } elseif ($key === Constants\AuctionDetail::PL_IMAGE_URL) {
            $value = $this->getRenderer()->renderAuctionImageUrl($row);
        } elseif ($key === Constants\AuctionDetail::PL_INFO_URL) {
            $value = $this->getRenderer()->renderAuctionInfoUrl($row);
        } elseif ($key === Constants\AuctionDetail::PL_LIVE_URL) {
            $value = $this->getRenderer()->renderLiveSaleUrl($row);
        } elseif ($key === Constants\AuctionDetail::PL_INVOICE_LOCATION_ADDRESS) {
            $value = $this->getRenderer()->renderInvoiceLocationAddress($row);
        } elseif ($key === Constants\AuctionDetail::PL_INVOICE_LOCATION_LOGO_TAG) {
            $value = $this->getRenderer()->renderInvoiceLocationLogoTag($row);
        } elseif ($key === Constants\AuctionDetail::PL_INVOICE_LOCATION_LOGO_URL) {
            $value = $this->getRenderer()->renderInvoiceLocationLogoUrl($row);
        } elseif ($key === Constants\AuctionDetail::PL_INVOICE_LOCATION_NAME) {
            $value = $this->getRenderer()->renderInvoiceLocationName($row);
        } elseif ($key === Constants\AuctionDetail::PL_INVOICE_LOCATION_COUNTRY) {
            $value = $this->getRenderer()->renderInvoiceLocationCountry($row);
        } elseif ($key === Constants\AuctionDetail::PL_INVOICE_LOCATION_STATE) {
            $value = $this->getRenderer()->renderInvoiceLocationState($row);
        } elseif ($key === Constants\AuctionDetail::PL_INVOICE_LOCATION) {
            $value = $this->getRenderer()->renderInvoiceLocation($row);
        } elseif ($key === Constants\AuctionDetail::PL_EVENT_LOCATION_LOGO_TAG) {
            $value = $this->getRenderer()->renderEventLocationLogoTag($row);
        } elseif ($key === Constants\AuctionDetail::PL_EVENT_LOCATION_LOGO_URL) {
            $value = $this->getRenderer()->renderEventLocationLogoUrl($row);
        } elseif ($key === Constants\AuctionDetail::PL_EVENT_LOCATION_COUNTRY) {
            $value = $this->getRenderer()->renderEventLocationCountry($row);
        } elseif ($key === Constants\AuctionDetail::PL_EVENT_LOCATION_STATE) {
            $value = $this->getRenderer()->renderEventLocationState($row);
        } elseif ($key === Constants\AuctionDetail::PL_EVENT_LOCATION) {
            $value = $this->getRenderer()->renderEventLocation($row);
        } elseif ($key === Constants\AuctionDetail::PL_NAME) {
            $value = $this->getRenderer()->renderName($row);
        } elseif ($key === Constants\AuctionDetail::PL_REGISTER_TO_BID_URL) {
            $value = $this->getRenderer()->renderRegisterToBidUrl($row);
        } elseif ($key === Constants\AuctionDetail::PL_SALE_NO) {
            $value = $this->getRenderer()->renderSaleNo($row);
        } elseif ($key === Constants\AuctionDetail::PL_SHIPPING_INFO) {
            $value = $this->getRenderer()->renderShippingInfo($row);
        } elseif ($key === Constants\AuctionDetail::PL_STATUS) {
            $value = $this->getRenderer()->renderStatus($row, false);
        } elseif ($key === Constants\AuctionDetail::PL_STATUS_LANG) {
            $value = $this->getRenderer()->renderStatus($row, true);
        } elseif ($key === Constants\AuctionDetail::PL_TAX) {
            $value = $this->getRenderer()->renderTaxPercent($row);
        } elseif ($key === Constants\AuctionDetail::PL_TAX_STATES) {
            $value = $this->getRenderer()->renderTaxStates($row);
        } elseif ($key === Constants\AuctionDetail::PL_TAX_COUNTRY) {
            $value = $this->getRenderer()->renderTaxCountry($row);
        } elseif ($key === Constants\AuctionDetail::PL_TERMS) {
            $value = $this->getRenderer()->renderTermsAndConditions($row);
        } elseif ($key === Constants\AuctionDetail::PL_TIME_LEFT) {
            $value = $this->getRenderer()->renderTimeLeft($row);
        } elseif ($key === Constants\AuctionDetail::PL_TOTAL_LOTS) {
            $value = $this->getRenderer()->renderTotalLots($row);
        } elseif ($key === Constants\AuctionDetail::PL_WAVEBID_AUCTION_GUID) {
            $value = $this->getRenderer()->renderWavebidAuctionGuid($row);
        } else {
            $value = $this->getRenderer()->renderAsIs($row, $key);
        }
        return $value;
    }

    protected function produceValueForBooleanPlaceholder(Placeholder $placeholder, array $row): string
    {
        $value = '';
        $key = $placeholder->getKey();
        if ($key === Constants\AuctionDetail::PL_IS_CLOSED) {
            $value = $this->getRenderer()->renderIsClosed($row);
        } elseif ($key === Constants\AuctionDetail::PL_IS_LIVE_OR_HYBRID) {
            $value = $this->getRenderer()->renderIsLiveOrHybrid($row);
        } elseif ($key === Constants\AuctionDetail::PL_IS_TIMED_SCHEDULED) {
            $value = $this->getRenderer()->renderIsTimedScheduled($row);
        } elseif ($key === Constants\AuctionDetail::PL_IS_TIMED_ONGOING) {
            $value = $this->getRenderer()->renderIsTimedOngoing($row);
        } elseif ($key === Constants\AuctionDetail::PL_IS_MULTIPLE_TENANT_INSTALL) {
            $value = $this->getRenderer()->renderIsMultipleTenantInstall();
        } elseif ($key === Constants\AuctionDetail::PL_IS_SINGLE_TENANT_INSTALL) {
            $value = $this->getRenderer()->renderIsSingleTenantInstall();
        }
        return $value;
    }

    protected function produceValueForDatePlaceholder(Placeholder $placeholder, array $row): string
    {
        $key = $placeholder->getKey();
        // SAM-9471
        if (in_array($key, [Constants\AuctionDetail::PL_STARTS_CLOSING_DATE, Constants\AuctionDetail::PL_STARTS_CLOSING_DATE_GMT], true) && !$row['ap_show_auction_starts_ending']) {
            return '';
        }
        return parent::produceValueForDatePlaceholder($placeholder, $row);
    }

    protected function produceValueForDateAdditionalPlaceholder(Placeholder $placeholder, array $row): string
    {
        $key = $placeholder->getKey();
        // SAM-9471
        if (
            in_array($key, [Constants\AuctionDetail::PL_STARTS_CLOSING_DATE_TZ_CODE, Constants\AuctionDetail::PL_STARTS_CLOSING_DATE_TZ_OFFSET], true)
            && !$row['ap_show_auction_starts_ending']
        ) {
            return '';
        }
        return parent::produceValueForDateAdditionalPlaceholder($placeholder, $row);
    }

    protected function produceValueForCustomFieldPlaceholder(Placeholder $placeholder, array $row): string
    {
        $customField = $this->getConfigManager()->getAuctionCustomFieldByPlaceholderKey($placeholder->getKey());
        if (!$customField) {
            log_error('Custom field not found by placeholder' . composeSuffix(['key' => $placeholder->getKey()]));
            return '';
        }

        if ($customField->Type === Constants\CustomField::TYPE_DATE) {
            $format = $placeholder->getOptionValue('fmt');
            return $this->getRenderer()->renderCustomField($row, $customField, $format);
        }

        return $this->getRenderer()->renderCustomField($row, $customField);
    }

    public function getRenderer(): Renderer
    {
        if ($this->renderer === null) {
            $this->renderer = Renderer::new()
                ->setConfigManager($this->getConfigManager())
                ->setLanguageId($this->getLanguageId())
                ->setSystemAccountId($this->getSystemAccountId())
                ->construct();
        }
        return $this->renderer;
    }
}
