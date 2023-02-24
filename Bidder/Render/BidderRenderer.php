<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\Render;

use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class BidderRenderer
 * @package Sam\Bidder\Render
 */
class BidderRenderer extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use BidderNumPaddingAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return bidder info string,
     * used in winning bidder textbox (at auction lots, lot edit)
     *
     * @param int|null $winningBidderUserId null - if Entity does not have winningBidderId
     * @param int|null $auctionId null - if Entity does not have auctionId
     * @return string
     */
    public function renderFullWinningBidderInfo(?int $winningBidderUserId, ?int $auctionId): string
    {
        $user = $this->getUserLoader()->load($winningBidderUserId, true);
        if ($user) {
            $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($winningBidderUserId, true);
            $auctionBidder = $this->getAuctionBidderLoader()->load($winningBidderUserId, $auctionId, true);
            return $this->makeFullWinningBidderInfo(
                $auctionBidder->BidderNum ?? '',
                $userInfo->CompanyName,
                $user->Username,
                $userInfo->FirstName,
                $userInfo->LastName
            );
        }
        return '';
    }

    /**
     * Render bidder info string based on input data
     *
     * @param string $bidderNum
     * @param string $company
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     * @return string
     */
    public function makeFullWinningBidderInfo(
        string $bidderNum,
        string $company,
        string $username,
        string $firstName,
        string $lastName
    ): string {
        $bidderName = $username . ' ' . UserPureRenderer::new()->makeFullName($firstName, $lastName);

        $bidderNumOutput = '';
        if ($bidderNum !== '') {
            $cleanBidderNum = $this->getBidderNumberPadding()->clear($bidderNum);
            if (!$this->getBidderNumberPadding()->isNone($cleanBidderNum)) {
                $bidderNumOutput = "$cleanBidderNum - ";
            }
        }

        $companyOutput = '';
        if ($company !== '') {
            $companyOutput = " ($company)";
        }

        return $bidderNumOutput . $bidderName . $companyOutput;
    }

    /**
     * Render bidder info string without a user first and last name based on input data
     *
     * @param string $bidderNum
     * @param string $company
     * @param string $username
     * @return string
     */
    public function makeShortWinningBidderInfo(string $bidderNum, string $company, string $username): string
    {
        $paddleBidderNum = '';
        if ($bidderNum !== '') {
            $paddleBidderNum = '[' . $this->getBidderNumberPadding()->clear($bidderNum) . ']';
        }
        $companyName = '';
        if ($company !== '') {
            $companyName = ' (' . $company . ')';
        }
        $winningBidder = $paddleBidderNum . $username . $companyName;
        return $winningBidder;
    }
}
