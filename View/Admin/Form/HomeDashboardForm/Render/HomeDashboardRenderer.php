<?php
/**
 * Home Dashboard Renderer
 *
 * SAM-5891: Admin - Dashboard - lazy loading
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 19, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\View\Admin\Form\HomeDashboardForm\Render;

use DateTime;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterCurrencyAwareTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\HomeDashboardForm\Load\HomeDashboardAuctionDto;
use Sam\View\Admin\Form\HomeDashboardForm\Load\HomeDashboardDataLoaderCreateTrait;

/**
 * Class HomeDashboardRenderer
 * @package Sam\View\Admin\Form\HomeDashboardForm\Render
 */
class HomeDashboardRenderer extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use ApplicationAccessCheckerCreateTrait;
    use AuctionRendererAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use EditorUserAwareTrait;
    use FilterAccountAwareTrait;
    use FilterCurrencyAwareTrait;
    use HomeDashboardDataLoaderCreateTrait;
    use NumberFormatterAwareTrait;
    use SystemAccountAwareTrait;
    use UrlBuilderAwareTrait;

    protected string $invoiceStartDate = '';
    protected string $invoiceEndDate = '';
    protected string $settlementStartDate = '';
    protected string $settlementEndDate = '';
    protected ?int $invoiceAuctionId = null;
    protected ?int $settlementAuctionId = null;
    protected ?int $timezoneId = null;
    protected ?int $viewPort = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $viewPort
     * @return static
     */
    public function setViewPort(int $viewPort): static
    {
        $this->viewPort = Cast::toInt($viewPort, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getViewPort(): ?int
    {
        return $this->viewPort;
    }

    /**
     * @param string $startDate
     * @return static
     */
    public function setInvoiceStartDate(string $startDate): static
    {
        $this->invoiceStartDate = $startDate;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getInvoiceStartDateUtc(): DateTime
    {
        $invoiceStartDateUtc = $this->getDateHelper()->convertToUtcByTzId(new DateTime($this->invoiceStartDate), $this->getTimezoneId());
        return $invoiceStartDateUtc;
    }

    /**
     * @param string $endDate
     * @return static
     */
    public function setInvoiceEndDate(string $endDate): static
    {
        $this->invoiceEndDate = $endDate;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getInvoiceEndDateUtc(): DateTime
    {
        $invoiceEndDateUtc = $this->getDateHelper()->convertToUtcByTzId(new DateTime($this->invoiceEndDate), $this->getTimezoneId());
        return $invoiceEndDateUtc;
    }

    /**
     * @param string $startDate
     * @return static
     */
    public function setSettlementStartDate(string $startDate): static
    {
        $this->settlementStartDate = $startDate;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getSettlementStartDateUtc(): DateTime
    {
        $settlementStartDateUtc = $this->getDateHelper()->convertToUtcByTzId(new DateTime($this->settlementStartDate), $this->getTimezoneId());
        return $settlementStartDateUtc;
    }

    /**
     * @param string $endDate
     * @return static
     */
    public function setSettlementEndDate(string $endDate): static
    {
        $this->settlementEndDate = $endDate;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getSettlementEndDateUtc(): DateTime
    {
        $settlementEndDateUtc = $this->getDateHelper()->convertToUtcByTzId(new DateTime($this->settlementEndDate), $this->getTimezoneId());
        return $settlementEndDateUtc;
    }

    /**
     * @param int $timezoneId
     * @return static
     */
    public function setTimezoneId(int $timezoneId): static
    {
        $this->timezoneId = $timezoneId;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimezoneId(): int
    {
        return $this->timezoneId;
    }

    /**
     * @param int|null $auctionId null means that there is no auction id passed
     * @return static
     */
    public function setInvoiceAuctionId(?int $auctionId): static
    {
        $this->invoiceAuctionId = Cast::toInt($auctionId, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getInvoiceAuctionId(): ?int
    {
        return $this->invoiceAuctionId;
    }

    /**
     * @param int|null $auctionId null means that there is no auction id passed
     * @return static
     */
    public function setSettlementAuctionId(?int $auctionId): static
    {
        $this->settlementAuctionId = Cast::toInt($auctionId, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSettlementAuctionId(): ?int
    {
        return $this->settlementAuctionId;
    }

    /**
     * @return string
     */
    public function renderActiveAuctions(): string
    {
        $activeAuctionDto = $this->createHomeDashboardDataLoader()
            ->filterAccountId($this->getFilterAccountId())
            ->filterCurrencyId($this->getFilterCurrencyId())
            ->setEditorUserId($this->getEditorUserId())
            ->setOffset($this->getViewPort())
            ->setLimit(1)
            ->loadActiveAuctionValues(true);
        if (!$activeAuctionDto) {
            return '';
        }

        $output = '';
        $currencySign = $this->getFilterCurrencySign();
        $numberFormatter = $this->getNumberFormatter();
        $auctionGeneralTotalValues = $this->calcAuctionGeneralTotalValues($activeAuctionDto, $currencySign);
        [
            $totalFees,
            $paid,
            $totalComm,
            $totalRevenue,
            $totalRevenuePaid,
            $revenue,
        ] = $auctionGeneralTotalValues;

        $lotsWithBidsPercent = 0;
        $lotsAboveHighEstimatePercent = 0;
        $lotsSoldPercent = 0;
        $lotsWithBids = $lotsAboveHighEstimate = $lotsSold = '';
        if ($activeAuctionDto->totalLots > 0) {
            $lotsWithBidsPercent = round($activeAuctionDto->lotsWithBids * 100 / $activeAuctionDto->totalLots, 1);
            $lotsAboveHighEstimatePercent = round($activeAuctionDto->lotsAboveHighEstimate * 100 / $activeAuctionDto->totalLots, 1);
            $lotsSoldPercent = round($activeAuctionDto->lotsSold * 100 / $activeAuctionDto->totalLots, 1);
            $lotsWithBids = $activeAuctionDto->lotsWithBids . ' lot' . ($activeAuctionDto->lotsWithBids === 1 ? '' : 's');
            $lotsAboveHighEstimate = $activeAuctionDto->lotsAboveHighEstimate . ' lot'
                . ($activeAuctionDto->lotsAboveHighEstimate === 1 ? '' : 's');
            $lotsSold = $activeAuctionDto->lotsSold . ' lot' . ($activeAuctionDto->lotsSold === 1 ? '' : 's');
        }
        $lotsSoldPercentFormatted = Floating::gt($lotsSoldPercent, 0)
            ? ', ' . $numberFormatter->format($lotsSoldPercent, 1) . '% sold' : '';
        $lots = $numberFormatter->formatMoney($activeAuctionDto->totalLots) .
            ' (<span title="' . $lotsWithBids . '">' . $numberFormatter->format($lotsWithBidsPercent, 1) . '% with bids</span>, ' .
            '<span title="' . $lotsAboveHighEstimate . '">' . $numberFormatter->format($lotsAboveHighEstimatePercent, 1) . '% above high est.</span>' .
            '<span title="' . $lotsSold . '">' . $lotsSoldPercentFormatted . '</span>)';
        $views = $numberFormatter->formatInteger($activeAuctionDto->totalViews);
        $totalWinBid = $activeAuctionDto->totalBid;
        $currentBidTotal = $currencySign . $numberFormatter->formatMoney($totalWinBid)
            . ' (' . $currencySign . $numberFormatter->formatMoney($activeAuctionDto->totalReserveMet) . ' res. met, '
            . $numberFormatter->formatInteger($activeAuctionDto->bids) . ' bid' . ($activeAuctionDto->bids === 1 ? '' : 's') . ')';
        $estimatedTotal = $currencySign . $numberFormatter->formatMoney($activeAuctionDto->totalLowEstimate) . ' - '
            . $currencySign . $numberFormatter->formatMoney($activeAuctionDto->totalHighEstimate);
        $hammerPriceTotal = $currencySign . $numberFormatter->formatMoney($activeAuctionDto->totalHammerPrice)
            . ' (' . $currencySign . $numberFormatter->formatMoney($activeAuctionDto->totalHammerPriceInternet) . ' online)';
        $revenueTotal = $currencySign . $numberFormatter->formatMoney($activeAuctionDto->totalBuyersPremium) . '+'
            . $currencySign . $numberFormatter->formatMoney($totalFees) . '+'
            . $currencySign . $numberFormatter->formatMoney($totalComm) . ' = '
            . $currencySign . $numberFormatter->formatMoney($totalRevenue)
            . ' (' . $currencySign . $numberFormatter->formatMoney($totalRevenuePaid) . ' Paid)';

        $values = [];
        $values['Current Bid Total'] = $currentBidTotal;
        $values['Estimated Total'] = $estimatedTotal;
        $values['Hammer Price Total'] = $hammerPriceTotal;
        $values[$revenue] = $revenueTotal;
        $values['Paid (HP/BP/Tax/Fees)'] = $paid;
        $bidders = $activeAuctionDto->bidders . ' (' . $activeAuctionDto->biddersApproved . ' approved, '
            . $activeAuctionDto->biddersBidding . ' bidding)';
        $values['Bidders'] = $bidders;
        $values['Lots'] = $lots;
        $values['Views'] = $views;
        $values['Bids'] = $activeAuctionDto->maxBidCount;

        $saleNo = $this->getAuctionRenderer()->makeSaleNo($activeAuctionDto->saleNum, $activeAuctionDto->saleNumExt);
        $auctionTitle = ee($activeAuctionDto->name) . " (#{$saleNo})";
        $closeDate = '';
        if ($activeAuctionDto->auctionEndDate) {
            $tzLocation = $activeAuctionDto->timezoneLocation;
            $closeDate = $this->getDateHelper()->formatUtcDateIso($activeAuctionDto->auctionEndDate, $this->getSystemAccountId(), $tzLocation);
        }
        $timeLeft = $this->prepareTimeLeftRenderingForLiveAuctions($activeAuctionDto);

        // assemble result html
        $output .= $this->isAccountFiltering()
            ? $this->renderAccountName($activeAuctionDto->accountId)
            : '';
        $output .= $this->renderAuctionTitle($activeAuctionDto->id, $auctionTitle, $closeDate . ' ' . $timeLeft);
        $lineTpl = '<div class="line-block %s">' .
            '<div class="fleft line-name">%s:</div>' .
            '<div class="fleft line-value">%s</div></div>';
        foreach ($values as $name => $value) {
            $class = str_replace(' ', '-', strtolower($name));
            $output .= sprintf($lineTpl, $class, $name, $value);
        }

        // prepare templates for links
        $generalAuctionLinkTemplates = $this->buildGeneralAuctionLinkTemplates($activeAuctionDto);
        [$editInfoLink, $manageLotsLink, $manageBiddersLink] = $generalAuctionLinkTemplates;
        $url = $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_RUN, $activeAuctionDto->id)
        );
        $runLiveAuctionLink = '<a class="runlivelink"  title="Run live auction" href="' . $url . '">%s</a>';

        $output .= '<div class="action first">' . sprintf($editInfoLink, 'Edit info') . '</div>';
        $output .= '<div class="action">' . sprintf($manageLotsLink, 'Manage lots') . '</div>';
        $output .= '<div class="action">' . sprintf($manageBiddersLink, 'Manage bidders') . '</div>';

        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $auctionType = $activeAuctionDto->auctionType;
        if ($auctionStatusPureChecker->isLive($auctionType)) {
            $output .= '<div class="action">' . sprintf($runLiveAuctionLink, 'Run live auction') . '</div>';
        } elseif ($auctionStatusPureChecker->isHybrid($auctionType)) {
            $output .= '<div class="action">' . sprintf($runLiveAuctionLink, 'Manual clerking') . '</div>';
        }
        $output .= '<div class="clear auction_bot_border"></div>';
        return $output;
    }

    /**
     * @return string
     */
    public function renderClosedAuctions(): string
    {
        $closedAuctionDto = $this->createHomeDashboardDataLoader()
            ->filterAccountId($this->getFilterAccountId())
            ->filterCurrencyId($this->getFilterCurrencyId())
            ->setEditorUserId($this->getEditorUserId())
            ->setOffset($this->getViewPort())
            ->setLimit(1)
            ->loadClosedAuctionValues(true);
        if (!$closedAuctionDto) {
            return '';
        }

        $output = '';
        $currencySign = $this->getFilterCurrencySign();
        $auctionGeneralTotalValues = $this->calcAuctionGeneralTotalValues($closedAuctionDto, $currencySign);
        [
            $totalFees,
            $paid,
            $totalComm,
            $totalRevenue,
            $totalRevenuePaid,
            $revenue,
        ] = $auctionGeneralTotalValues;

        $lotsWithBidsPercent = 0;
        $lotsSoldPercent = 0;
        $lotsWithBids = $lotsSold = '';
        if ($closedAuctionDto->totalLots > 0) {
            $lotsWithBidsPercent = round($closedAuctionDto->lotsWithBids * 100 / $closedAuctionDto->totalLots, 1);
            $lotsSoldPercent = round($closedAuctionDto->lotsSold * 100 / $closedAuctionDto->totalLots, 1);
            $lotsWithBids = $closedAuctionDto->lotsWithBids . ' lot' . ($closedAuctionDto->lotsWithBids === 1 ? '' : 's');
            $lotsSold = $closedAuctionDto->lotsSold . ' lot' . ($closedAuctionDto->lotsSold === 1 ? '' : 's');
        }
        $lots = $closedAuctionDto->totalLots . ' (<span title="' . $lotsWithBids . '">'
            . $this->getNumberFormatter()->format($lotsWithBidsPercent, 1) . '% with bids</span>, '
            . '<span title="' . $lotsSold . '">' . $this->getNumberFormatter()->format($lotsSoldPercent, 1) . '% sold</span>)';
        $views = $this->getNumberFormatter()->formatInteger($closedAuctionDto->totalViews);
        $bids = $closedAuctionDto->maxBidCount;
        $bidders = $closedAuctionDto->bidders . ' (' . $closedAuctionDto->biddersApproved . ' approved, '
            . $closedAuctionDto->biddersBidding . ' bidding, ' . $closedAuctionDto->biddersWinning . ' winning)';
        $hammerPriceTotal = $currencySign . $this->getNumberFormatter()->formatMoney($closedAuctionDto->totalHammerPrice)
            . ' (' . $currencySign . $this->getNumberFormatter()->formatMoney($closedAuctionDto->totalHammerPriceInternet) . ' online)';
        $estimatedTotal = $currencySign . $this->getNumberFormatter()->formatMoney($closedAuctionDto->totalLowEstimate) . ' - '
            . $currencySign . $this->getNumberFormatter()->formatMoney($closedAuctionDto->totalHighEstimate);
        $totalBP = $currencySign . $this->getNumberFormatter()->formatMoney($closedAuctionDto->totalBuyersPremium);
        $revenueTotal = $totalBP . '+'
            . $currencySign . $this->getNumberFormatter()->formatMoney($totalFees) . '+'
            . $currencySign . $this->getNumberFormatter()->formatMoney($totalComm) . ' = '
            . $currencySign . $this->getNumberFormatter()->formatMoney($totalRevenue)
            . ' (' . $currencySign . $this->getNumberFormatter()->formatMoney($totalRevenuePaid) . ' Paid)';
        $values = [
            'Hammer Price Total' => $hammerPriceTotal,
            $revenue => $revenueTotal,
            'Estimated Total' => $estimatedTotal,
            'Paid (HP/BP/Tax/Fees)' => $paid,
            'Bidders' => $bidders,
            'Lots' => $lots,
            'Views' => $views,
            'Bids' => $bids,
        ];

        $lineTpl = '<div class="line-block">'
            . '<div class="fleft line-name">%s:</div>'
            . '<div class="fleft line-value">%s</div></div>';
        $output .= $this->isAccountFiltering()
            ? $this->renderAccountName($closedAuctionDto->accountId)
            : '';

        $saleNo = $this->getAuctionRenderer()->makeSaleNo($closedAuctionDto->saleNum, $closedAuctionDto->saleNumExt);
        $auctionTitle = ee($closedAuctionDto->name) . " (#{$saleNo})";
        if ($closedAuctionDto->endDate) {
            $tzLocation = $closedAuctionDto->timezoneLocation;
            $endDateIso = $this->getDateHelper()->formatUtcDateIso($closedAuctionDto->endDate, $this->getSystemAccountId(), $tzLocation);
            $closeDateOutput = '(closed ' . $endDateIso . ')';
        } else {
            $closeDateOutput = '';
        }
        $output .= $this->renderAuctionTitle($closedAuctionDto->id, $auctionTitle, $closeDateOutput);

        foreach ($values as $name => $value) {
            $output .= sprintf($lineTpl, $name, $value);
        }

        // prepare templates for links
        $generalAuctionLinkTemplates = $this->buildGeneralAuctionLinkTemplates($closedAuctionDto);
        [$editInfoLink, $manageLotsLink, $manageBiddersLink] = $generalAuctionLinkTemplates;
        $output .= '<div class="action first">' . sprintf($editInfoLink, 'Edit info') . '</div>';
        $output .= '<div class="action">' . sprintf($manageLotsLink, 'Manage lots') . '</div>';
        $output .= '<div class="action">' . sprintf($manageBiddersLink, 'Manage bidders') . '</div>';
        $output .= '<div class="clear auction_bot_border"></div>';
        return $output;
    }

    /**
     * @return string
     */
    public function renderInvoiceOverview(): string
    {
        $output = '';
        $startDateUtc = $this->getInvoiceStartDateUtc();
        $endDateUtc = $this->getInvoiceEndDateUtc();

        $dto = $this->createHomeDashboardDataLoader()
            ->filterAccountId($this->getFilterAccountId())
            ->filterCurrencyId($this->getFilterCurrencyId())
            ->setEditorUser($this->getEditorUser())
            ->loadInvoiceOverviewValues($this->getInvoiceAuctionId(), $startDateUtc, $endDateUtc, true);
        $currencySign = $this->getFilterCurrencySign();
        $numberFormatter = $this->getNumberFormatter();
        $highBidTotal = $currencySign . $numberFormatter->formatMoney($dto->totalHighBid)
            . ' (' . $currencySign . $numberFormatter->formatMoney($dto->totalHighHidAboveReserve) . ' above reserve)';
        $hammerPriceTotal = $currencySign . $numberFormatter->formatMoney($dto->totalHp)
            . ' (' . $currencySign . $numberFormatter->formatMoney($dto->totalHpCollected) . ' collected)';
        $bpTotal = $currencySign . $numberFormatter->formatMoney($dto->totalBp)
            . ' (' . $currencySign . $numberFormatter->formatMoney($dto->totalBpCollected) . ' collected)';
        $taxTotal = $currencySign . $numberFormatter->formatMoney($dto->totalTax)
            . ' (' . $currencySign . $numberFormatter->formatMoney($dto->totalTaxCollected) . ' collected)';
        $feeTotal = $currencySign . $numberFormatter->formatMoney($dto->totalFees)
            . ' (' . $currencySign . $numberFormatter->formatMoney($dto->totalFeesCollected) . ' collected)';
        $values = [
            'High Bid Total' => $highBidTotal,
            'Hammer Price Total' => $hammerPriceTotal,
            'BP Total' => $bpTotal,
            'Tax Total' => $taxTotal,
            'Fee Total' => $feeTotal
        ];
        $lineTpl = '<div class="line-block">'
            . '<div class="fleft line-name">%s:</div>'
            . '<div class="fleft line-value">%s</div></div>';
        foreach ($values as $name => $value) {
            $output .= sprintf($lineTpl, $name, $value);
        }
        $output .= '<div class="clear"></div>';
        $output .= '<div class="overall-line"></div>';
        $overallTotal = $dto->totalHp + $dto->totalBp + $dto->totalTax + $dto->totalFees;
        $overallTotalCollected = $dto->totalHpCollected + $dto->totalBpCollected + $dto->totalTaxCollected + $dto->totalFeesCollected;
        $overallTotalFormatted = $currencySign . $numberFormatter->formatMoney($overallTotal)
            . ' (' . $currencySign . $numberFormatter->formatMoney($overallTotalCollected) . ' collected)';
        $output .= sprintf($lineTpl, 'Overall Total', $overallTotalFormatted);
        return $output;
    }

    /**
     * @return string
     */
    public function renderSettlementOverview(): string
    {
        $output = '';
        $dateStartUtc = $this->getSettlementStartDateUtc();
        $dateEndUtc = $this->getSettlementEndDateUtc();

        $overviewValues = $this->createHomeDashboardDataLoader()
            ->filterAccountId($this->getFilterAccountId())
            ->filterCurrencyId($this->getFilterCurrencyId())
            ->setEditorUser($this->getEditorUser())
            ->loadSettlementOverviewValues($this->getSettlementAuctionId(), $dateStartUtc, $dateEndUtc, true);
        $currencySign = $this->getFilterCurrencySign();
        $numberFormatter = $this->getNumberFormatter();
        $hammerPriceTotal = $currencySign . $numberFormatter->formatMoney($overviewValues->totalHp)
            . ' (' . $currencySign . $numberFormatter->formatMoney($overviewValues->totalHpSettled) . ' settled)';
        $commissionTotal = $currencySign . $numberFormatter->formatMoney($overviewValues->totalCommission)
            . ' (' . $currencySign . $numberFormatter->formatMoney($overviewValues->totalCommissionSettled) . ' settled)';
        $taxTotal = $currencySign . $numberFormatter->formatMoney($overviewValues->totalTax)
            . ' (' . $currencySign . $numberFormatter->formatMoney($overviewValues->totalTaxSettled) . ' settled)';
        $feeTotal = $currencySign . $numberFormatter->formatMoney($overviewValues->totalFee)
            . ' (' . $currencySign . $numberFormatter->formatMoney($overviewValues->totalFeeSettled) . ' settled)';
        $values = [
            'Hammer Price Total' => $hammerPriceTotal,
            'Commission Total' => $commissionTotal,
            'Tax Total' => $taxTotal,
            'Fee Total' => $feeTotal
        ];
        $lineTpl = '<div class="line-block">' .
            '<div class="fleft line-name">%s:</div>' .
            '<div class="fleft line-value">%s</div></div>';
        foreach ($values as $name => $value) {
            $output .= sprintf($lineTpl, $name, $value);
        }
        $output .= '<div class="clear"></div>';
        $output .= '<div class="overall-line"></div>';
        $overallTotal = $overviewValues->totalCommission + $overviewValues->totalTax + $overviewValues->totalFee;
        $overallTotalSettled = $overviewValues->totalCommissionSettled
            + $overviewValues->totalTaxSettled + $overviewValues->totalFeeSettled;
        $overallTotalFormatted = $currencySign . $numberFormatter->formatMoney($overallTotal)
            . ' (' . $currencySign . $numberFormatter->formatMoney($overallTotalSettled) . ' settled)';
        $output .= sprintf($lineTpl, 'Total Commissions, Tax & Fees', $overallTotalFormatted);
        return $output;
    }

    /**
     * @param int $accountId
     * @return string
     */
    protected function renderAccountName(int $accountId): string
    {
        $account = $this->getAccountLoader()->load($accountId);
        $accountName = $account->Name ?? '';
        $output = '<div class="fleft auction-account-name">' . ee($accountName) . '</div>';
        $output .= '<div class="clear"></div>';
        return $output;
    }

    /**
     * @param int $auctionId
     * @param string $title
     * @param string $subtitle
     * @return string
     */
    protected function renderAuctionTitle(int $auctionId, string $title, string $subtitle): string
    {
        $url = $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_LOT_LIST, $auctionId)
        );
        $manageLotsLink = '<a href="' . $url . '">%s</a>';
        $output = sprintf($manageLotsLink, '<div class="fleft auction-title">' . $title . '</div>');
        $output .= '<div class="clear"></div>';
        $output .= sprintf($manageLotsLink, '<div class="fleft auction-subtitle">' . $subtitle . '</div>');
        $output .= '<div class="clear"></div>';
        return $output;
    }

    /**
     * @param HomeDashboardAuctionDto $auctionDto
     * @return array
     */
    private function buildGeneralAuctionLinkTemplates(HomeDashboardAuctionDto $auctionDto): array
    {
        $urlBuilder = $this->getUrlBuilder();
        $auctionId = $auctionDto->id;
        $url = $urlBuilder->build(
            AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_EDIT, $auctionId)
        );
        $editInfoLink = '<a class="editlink"  title="Edit info" href="' . $url . '">%s</a>';
        $url = $urlBuilder->build(
            AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_LOT_LIST, $auctionId)
        );
        $manageLotsLink = '<a class="lotslink"  title="Manage lots" href="' . $url . '">%s</a>';
        $url = $urlBuilder->build(
            AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_BIDDER_LIST, $auctionId)
        );
        $manageBiddersLink = '<a class="bidderslink"  title="Manage bidders" href="' . $url . '">%s</a>';
        $output = [$editInfoLink, $manageLotsLink, $manageBiddersLink];
        return $output;
    }

    /**
     * @param HomeDashboardAuctionDto $auctionDto
     * @param string $currencySign
     * @return array
     */
    private function calcAuctionGeneralTotalValues(HomeDashboardAuctionDto $auctionDto, string $currencySign): array
    {
        $totalFees = $auctionDto->totalFees;
        $totalFeesPaid = $auctionDto->totalPaidFees;
        $numberFormatter = $this->getNumberFormatter();

        $paid = $currencySign . $numberFormatter->formatMoney($auctionDto->totalPaidHammerPrice) . '/' .
            $currencySign . $numberFormatter->formatMoney($auctionDto->totalPaidBuyersPremium) . '/' .
            $currencySign . $numberFormatter->formatMoney($auctionDto->totalPaidTax) . '/' .
            $currencySign . $numberFormatter->formatMoney($totalFeesPaid);

        $totalComm = $auctionDto->totalCommission + $auctionDto->totalSettlementFee;
        $totalCommPaid = $auctionDto->totalCommissionSettled + $auctionDto->totalSettlementFeeSettled;

        $totalRevenue = $auctionDto->totalBuyersPremium
            + $totalFees
            + $totalComm;
        $totalRevenuePaid = $auctionDto->totalPaidBuyersPremium
            + $totalFeesPaid
            + $totalCommPaid;
        $revenue = 'Revenue(BP+Fees+Comm.)';

        $output = [
            $totalFees,
            $paid,
            $totalComm,
            $totalRevenue,
            $totalRevenuePaid,
            $revenue,
        ];
        return $output;
    }

    /**
     * @param HomeDashboardAuctionDto $activeAuctionDto
     * @return string
     */
    private function prepareTimeLeftRenderingForLiveAuctions(HomeDashboardAuctionDto $activeAuctionDto): string
    {
        $auctionType = $activeAuctionDto->auctionType;
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if (
            $auctionStatusPureChecker->isLiveOrHybrid($auctionType)
            && $auctionStatusPureChecker->isStarted($activeAuctionDto->auctionStatusId)
        ) {
            $timeLeft = '(Live now!)';
        } elseif ($auctionStatusPureChecker->isTimedOngoing($auctionType, $activeAuctionDto->eventType)) {
            $timeLeft = '(Ongoing)';
        } else {
            $dataTimeEndDate = $auctionStatusPureChecker->isTimed($auctionType)
                ? $activeAuctionDto->endDate
                : $activeAuctionDto->startClosingDate;
            $endDate = new DateTime($dataTimeEndDate);
            $nowDate = $this->getCurrentDateUtc();
            $secondsLeft = $endDate->getTimestamp() - $nowDate->getTimestamp();
            $timeLeft = '';
            if ($secondsLeft > 0) {
                $days = floor($secondsLeft / (60 * 60 * 24));
                $daysText = $days ? $days . 'd ' : '';
                $secondsLeft -= $days * (60 * 60 * 24);
                $hours = floor($secondsLeft / 3600);
                $hoursText = $hours ? $hours . 'h ' : '';
                $secondsLeft -= $hours * 3600;
                $minutes = floor($secondsLeft / 60);
                $minutesText = $minutes . 'm';
                $timeLeft = '(closes in ' . $daysText . $hoursText . $minutesText . ')';
            }
        }
        return $timeLeft;
    }

    /**
     * @return bool
     */
    public function isAccountFiltering(): bool
    {
        return $this->createApplicationAccessChecker()->isCrossDomainAdminOnMainAccountForMultipleTenant(
            $this->getEditorUserId(),
            $this->getSystemAccountId(),
            true
        );
    }
}
