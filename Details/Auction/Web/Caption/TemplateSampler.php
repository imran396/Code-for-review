<?php
/**
 * Template sample renderer
 *
 * SAM-4304: Editable template for auction pages header
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

namespace Sam\Details\Auction\Web\Caption;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Auction\Web\ConfigManagerAwareTrait;
use Sam\Details\Core\ConfigManagerAwareInterface;

/**
 * Class TemplateSampler
 * @package Sam\Details
 */
class TemplateSampler extends CustomizableClass implements ConfigManagerAwareInterface
{
    use ConfigManagerAwareTrait;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function render(): string
    {
        $plAccountCompany = Constants\AuctionDetail::PL_ACCOUNT_COMPANY;
        $plAccountEmail = Constants\AuctionDetail::PL_ACCOUNT_EMAIL;
        $plAccountImageUrl = Constants\AuctionDetail::PL_ACCOUNT_IMAGE_URL;
        $plAccountSiteUrl = Constants\AuctionDetail::PL_ACCOUNT_SITE_URL;
        $plAccountPublicSupportContactName = Constants\AuctionDetail::PL_ACCOUNT_PUBLIC_SUPPORT_CONTACT_NAME;
        $plAccountPhone = Constants\AuctionDetail::PL_ACCOUNT_PHONE;
        $plIsClosed = Constants\AuctionDetail::PL_IS_CLOSED;
        $plIsMultipleTenantInstall = Constants\AuctionDetail::PL_IS_MULTIPLE_TENANT_INSTALL;
        $plIsLiveOrHybrid = Constants\AuctionDetail::PL_IS_LIVE_OR_HYBRID;
        $plIsTimedScheduled = Constants\AuctionDetail::PL_IS_TIMED_SCHEDULED;
        $plIsTimedOngoing = Constants\AuctionDetail::PL_IS_TIMED_ONGOING;
        $plInfoUrl = Constants\AuctionDetail::PL_INFO_URL;
        $plSaleNo = Constants\AuctionDetail::PL_SALE_NO;
        $plStartDate = Constants\AuctionDetail::PL_START_DATE;
        $plStartDateTzCode = Constants\AuctionDetail::PL_START_DATE_TZ_CODE;
        $plStartsClosingDate = Constants\AuctionDetail::PL_STARTS_CLOSING_DATE;
        $plStartsClosingDateTzCode = Constants\AuctionDetail::PL_STARTS_CLOSING_DATE_TZ_CODE;
        $plEndDate = Constants\AuctionDetail::PL_END_DATE;
        $plEndDateTzCode = Constants\AuctionDetail::PL_END_DATE_TZ_CODE;
        $plLiveUrl = Constants\AuctionDetail::PL_LIVE_URL;
        $plName = Constants\AuctionDetail::PL_NAME;
        return <<<HTML
{{$plIsMultipleTenantInstall}__begin}
<div class="account-info">
{{$plAccountSiteUrl}__begin}
  <span class="account-company">
    <a href="{{$plAccountSiteUrl}}" target="_blank" rel="noopener noreferrer">
{{$plAccountSiteUrl}__end}
      <span class="account-image">
            <img src="{{$plAccountImageUrl}}" alt="{{$plAccountCompany}}" title="" class="account-img"/>
      </span>
{{$plAccountSiteUrl}__begin}
    </a>
  </span>
{{$plAccountSiteUrl}__end}
<span class="account-company">
{{$plAccountSiteUrl}__begin}
    <a href="{{$plAccountSiteUrl}}" target="_blank" rel="noopener noreferrer">
{{$plAccountSiteUrl}__end}
      {{$plAccountCompany}}
{{$plAccountSiteUrl}__begin}
    </a>
{{$plAccountSiteUrl}__end}
</span>
{{$plAccountPhone}__begin}
<span class="account-phone">
    <span class="label">{label_{$plAccountPhone}}:</span>
    <span class="value">{{$plAccountPhone}}</span>
</span>
{{$plAccountPhone}__end}
{{$plAccountEmail}__begin}
<span class="account-email">
    <span class="label">{label_{$plAccountEmail}}:</span>
    <span class="value"><a href="mailto:{email}">{{$plAccountEmail}}</a></span>
</span>
{{$plAccountEmail}__end}
{{$plAccountPublicSupportContactName}__begin}
<span class="account-support-con-name">
    <span class="label">{label_{$plAccountPublicSupportContactName}}:</span>
    <span class="value"> {{$plAccountPublicSupportContactName}} </span>
</span>
{{$plAccountPublicSupportContactName}__end}
</div>
{{$plIsMultipleTenantInstall}__end}
<div class="tle">
  <h3>
     <span class="sale-name">{{$plName}}</span>
     <span class="sale-no">(#{{$plSaleNo}})</span>
     <span class="start-end-dates">
{{$plIsLiveOrHybrid}__begin}
        {{$plStartsClosingDate}[fmt=m/d/Y g:i A]} {{$plStartsClosingDateTzCode}}
{{$plIsLiveOrHybrid}__end}
{{$plIsTimedScheduled}__begin}
        {{$plStartDate}[fmt=m/d/Y g:i A]} {{$plStartDateTzCode}} - {{$plEndDate}[fmt=m/d/Y g:i A]} {{$plEndDateTzCode}}
{{$plIsTimedScheduled}__end}
{{$plIsTimedOngoing}__begin}
        {label_{$plIsTimedOngoing}}
{{$plIsTimedOngoing}__end}
     </span>
     <span class="auction-closed">
{{$plIsClosed}__begin}
        {label_{$plIsClosed}}
{{$plIsClosed}__end}
     </span>
{{$plStartsClosingDate}__begin}
     <p>
       Starts Ending
       <span id="auc-starts-ending-date">{{$plStartsClosingDate}[fmt=m/d/Y g:i A]} {{$plStartsClosingDateTzCode}}</span>
     </p>
{{$plStartsClosingDate}__end}
  </h3>
</div>
<div class="clearfix"></div>
<a href="{{$plInfoUrl}}" class="aucinfo">{{$plInfoUrl}_url}</a>
<div class="clearfix"></div>
{{$plLiveUrl}__begin}
<a href="{live_url}" class="catalog-live-sale-link">{label_{$plLiveUrl}}</a>
<div class="clearfix"></div>
{{$plLiveUrl}__end}
HTML;
    }
}
