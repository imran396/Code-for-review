<?php
/**
 * Output rendering helper
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

use DateTime;
use Exception;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Date\DateHelperAwareTrait;
use Sam\Lang\TranslatorAwareTrait;

/**
 * Class Renderer
 */
class Renderer extends CustomizableClass
{
    use AuctionRendererAwareTrait;
    use OptionsAwareTrait;
    use DateHelperAwareTrait;
    use TranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $row
     * @return string
     * @throws Exception
     */
    public function render(array $row): string
    {
        $autocompleteOptions = clone $this->getOptions();
        // For unassigned auctions we need render only auction name.
        if ($this->isUnassignedAuctionId($row)) {
            $autocompleteOptions->setRenderingTemplate('{name}');
        }
        $output = $this->replacePlaceholders($autocompleteOptions->getRenderingTemplate(), $row);
        return $output;
    }

    /**
     * @param string $template
     * @param array $row
     * @return string
     * @throws Exception
     */
    protected function replacePlaceholders(string $template, array $row): string
    {
        $placeholderViews = [
            'date',
            'full_date',
            'name',
            'sale_no',
        ];
        $output = $template;
        foreach ($placeholderViews as $view) {
            $key = "{{$view}}";
            if (str_contains($output, $key)) {
                $value = '';
                if ($view === 'date') {
                    $value = $this->renderAuctionDate($row);
                } elseif ($view === 'full_date') {
                    $value = $this->renderFullAuctionDate($row);
                } elseif ($view === 'sale_no') {
                    $value = $this->getAuctionRenderer()->makeSaleNo($row['sale_num'], $row['sale_num_ext']);
                } elseif ($view === 'name') {
                    $value = $this->renderAuctionName($row);
                }
                $output = str_replace($key, $value, $output);
            }
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    private function renderAuctionName(array $row): string
    {
        $name = $this->isUnassignedAuctionId($row)
            ? Constants\AuctionListAutocomplete::UNASSIGNED_AUCTION_LABEL
            : $this->getAuctionRenderer()->makeName($row['name'], (bool)$row['test_auction']);
        $name = $this->makePlain($name);
        $length = $this->getOptions()->getNameLengthLimit();
        if ($length) {
            $name = TextTransformer::new()->cut($name, $length, '...');
        }
        return $name;
    }

    /**
     * Remove tags
     * @param string $name
     * @return string
     */
    protected function makePlain(string $name): string
    {
        $name = strip_tags(html_entity_decode($name, ENT_QUOTES, 'UTF-8'));
        return $name;
    }

    /**
     * For Live, Hybrid and Scheduled Timed Auctions it shows date without time
     * In the other cases we show "Ongoing Event"
     *
     * @param array $row
     * @return string
     * @throws Exception
     */
    private function renderAuctionDate(array $row): string
    {
        $systemAccountId = $this->getOptions()->getSystemAccountId();
        $languageId = $this->getOptions()->getLanguageId();
        $auctionType = $row['auction_type'];
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if (
            $auctionStatusPureChecker->isLiveOrHybrid($auctionType)
            || $auctionStatusPureChecker->isTimedScheduled($auctionType, (int)$row['event_type'])
        ) {
            $formattedDateOnly = $this->getDateHelper()
                ->formattedDateWithoutTime(new DateTime($row['auction_date']), $systemAccountId);
            preg_match('/^\S+/', $formattedDateOnly, $match);
            $date = (string)array_shift($match);
        } else {
            $date = $languageId
                ? $this->getTranslator()->translate('AUCTIONS_EVENT_TYPE', 'auctions', $systemAccountId, $languageId)
                : Constants\Auction::$eventTypeFullNames[Constants\Auction::ET_ONGOING];
        }
        return $date;
    }

    /**
     * Schedule Timed Auction we show a range (start date - end date) in the other cases "Ongoing Event"
     * Live\Hybrid Auction we show a date
     * @param array $row
     * @return string
     * @throws Exception
     */
    private function renderFullAuctionDate(array $row): string
    {
        $systemAccountId = $this->getOptions()->getSystemAccountId();
        $languageId = $this->getOptions()->getLanguageId();
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $auctionType = $row['auction_type'];
        $eventType = (int)$row['event_type'];
        if ($auctionStatusPureChecker->isTimedScheduled($auctionType, $eventType)) {
            $startDateFormatted = $this->getDateHelper()->formatUtcDateIso($row['start_bidding_date'], $systemAccountId, $row['timezone_location']);
            $endDateFormatted = $this->getDateHelper()->formatUtcDateIso($row['end_date'], $systemAccountId, $row['timezone_location']);
            return $startDateFormatted . ' - ' . $endDateFormatted;
        }

        if ($auctionStatusPureChecker->isTimedOngoing($auctionType, $eventType)) {
            return $languageId
                ? $this->getTranslator()->translate('AUCTIONS_EVENT_TYPE', 'auctions', $systemAccountId, $languageId)
                : Constants\Auction::$eventTypeFullNames[Constants\Auction::ET_ONGOING];
        }

        if ($auctionStatusPureChecker->isLiveOrHybrid($auctionType)) {
            return $this->getDateHelper()->formatUtcDateIso($row['start_closing_date'], $systemAccountId, $row['timezone_location']);
        }

        return '';
    }

    /**
     * Check auction id and return true if it's unassigned auction id.
     * @param array $row
     * @return bool
     */
    private function isUnassignedAuctionId(array $row): bool
    {
        return (int)$row['id'] === Constants\AuctionListAutocomplete::UNASSIGNED_AUCTION_ID;
    }
}
