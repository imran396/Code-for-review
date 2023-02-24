<?php
/**
 * SAM-6181 : Refactor for Admin>Auction>Enter bids - Move input validation logic to separate class and implement unit test
 * https://bidpath.atlassian.net/browse/SAM-6181
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01/25/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionEnterBidForm\EnterBid\Validate;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Core\Constants\Admin\AuctionEnterBidFormConstants;

/**
 * Class Dto
 * @package Sam\View\Admin\Form\AuctionEnterBidForm\Validate
 */
class AuctionEnterBidInputDto extends CustomizableClass
{
    use NumberFormatterAwareTrait;

    // --- Incoming values ---

    public string $lotFullNo;
    public string $lotNum;
    public string $lotNumExt;
    public string $lotNumPrefix;
    public string $amount;
    public string $bidderNum;
    public int $editorUserId;

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $lotFullNo
     * @param string $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param string $amount
     * @param string $bidderNum
     * @param int $editorUserId
     * @return $this
     */
    public function construct(
        string $lotFullNo,
        string $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        string $amount,
        string $bidderNum,
        int $editorUserId
    ): static {
        $this->lotFullNo = $lotFullNo;
        $this->lotNum = $lotNum;
        $this->lotNumExt = $lotNumExt;
        $this->lotNumPrefix = $lotNumPrefix;
        $this->amount = $amount;
        $this->bidderNum = $bidderNum;
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * @param array $body
     * @param int $editorUserId
     * @return $this
     */
    public function fromPsrRequest(array $body, int $editorUserId): static
    {
        $lotFullNo = Cast::toString($body[AuctionEnterBidFormConstants::CID_TXT_LOT_FULL_NUM] ?? '');
        $lotNum = Cast::toString($body[AuctionEnterBidFormConstants::CID_TXT_LOT_NUM] ?? '');
        $lotNumExt = Cast::toString($body[AuctionEnterBidFormConstants::CID_TXT_LOT_NUM_EXT] ?? '');
        $lotNumPrefix = Cast::toString($body[AuctionEnterBidFormConstants::CID_TXT_LOT_NUM_PREF] ?? '');
        $bidderNum = Cast::toString($body[AuctionEnterBidFormConstants::CID_TXT_BIDDER_NUM] ?? '');
        $amount = Cast::toString($this->getNumberFormatter()->removeFormat($body[AuctionEnterBidFormConstants::CID_TXT_AMOUNT] ?? ''));
        return $this->construct($lotFullNo, $lotNum, $lotNumExt, $lotNumPrefix, $amount, $bidderNum, $editorUserId);
    }
}
