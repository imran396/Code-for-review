<?php
/**
 * Render html catalog at Rtb consoles
 *
 * SAM-10431: Refactor rtb catalog renderer for v3-7
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Mar 21, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Catalog\Clerk\Render\Base;

use AuctionLotItem;
use Sam\Application\Url\Build\Config\AuctionLot\AdminLotPresaleUrlConfig;
use Sam\Application\Url\Build\Config\User\AdminUserCreateUrlConfig;
use Sam\Application\Url\Build\Config\User\AdminUserEditUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\ClerkConsoleConstants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Lot\Render\Amount\LotAmountRendererInterface;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Catalog\Clerk\Render\Base\Internal\Load\DataProviderCreateTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperFactoryCreateTrait;
use Sam\Rtb\Group\GroupingHelperAwareTrait;
use Sam\Rtb\Load\RtbLoaderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class Renderer
 * @package Sam\Rtb\Catalog\Bidder\Base
 */
abstract class AbstractClerkCatalogRenderer extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BidderNumPaddingAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrencyLoaderAwareTrait;
    use DataProviderCreateTrait;
    use GroupingHelperAwareTrait;
    use LotAmountRendererFactoryCreateTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use RtbCommandHelperFactoryCreateTrait;
    use RtbLoaderAwareTrait;
    use SettingsManagerAwareTrait;
    use UrlBuilderAwareTrait;
    use UserLoaderAwareTrait;

    protected ?LotAmountRendererInterface $lotAmountRenderer = null;

    /**
     * @param int $auctionId
     * @param bool $isWinner
     * @param bool $isClerk
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function render(int $auctionId, bool $isWinner, bool $isClerk, bool $isReadOnlyDb = false): array
    {
        $auction = $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb);
        if (!$auction) {
            log_error(
                "Available auction not found, when rendering admin rtb catalog"
                . composeSuffix(['a' => $auctionId])
            );
            return [];
        }

        $this->getNumberFormatter()->construct($auction->AccountId);
        $this->lotAmountRenderer = $this->createLotAmountRendererFactory()->create($auction->AccountId);

        $rtbCommandHelper = $this->createRtbCommandHelperFactory()->createByAuction($auction);
        $rtbCurrent = $rtbCommandHelper->loadRtbCurrentOrCreate($auction);
        $runningAuctionLot = $this->getAuctionLotLoader()->load($rtbCurrent->LotItemId, $auctionId);

        $groupedLotItemIds = [];
        if ($rtbCurrent->LotGroup) {
            $rtbCurrentGroups = $this->getGroupingHelper()->loadGroups($auctionId, null, [$rtbCurrent->LotItemId]);
            foreach ($rtbCurrentGroups as $rtbCurrentGroup) {
                $auctionLot = $this->getAuctionLotLoader()->load($rtbCurrentGroup->LotItemId, $auctionId);
                if ($auctionLot) {
                    $groupedLotItemIds[] = $rtbCurrentGroup->LotItemId;
                }
            }
        }

        $currencySign = $this->getCurrencyLoader()->detectDefaultSign($auctionId);

        $outputs = [];
        $lotRows = $this->createDataProvider()->loadAdminCatalogData($auctionId, $isReadOnlyDb);
        foreach ($lotRows as $rowNum => $lotRow) {
            $auctionAccountId = (int)$lotRow['account_id'];
            $isBuyNowAmount = Floating::gt($lotRow['buy_now'], 0);
            $hammerPrice = Cast::toFloat($lotRow['hammer_price']);
            $highEstimate = (float)$lotRow['high_estimate'];
            $lotItemId = (int)$lotRow['lot_item_id'];
            $lotNo = $this->getLotRenderer()->makeLotNo($lotRow['lot_num'], $lotRow['lot_num_ext'], $lotRow['lot_num_prefix']);
            $lotName = $this->makeLotName($lotRow['name'], (bool)$lotRow['test_auction'], $lotNo);
            $lotStatus = (int)$lotRow['lot_status_id'];
            $lowEstimate = (float)$lotRow['low_estimate'];
            $groupId = (string)$lotRow['group_id'];
            $seoUrl = $lotRow['lot_seo_url'];
            $winnerId = (int)$lotRow['winning_bidder_id'];

            // Texts for high absentee bid value and high bidder name
            $highAbsenteeInfo = '';
            $highAbsenteeUserText = '';
            if ($lotRow['current_bidder_id'] > 0 && $lotRow['current_bidder_num']) {
                $highAbsenteeInfo = $currencySign . $this->getNumberFormatter()->formatMoney($lotRow['current_max_bid']);
                $highAbsenteeUserText = sprintf(
                    '%s (%s) - %s',
                    ee($lotRow['current_bidder_full_name']),
                    $this->getBidderNumberPadding()->clear($lotRow['current_bidder_num']),
                    ee($lotRow['current_bidder_username'])
                );
            }

            // Texts for second high absentee bid value and second high bidder name
            $secondAbsenteeInfo = '';
            $secondAbsenteeUserText = '';
            if ($lotRow['second_bidder_id'] > 0 && $lotRow['second_bidder_num']) {
                $secondAbsenteeInfo = $currencySign . $this->getNumberFormatter()->formatMoney($lotRow['second_max_bid']);
                $secondAbsenteeUserText = sprintf(
                    '%s (%s) - %s',
                    ee($lotRow['second_bidder_full_name']),
                    $this->getBidderNumberPadding()->clear($lotRow['second_bidder_num']),
                    ee($lotRow['second_bidder_username'])
                );
            }

            $absenteeInfo = $this->getAbsentee(
                $auctionId,
                $lotItemId,
                $highAbsenteeInfo,
                $highAbsenteeUserText,
                $secondAbsenteeInfo,
                $secondAbsenteeUserText
            );
            $bidderInfo = $this->getBidder($auctionId, $lotItemId, $lotStatus, $winnerId);
            $groupCheckbox = $this->getGroup(
                $runningAuctionLot,
                $lotItemId,
                $lotStatus,
                $groupedLotItemIds,
                $groupId,
                $isClerk
            );
            $statusInfo = $this->getStatus(
                $auctionId,
                $lotItemId,
                $lotStatus,
                $hammerPrice,
                $isWinner,
                $winnerId,
                $isBuyNowAmount,
                $currencySign
            );
            $style = $rowNum % 2 === 0
                ? 'style="background-color: #eef;"'
                : 'style="background-color: #cf9;"';

            $estimatesCell = '';
            if ($this->cfg()->get('core->rtb->catalog->columnEstimate')) {
                $estimatesInfo = $this->getEstimates($lowEstimate, $highEstimate, $currencySign, $auctionAccountId);
                $estimatesCell = '<td width="120px">' . $estimatesInfo . '</td>';
            }

            $outputs[$lotItemId] = <<<HTML
<tr $style data-lot_item_id="{$lotItemId}" id="lot-{$lotItemId}" data-seo_url="{$seoUrl}">
    {$groupCheckbox}
    <td width="30px" data-forpopup="1">{$lotNo}</td>
    <td width="20px" data-forpopup="1">{$groupId}</td>
    <td width="240px" data-forpopup="1">{$lotName}</td>
    {$estimatesCell}
    <td width="50px">{$absenteeInfo}</td>
    <td width="50px">{$statusInfo}</td>
    <td width="10px">{$bidderInfo}</td>
</tr>
HTML;
        }
        return $outputs;
    }

    /**
     * @param AuctionLotItem|null $runningAuctionLot
     * @param int $lotItemId
     * @param int $lotStatus
     * @param int[] $groupedLotItemIds
     * @param string $groupId
     * @param bool $isClerk
     * @return string
     */
    protected function getGroup(
        ?AuctionLotItem $runningAuctionLot,
        int $lotItemId,
        int $lotStatus,
        array $groupedLotItemIds,
        string $groupId,
        bool $isClerk
    ): string {
        $output = '';
        if (!$isClerk) {
            $alt = '';
            $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
            if (
                $groupId !== ''
                && $auctionLotStatusPureChecker->isActiveOrUnsold($lotStatus)
            ) {
                $alt = 'alt="' . $groupId . '"';
            }

            $checked = '';
            if (in_array($lotItemId, $groupedLotItemIds, true)) {
                $checked = 'checked';
            }

            $disabled = '';
            if (
                $runningAuctionLot
                && $runningAuctionLot->LotItemId === $lotItemId
            ) {
                $disabled = 'disabled';
            }

            if (count($groupedLotItemIds)) {
                $disabled = 'disabled';
            }

            $choiceControlId = sprintf(ClerkConsoleConstants::CID_CHK_CATALOG_CHOICE_TPL, $lotItemId);
            $choiceClass = ClerkConsoleConstants::CLASS_CHK_CATALOG_CHOICE;
            $checkbox = "<input type=\"checkbox\" value=\"{$lotItemId}\" name=\"{$choiceControlId}\""
                . " id=\"{$choiceControlId}\" class=\"{$choiceClass}\" {$disabled} {$checked} {$alt} />";
            $output = '<td width="10px" align="left">' . $checkbox . '</td>';
        }
        return $output;
    }

    /**
     * @param int $auctionId
     * @param int $lotItemId
     * @param string $highAbsenteeInfo
     * @param string $highAbsenteeUserText
     * @param string $secondAbsenteeInfo
     * @param string $secondAbsenteeUserText
     * @return string
     */
    protected function getAbsentee(
        int $auctionId,
        int $lotItemId,
        string $highAbsenteeInfo,
        string $highAbsenteeUserText,
        string $secondAbsenteeInfo,
        string $secondAbsenteeUserText
    ): string {
        $output = '';
        $url = $this->getUrlBuilder()->build(
            AdminLotPresaleUrlConfig::new()->forWeb($lotItemId, $auctionId)
        );
        if ($highAbsenteeInfo !== '') {
            $tooltipClass = ClerkConsoleConstants::CLASS_LNK_TOOLTIP;
            $output = <<<HTML
<a href="{$url}" target="_blank" title="{$highAbsenteeUserText}" class="{$tooltipClass}">
{$highAbsenteeInfo}
</a>
HTML;
        }
        if ($secondAbsenteeInfo !== '') {
            $classTooltip = ClerkConsoleConstants::CLASS_LNK_TOOLTIP;
            $output .= <<<HTML
<br />
<a href="{$url}" target="_blank" title="{$secondAbsenteeUserText}" class="{$classTooltip}">
{$secondAbsenteeInfo}
</a>
HTML;
        }
        return $output;
    }

    /**
     * @param float $lowEstimate
     * @param float $highEstimate
     * @param string $currencySign
     * @param int $auctionAccountId
     * @return string
     */
    protected function getEstimates(
        float $lowEstimate,
        float $highEstimate,
        string $currencySign,
        int $auctionAccountId
    ): string {
        $sm = $this->getSettingsManager();
        $output = $this->lotAmountRenderer->makeEstimates(
            $lowEstimate,
            $highEstimate,
            $currencySign,
            $sm->get(Constants\Setting::SHOW_LOW_EST, $auctionAccountId),
            $sm->get(Constants\Setting::SHOW_HIGH_EST, $auctionAccountId)
        );
        return $output;
    }

    /**
     * @param int $auctionId
     * @param int $lotItemId
     * @param int $lotStatus
     * @param float|null $hammerPrice
     * @param bool $isWinner
     * @param int|null $winnerId
     * @param bool $isBuyNow
     * @param string $currencySign
     * @return string
     */
    protected function getStatus(
        int $auctionId,
        int $lotItemId,
        int $lotStatus,
        ?float $hammerPrice,
        bool $isWinner,
        ?int $winnerId,
        bool $isBuyNow,
        string $currencySign
    ): string {
        $output = '<span class="' . sprintf(ClerkConsoleConstants::CLASS_BLK_LOT_TPL, $lotItemId) . '">%s</span>';
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        if ($auctionLotStatusPureChecker->isUnsold($lotStatus)) {
            $output = sprintf($output, 'Unsold');
        } elseif (
            $auctionLotStatusPureChecker->isAmongWonStatuses($lotStatus)
            && LotSellInfoPureChecker::new()->isHammerPrice($hammerPrice)
        ) {
            $hammerPriceText = $this->getNumberFormatter()->formatMoney($hammerPrice);
            if ($isBuyNow) {
                $hammerPriceText .= ' <a class="' . ClerkConsoleConstants::CLASS_LNK_TOOLTIP . '" title="Sold through Buy Now">(BN)</a>';
            }
            $winnerLink = '';
            if ($winnerId > 0) {
                $auctionBidder = $this->getAuctionBidderLoader()->load($winnerId, $auctionId, true);
                if ($auctionBidder) {
                    $bidderNum = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
                    if ($isWinner) {
                        $url = $this->getUrlBuilder()->build(
                            AdminUserEditUrlConfig::new()->forWeb($winnerId)
                        );
                        $winnerLink = '&nbsp;(<a href="' . $url . '" target="_blank">' . $bidderNum . '</a>)';
                    }
                }
            }
            $output = sprintf($output, $currencySign . $hammerPriceText . $winnerLink);
        } else {
            $output = sprintf($output, '');
        }
        return $output;
    }

    /**
     * @param int $auctionId
     * @param int $lotItemId
     * @param int $lotStatus
     * @param int|null $winnerUserId
     * @return string
     */
    protected function getBidder(int $auctionId, int $lotItemId, int $lotStatus, ?int $winnerUserId): string
    {
        $winnerLink = '';
        $ibidderClass = sprintf(ClerkConsoleConstants::CLASS_BLK_I_BIDDER_TPL, $lotItemId);
        $output = <<<HTML
<span class="{$ibidderClass}">%s</span>
HTML;
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        if ($auctionLotStatusPureChecker->isAmongWonStatuses($lotStatus)) {
            if ($winnerUserId) {
                $editUserUrl = $this->getUrlBuilder()->build(AdminUserEditUrlConfig::new()->forWeb($winnerUserId));
                $auctionBidder = $this->getAuctionBidderLoader()->load($winnerUserId, $auctionId, true);
                if ($auctionBidder) {
                    $bidderNum = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
                    $winnerUser = $this->getUserLoader()->load($winnerUserId);
                    if (!$winnerUser) {
                        log_error(
                            "Available winner user not found, when rendering catalog"
                            . composeSuffix(['u' => $winnerUserId, 'a' => $auctionId])
                        );
                        return '';
                    }
                    $classTooltip = ClerkConsoleConstants::CLASS_LNK_TOOLTIP;
                    $winnerLink = <<<HTML
<a href="{$editUserUrl}" target="_blank" class="{$classTooltip}" title="{$bidderNum} - {$winnerUser->Username}">&nbsp;I&nbsp;</a>
HTML;
                }
            } else {
                $createUserUrl = $this->getUrlBuilder()->build(AdminUserCreateUrlConfig::new()->forWeb());
                $classTooltip = ClerkConsoleConstants::CLASS_LNK_TOOLTIP;
                $winnerLink = <<<HTML
<a href="{$createUserUrl}" target="_blank" title=" - " class="{$classTooltip}">&nbsp;I&nbsp;</a>
HTML;
            }
        }
        $output = sprintf($output, $winnerLink);
        return $output;
    }

    /**
     * Return lot name for rendering in catalog
     * @param string $lotName lot_item.name
     * @param bool $isTestAuction auction.test_auction
     * @param string $lotFullNum full lot#
     * @return string
     */
    protected function makeLotName(string $lotName, bool $isTestAuction, string $lotFullNum): string
    {
        $lotName = $this->getLotRenderer()->makeName($lotName, $isTestAuction);
        if (mb_check_encoding($lotName, 'UTF-8') === false) {
            $lotName = substr($lotName, 0, -1);
        }
        if (mb_check_encoding($lotName, 'UTF-8') === false) {
            $lotName = "##ENC ERROR" . composeSuffix(['lot' => $lotFullNum]) . "##";
        }
        $lotName = htmlentities($lotName, ENT_COMPAT, 'UTF-8');
        return $lotName;
    }
}
