<?php
/**
 * SAM-3796: Bidder upload into auction
 * SAM-9366: Refactor Sam\Bidder\AuctionBidder\CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Bidder\Internal\Validate\Internal;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Text\TextChecker;
use Sam\Import\Csv\Bidder\Internal\Dto\RowInput;
use Sam\Import\Csv\Bidder\Internal\Validate\Internal\BidderValidationResult as Result;
use Sam\Import\Csv\Bidder\Internal\Validate\Internal\Internal\DataProviderCreateTrait;
use Sam\Core\Constants;

/**
 * Class BidderValidator
 * @package Sam\Import\Csv\Bidder\Internal\Validate\Internal
 * @internal
 */
class BidderValidator extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate bidder information from CSV row
     *
     * @param RowInput $rowInput
     * @param int $auctionId
     * @param int $syncMode
     * @param string $encoding
     * @return BidderValidationResult
     */
    public function validate(
        RowInput $rowInput,
        int $auctionId,
        int $syncMode,
        string $encoding
    ): Result {
        $result = Result::new()->construct();

        if ($syncMode === Constants\Csv\Bidder::SYNC_BY_EMAIL) {
            $email = (string)$rowInput->userInputDto->email;
            if (!$email) {
                $result->addError(Result::ERR_EMAIL_REQUIRED);
            }
        }

        $bidderNo = $rowInput->bidderNo;
        if (!empty($bidderNo)) {
            if (!$this->isAlphanumeric($bidderNo)) {
                $result->addError(Result::ERR_BIDDER_NO_INVALID);
            }
            if (!$this->hasValidEncoding($bidderNo, $encoding)) {
                $result->addError(Result::ERR_BIDDER_NO_INVALID_ENCODING);
            }
        }

        $bidderUserid = Cast::toInt($rowInput->userInputDto->id);
        if ($this->createDataProvider()->existBidderNo($bidderNo, $auctionId, $bidderUserid)) {
            $result->addError($result::ERR_BIDDER_NO_EXIST);
        }

        return $result;
    }

    protected function isAlphanumeric(string $value): bool
    {
        return preg_match('/^\w*$/', $value);
    }

    protected function hasValidEncoding(string $value, string $encoding): bool
    {
        return TextChecker::new()->hasValidEncoding($value, $encoding);
    }
}
