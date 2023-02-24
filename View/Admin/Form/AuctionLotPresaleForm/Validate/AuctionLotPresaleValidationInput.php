<?php
/**
 * Validation input maker class
 *
 * SAM-8763 : Lot Absentee Bid List page - Add bid amount validation
 * https://bidpath.atlassian.net/browse/SAM-8763
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 23, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotPresaleForm\Validate;

use Sam\Core\Constants\Admin\AuctionLotPresaleFormConstants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionLotPresaleValidationInput
 * @package Sam\View\Admin\Form\AuctionLotPresaleForm\Validate
 */
class AuctionLotPresaleValidationInput extends CustomizableClass
{
    /**
     * number formatted
     */
    public readonly string $maxBid;
    public readonly string $biddingDate;
    public readonly bool $isPhoneBid;
    public readonly bool $isNewBid;
    public readonly ?int $bidUserId;
    public readonly ?int $absenteeBidId;
    public readonly int $systemAccountId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $maxBid
     * @param string $biddingDate
     * @param bool $isPhoneBid
     * @param bool $isNewBid
     * @param int|null $bidUserId
     * @param int|null $absenteeBidId
     * @param int $systemAccountId
     * @return $this
     */
    public function construct(
        string $maxBid,
        string $biddingDate,
        bool $isPhoneBid,
        bool $isNewBid,
        ?int $bidUserId,
        ?int $absenteeBidId,
        int $systemAccountId
    ): static {
        $this->maxBid = $maxBid;
        $this->biddingDate = $biddingDate;
        $this->isPhoneBid = $isPhoneBid;
        $this->isNewBid = $isNewBid;
        $this->bidUserId = $bidUserId;
        $this->absenteeBidId = $absenteeBidId;
        $this->systemAccountId = $systemAccountId;
        return $this;
    }

    /**
     * @param array $body
     * @param bool $isNewBid
     * @param int|null $absenteeBidId
     * @param int $systemAccountId
     * @return $this
     */
    public function fromPsrRequest(
        array $body,
        bool $isNewBid,
        ?int $absenteeBidId,
        int $systemAccountId
    ): static {
        $maxBid = Cast::toString($body[AuctionLotPresaleFormConstants::CID_TXT_MAX_BID] ?? '');
        $biddingDate = Cast::toString($body[AuctionLotPresaleFormConstants::CID_CAL_DATE] ?? '');
        $isPhoneBid = isset($body[AuctionLotPresaleFormConstants::CID_CHK_PHONE]);
        $bidUserId = Cast::toInt($body[AuctionLotPresaleFormConstants::CID_LST_BIDDER]);
        return $this->construct(
            $maxBid,
            $biddingDate,
            $isPhoneBid,
            $isNewBid,
            $bidUserId,
            $absenteeBidId,
            $systemAccountId
        );
    }

    /**
     * @return array
     */
    public function logData(): array
    {
        return [
            'maxBid' => $this->maxBid,
            'biddingDate' => $this->biddingDate,
            'isPhoneBid' => $this->isPhoneBid,
            'isNewBid' => $this->isPhoneBid,
            'bidUserId' => $this->bidUserId,
            'ab' => $this->absenteeBidId,
            'sys acc' => $this->systemAccountId
        ];
    }
}
