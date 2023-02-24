<?php
/**
 * SAM-6393: Auctioneers Screen - allow to show State/Province & Country of current bidder
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Response\Concrete;

use Sam\Core\Address\Render\AddressRenderer;
use Sam\Core\Service\CustomizableClass;
use RtbCurrent;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class BidderAddressDataProducer
 * @package Sam\Rtb\Command\Response\Concrete
 */
class BidderAddressDataProducer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use HighBidDetectorCreateTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @param int $userType
     * @param array $optionals = [
     *  'highBidUserId' => int,
     * ]
     * @return array = [
     *  Constants\Rtb::RES_CURRENT_BIDDER_ADDRESS => string,
     * ]
     */
    public function produceData(RtbCurrent $rtbCurrent, int $userType, array $optionals = []): array
    {
        $data[Constants\Rtb::RES_CURRENT_BIDDER_ADDRESS] = '';
        $isEnabled = (
                $userType === Constants\Rtb::UT_CLERK
                && $this->cfg()->get('core->rtb->clerk->bidder->address->enabled')
            ) || (
                $userType === Constants\Rtb::UT_AUCTIONEER
                && $this->cfg()->get('core->rtb->auctioneer->bidder->address->enabled')
            );
        if (
            !$isEnabled
            || !$rtbCurrent->LotItemId
            || !$rtbCurrent->AuctionId
        ) {
            return $data;
        }

        $highBidUserId = (int)($optionals['highBidUserId']
            ?? $this->createHighBidDetector()->detectUserId($rtbCurrent->LotItemId, $rtbCurrent->AuctionId));
        if (!$highBidUserId) {
            return $data;
        }

        $takeFrom = $userType === Constants\Rtb::UT_CLERK
            ? $this->cfg()->get('core->rtb->clerk->bidder->address->takeFrom')
            : $this->cfg()->get('core->rtb->auctioneer->bidder->address->takeFrom');

        $bidderAddress = '';
        if ($takeFrom === Constants\Rtb::ABA_BILLING) {
            $userBilling = $this->getUserLoader()->loadUserBilling($highBidUserId, true);
            $bidderAddress = $userBilling
                ? $this->makeBidderAddress($userBilling->Country, $userBilling->State)
                : '';
        } elseif ($takeFrom === Constants\Rtb::ABA_SHIPPING) {
            $userShipping = $this->getUserLoader()->loadUserShipping($highBidUserId, true);
            $bidderAddress = $userShipping
                ? $this->makeBidderAddress($userShipping->Country, $userShipping->State)
                : '';
        }

        $data[Constants\Rtb::RES_CURRENT_BIDDER_ADDRESS] = $bidderAddress;
        return $data;
    }

    /**
     * Make billing address from UserBilling or UserShipping
     * @param string $country
     * @param string $state
     * @return string
     */
    protected function makeBidderAddress(string $country, string $state): string
    {
        $address = [];
        if ($country) {
            $address [] = AddressRenderer::new()->countryName($country);
        }
        if ($country && $state) {
            $address [] = AddressRenderer::new()->stateName($state, $country);
        }
        return implode(', ', $address);
    }
}
