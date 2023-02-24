<?php
/**
 * SAM-5771 User not able to download all bids report in Auctions page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           2/6/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Report\Auction\AuctionBid\Validate;

use DateTime;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Date\Validate\DateFormatValidator;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Report\Auction\AuctionBid\CommonAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

class AuctionBidReportFilteringValidator extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use CommonAwareTrait;
    use DateHelperAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterDatePeriodAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * End date should be greater than start date
     */
    public const ERR_INCLUSIVE_DATE = 1;

    /**
     * Start date format should be valid US date
     */
    public const ERR_INVALID_START_DATE_US_FORMAT = 2;

    /**
     * End date format should be valid US date
     */
    public const ERR_INVALID_END_DATE_US_FORMAT = 3;

    /**
     * Start date format should be valid AU date
     */
    public const ERR_INVALID_START_DATE_AU_FORMAT = 4;

    /**
     * End date format should be valid AU date
     */
    public const ERR_INVALID_END_DATE_AU_FORMAT = 5;

    /**
     * Auction is not available
     */
    public const ERR_AUCTION_NOT_FOUND = 6;

    /** @var string[] */
    private static array $errorMessages = [
        self::ERR_INCLUSIVE_DATE => 'Invalid inclusive date',
        self::ERR_INVALID_START_DATE_US_FORMAT => 'Invalid input! Start date format should be MM/DD/YYYY.',
        self::ERR_INVALID_END_DATE_US_FORMAT => 'Invalid input! End date format should be MM/DD/YYYY.',
        self::ERR_INVALID_START_DATE_AU_FORMAT => 'Invalid input! Start date format should be DD-MM-YYYY.',
        self::ERR_INVALID_END_DATE_AU_FORMAT => 'Invalid input! End date format should be DD-MM-YYYY.',
        self::ERR_AUCTION_NOT_FOUND => 'Auction is not available.',
    ];

    /** Start date received from user */
    public string $startDateTxt = '';

    /** End date received from user */
    public string $endDateTxt = '';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * To initialize instance properties
     * @return static
     */
    public function initInstance(): static
    {
        $this->getResultStatusCollector()->construct(self::$errorMessages);
        return $this;
    }

    /**
     * Validate/Check BidReport params
     * @return bool
     */
    public function validate(): bool
    {
        // validate auction id:
        $auctionId = $this->getFilterAuctionId();
        if ($auctionId) {
            /**
             * Check auction availability according AuctionLoader filtering
             */
            $auction = $this->getAuctionLoader()
                ->filterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
                ->load($auctionId);
            if (!$auction) {
                $this->getResultStatusCollector()->addError(self::ERR_AUCTION_NOT_FOUND);
                return false;
            }
        }

        // validate dates:
        $withoutDate = (!$this->getStartDateTxt() && !$this->getEndDateTxt());
        if (!$withoutDate) {
            $this->addDateFormatErrors($this->getStartDateTxt(), true);
            $this->addDateFormatErrors($this->getEndDateTxt(), false);

            if (!$this->getResultStatusCollector()->hasError()) {
                // convert input date to ISO_DATE (Y-m-d):
                $adminDateFormat = (int)$this->getSettingsManager()
                    ->get(Constants\Setting::ADMIN_DATE_FORMAT, $this->getSystemAccountId());
                $startDateIso = $this->getDateHelper()
                        ->formatDateByAdminDateFormat($this->getStartDateTxt(), $adminDateFormat) . ' 00:00:00';
                $endDateIso = $this->getDateHelper()
                        ->formatDateByAdminDateFormat($this->getEndDateTxt(), $adminDateFormat) . ' 23:59:59';

                // set class' datetime values:
                $this->filterStartDateSysIso($startDateIso);
                $this->filterEndDateSysIso($endDateIso);
            }
        }

        // We should check iso dates even if they not set with StartDateTxt/EndDateTxt:
        if ($this->getFilterStartDateSysIso() && $this->getFilterEndDateSysIso()) {
            // check that end date greater than start date:
            $startDate = new DateTime($this->getFilterStartDateSysIso());
            $endDate = new DateTime($this->getFilterEndDateSysIso());
            if ($startDate->getTimestamp() > $endDate->getTimestamp()) {
                $this->getResultStatusCollector()->addError(self::ERR_INCLUSIVE_DATE);
            }
        }

        return !$this->getResultStatusCollector()->hasError();
    }

    /**
     * Return error message if passed date string is not valid or false if no error
     *
     * @param string $date
     * @param bool $start
     */
    private function addDateFormatErrors(string $date, bool $start): void
    {
        $adminDateFormat = (int)$this->getSettingsManager()
            ->get(Constants\Setting::ADMIN_DATE_FORMAT, $this->getSystemAccountId());

        if (empty($date)) {
            return;
        }

        if (
            !DateFormatValidator::new()->isUsFormatDate($date)
            && $adminDateFormat === Constants\Date::ADF_US
        ) {
            $errorCode = $start ? self::ERR_INVALID_START_DATE_US_FORMAT : self::ERR_INVALID_END_DATE_US_FORMAT;
            $this->getResultStatusCollector()->addError($errorCode);
        } elseif (
            !DateFormatValidator::new()->isEnFormatDate($date)
            && $adminDateFormat === Constants\Date::ADF_AU
        ) {
            $errorCode = $start ? self::ERR_INVALID_START_DATE_AU_FORMAT : self::ERR_INVALID_END_DATE_AU_FORMAT;
            $this->getResultStatusCollector()->addError($errorCode);
        }
    }

    /**
     * Get string with validation errors
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return string
     */
    public function getStartDateTxt(): string
    {
        return $this->startDateTxt;
    }

    /**
     * @param string $startDateTxt
     * @return static
     */
    public function setStartDateTxt(string $startDateTxt): static
    {
        $this->startDateTxt = $startDateTxt;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndDateTxt(): string
    {
        return $this->endDateTxt;
    }

    /**
     * @param string $endDateTxt
     * @return static
     */
    public function setEndDateTxt(string $endDateTxt): static
    {
        $this->endDateTxt = $endDateTxt;
        return $this;
    }
}
