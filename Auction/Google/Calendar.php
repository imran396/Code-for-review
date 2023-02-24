<?php
/**
 * Parse auction template and return auction details
 *
 * SAM-4191: Apply google-api-php-client
 * SAM-3087: Auctions Google Calender integration upgrade
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Boanerge Regidor
 * @package         com.swb.sam2
 * @version         SVN: $Id: Calendar.php 15390 2013-12-04 20:09:54Z SWB\nerge $
 * @since           Jan 7, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Google;

use Account;
use Exception;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_EventDateTime;
use RuntimeException;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionLandingUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Application\Url\Domain\AccountDomainDetectorCreateTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\File\Manage\LocalFileManager;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Count\LotCounterAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use InvalidArgumentException;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;

/**
 * Class Calendar
 * @package Sam\Auction\Google
 */
class Calendar extends CustomizableClass
{
    use AccountDomainDetectorCreateTrait;
    use AccountLoaderAwareTrait;
    use AuctionAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionWriteRepositoryAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use LotCounterAwareTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Update Google Calendar Event
     * @param int $editorUserId
     * @return void
     */
    public function updateEvent(int $editorUserId): void
    {
        $auction = $this->getAuction();
        if (!$auction) {
            $message = 'Auction not defined';
            log_error($message);
            throw new InvalidArgumentException($message);
        }

        try {
            $check = false;
            $currentDateSysNoTime = $this->getCurrentDateSys($auction->AccountId)->setTime(0, 0);
            if (
                $auction->isLiveOrHybrid()
                && (
                    $auction->StartClosingDate === null
                    || $auction->StartClosingDate >= $currentDateSysNoTime
                )
            ) {
                $check = true;
            }

            if (
                !$check
                && $auction->isTimed()
                && (
                    $auction->StartBiddingDate === null
                    || $auction->EndDate === null
                    || $auction->EndDate > $currentDateSysNoTime
                )
            ) {
                $check = true;
            }

            if ($check) {
                // only do this for current or future auctions; TODO: GMT conversion for correct comparison
                $start = microtime(true);
                $account = $this->getAccountLoader()->load($auction->AccountId);
                if (!$account) {
                    $message = "Available auction's account not found" . composeSuffix(['a' => $auction->Id, 'acc' => $auction->AccountId]);
                    log_error($message);
                    throw new InvalidArgumentException($message);
                }
                $portalAccount = $this->isPortalSystemAccount();
                $auctionDomainMode = $this->getSettingsManager()->getForMain(Constants\Setting::AUCTION_DOMAIN_MODE);

                if ($portalAccount) {
                    $domain = $this->getDomain($auctionDomainMode, $account);
                } else {
                    $domain = $this->cfg()->get('core->app->httpHost');
                }

                $title = '';
                $title .= $domain . " ";
                if ($auction->PublishDate === null) {
                    $title .= '* ';
                } // mark unpublished auctions with asterisk

                $title .= $auction->AuctionType;
                $title .= ($auction->StreamDisplay
                    && $auction->StreamDisplay !== 'N')
                    ? " " . $auction->StreamDisplay
                    : ''; // (A)Audio or (V)Video streaming
                $title .= ' ';
                // $title .= 'starts ' . $auction->StartDate->format('h:iA') . ' ' . $startTzCode . ' '; // on live show start time

                // $auctionEndDate = $auction->StartDate->AddDays(max($auction->Days - 1, 0))->format('Y-m-d'); //"2010-12-06"; //  <- end date, use number of days for live to determine or auction end date for timed
                // $auctionEndTime = $auction->StartDate->AddHours(1)->format("H:i:s"); // "16:00"; // <- for live: start-time +1
                $lotCount = $this->getLotCounter()
                    ->setAuction($auction)
                    ->count();

                $days = $auction->EndDate->diff($auction->detectScheduledStartDate())->format("%a");
                $title .= $days . " day " . $lotCount . " lots";

                // Produce url to auction's landing page
                $landingUrlConfig = ResponsiveAuctionLandingUrlConfig::new()->forWeb(
                    $auction->Id,
                    '',
                    [
                        UrlConfigConstants::OP_ACCOUNT_ID => $auction->AccountId,
                        UrlConfigConstants::OP_AUCTION_INFO_LINK => $auction->AuctionInfoLink,
                    ]
                );
                $auctionUrl = $this->getUrlBuilder()->build($landingUrlConfig);

                $calendarId = $this->cfg()->get('core->vendor->google->calendar->calendarId');
                if (trim($calendarId) === '') {
                    $calendarId = 'primary';
                }

                $isNew = true;
                try {
                    $client = $this->getClient();
                    $service = new Google_Service_Calendar($client);

                    if ($auction->GcalEventId) {
                        $isNew = false;
                        $event = $service->events->get($calendarId, $auction->GcalEventId);
                    } else {
                        $event = $service->events->quickAdd($calendarId, $title);
                    }
                } catch (Exception $exception) {
                    log_error('Failed to fetch event, creating new one. ' . $exception->getMessage());
                    // failed to fetch entry, create a new one
                    $client = $this->getClient();
                    $service = new Google_Service_Calendar($client);
                    $event = $service->events->quickAdd($calendarId, $title);
                }

                if (
                    !$isNew
                    && $auction->isDeletedOrArchived()
                ) {
                    if ($auction->GcalEventId) {
                        $service->events->delete($calendarId, $auction->GcalEventId);
                    }
                    // remove gcal_event_key and gcal_event_id

                    $auction = $this->getAuctionLoader()->load($auction->Id);
                    if ($auction) {
                        $auction->GcalEventKey = '';
                        $auction->GcalEventId = '';
                        $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);
                    }
                } else {
                    if (!$isNew) {
                        $event->setSummary($title);
                    }

                    $auctionStartDate = $this->getDateHelper()->convertUtcToTzById($auction->detectScheduledStartDate(), $auction->TimezoneId);
                    $auctionStartDateIso = $auctionStartDate ? $auctionStartDate->format(DATE_ATOM) : '';
                    $gCalStartDate = new Google_Service_Calendar_EventDateTime();
                    $gCalStartDate->setDateTime($auctionStartDateIso);
                    $event->setStart($gCalStartDate);

                    $auctionEndDate = $this->getDateHelper()->convertUtcToTzById($auction->EndDate, $auction->TimezoneId);
                    $auctionEndDateIso = $auctionEndDate ? $auctionEndDate->format(DATE_ATOM) : '';
                    $gCalEndDate = new Google_Service_Calendar_EventDateTime();
                    $gCalEndDate->setDateTime($auctionEndDateIso);
                    $event->setEnd($gCalEndDate);

                    $content = $event->getDescription();
                    if (!$content || !str_contains($content, $auctionUrl)) {
                        $content .= ($content ? "\n" : '') . 'Auction URL: ' . $auctionUrl;
                    }
                    $content .= ($content ? "\n" : '') . ($isNew ? 'Created' : 'Modified') . ': '
                        . $this->getCurrentDateSys($auction->AccountId)->format('m/d/Y h:i:s a');

                    $event->setDescription($content);
                    $event = $service->events->update($calendarId, $event->getId(), $event); // save changes

                    if ($isNew) {
                        // update GcalEventKey and GcalEventId of newly created `auction` database record
                        $auction->GcalEventKey = (string)$event->htmlLink;
                        $auction->GcalEventId = (string)$event->getId();
                        $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);
                    }
                }
                log_info(
                    'GCal sync' . composeSuffix(
                        [
                            'a' => $auction->Id,
                            'total time' => (microtime(true) - $start) . 's',
                        ]
                    )
                );
            }
        } catch (Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }

    /**
     * Get Google Client Api
     * @return Google_Client
     */
    public function getClient(): Google_Client
    {
        $credentialsPath = $this->cfg()->get('core->vendor->google->auth->credentials');
        log_debug(composeSuffix(['$credentialsPath' => $this->cfg()->get('core->vendor->google->auth->credentials')]));

        if ($credentialsPath === '') {
            throw new InvalidArgumentException('JSON auth file path is not set');
        }
        if (!LocalFileManager::new()->exist(substr($credentialsPath, strlen(path()->sysRoot())))) {
            throw new InvalidArgumentException('JSON auth file do not exists');
        }
        try {
            $client = new Google_Client();
            $client->setAuthConfig($this->cfg()->get('core->vendor->google->auth->credentials'));
            $client->setScopes([Google_Service_Calendar::CALENDAR]);
        } catch (Exception $exception) {
            throw new RuntimeException(self::class . '::' . __FUNCTION__ . ' ' . $exception->getMessage());
        }
        return $client;
    }

    /**
     * @param string $accountDomainMode
     * @param Account $account
     * @return string
     */
    private function getDomain(string $accountDomainMode, Account $account): string
    {
        if ($accountDomainMode === Constants\AuctionDomainMode::ALWAYS_SUB_DOMAIN) {
            $domain = $this->createAccountDomainDetector()->detectByAccount($account);
        } else {
            $domain = $this->cfg()->get('core->app->httpHost');
        }
        return $domain;
    }
}
