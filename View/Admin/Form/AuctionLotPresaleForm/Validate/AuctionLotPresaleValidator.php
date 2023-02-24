<?php
/**
 * Validate lot absentee max bid amount, bidder no, bidding date.
 *
 * SAM-8763 : Lot Absentee Bid List page - Add bid amount validation
 * https://bidpath.atlassian.net/browse/SAM-8763
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotPresaleForm\Validate;

use Sam\Core\Date\Validate\DateFormatValidator;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\DateFormatDetector;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\View\Admin\Form\AuctionLotPresaleForm\Validate\Internal\Load\DataProvider;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\AuctionLotPresaleForm\Validate\AuctionLotPresaleValidationInput as Input;
use Sam\View\Admin\Form\AuctionLotPresaleForm\Validate\AuctionLotPresaleValidationResult as Result;

/**
 * Class AuctionLotPresaleValidator
 * @package Sam\View\Admin\Form\AuctionLotPresaleForm\Validate
 */
class AuctionLotPresaleValidator extends CustomizableClass
{
    use NumberFormatterAwareTrait;

    private ?DataProvider $dataProvider = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Main command method ---

    /**
     * @param Input $input
     * @return Result
     */
    public function validate(Input $input): Result
    {
        $result = Result::new()->construct();
        $this->getNumberFormatter()->construct($input->systemAccountId);
        $result = $this->validateMaxBid($input, $result);
        $result = $this->validateBiddingDate($input, $result);
        $result = $this->validateBidder($input, $result);
        $result = $this->validateAbsenteeBid($input, $result);
        return $result;
    }

    // --- Internal logic ---

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateMaxBid(Input $input, Result $result): Result
    {
        if ($input->isPhoneBid) {
            return $result;
        }

        $maxBid = $this->getNumberFormatter()->removeFormat($input->maxBid);
        if (!$maxBid) {
            return $result->addError(Result::ERR_MAX_BID_REQUIRED);
        }

        if (!is_numeric($maxBid)) {
            return $result->addError(Result::ERR_MAX_BID_NOT_NUMERIC);
        }

        return $result;
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateBiddingDate(Input $input, Result $result): Result
    {
        $dateFormats = DateFormatDetector::new()->dateFormatsForMode(Mode::WEB_ADMIN);
        $isValidDateTimeFormat = DateFormatValidator::new()->isValidFormatDateTime($input->biddingDate, $dateFormats);
        if (!$isValidDateTimeFormat) {
            $result->addError(Result::ERR_BID_DATE_INVALID_FORMAT);
        }
        return $result;
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateBidder(Input $input, Result $result): Result
    {
        if (!$input->isNewBid) {
            return $result;
        }

        if (!$input->bidUserId) {
            return $result->addError(Result::ERR_BID_USER_ID_ABSENT);
        }

        $isUserFound = $this->getDataProvider()->existBidUserId($input->bidUserId, true);
        if (!$isUserFound) {
            return $result->addError(Result::ERR_BID_USER_NOT_FOUND);
        }

        return $result;
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateAbsenteeBid(Input $input, Result $result): Result
    {
        if ($input->isNewBid) {
            return $result;
        }

        $result->updatedAbsenteeBid = $this->getDataProvider()->loadAbsenteeBid($input->absenteeBidId, true);
        if (!$result->updatedAbsenteeBid) {
            $result->addError(Result::ERR_BID_NOT_FOUND);
        }
        return $result;
    }

    // --- DI ---

    /**
     * @return DataProvider
     */
    protected function getDataProvider(): DataProvider
    {
        if ($this->dataProvider === null) {
            $this->dataProvider = DataProvider::new();
        }
        return $this->dataProvider;
    }

    /**
     * @param DataProvider $dataProvider
     * @return $this
     */
    public function setDataProvider(DataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }
}
