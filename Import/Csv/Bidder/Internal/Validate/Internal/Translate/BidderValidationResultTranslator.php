<?php
/**
 * SAM-3796: Bidder upload into auction
 * SAM-9366: Refactor Sam\Bidder\AuctionBidder\CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Bidder\Internal\Validate\Internal\Translate;

use Sam\Core\Constants;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\Bidder\Internal\Validate\Internal\BidderValidationResult;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Provides translation of bidder validation errors
 *
 * Class BidderValidationResultTranslator
 * @package Sam\Import\Csv\Bidder\Internal\Validate\Internal\Translate
 */
class BidderValidationResultTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const TRANSLATION_KEYS = [
        BidderValidationResult::ERR_EMAIL_REQUIRED => 'import.csv.bidder.email.required',
        BidderValidationResult::ERR_BIDDER_NO_INVALID => 'import.csv.bidder.bidder_no.invalid',
        BidderValidationResult::ERR_BIDDER_NO_INVALID_ENCODING => 'import.csv.bidder.bidder_no.invalid_encoding',
        BidderValidationResult::ERR_BIDDER_NO_EXIST => 'import.csv.bidder.bidder_no.exist',
    ];

    protected const ERR_COLUMNS = [
        BidderValidationResult::ERR_EMAIL_REQUIRED => Constants\Csv\User::EMAIL,
        BidderValidationResult::ERR_BIDDER_NO_INVALID => Constants\Csv\User::BIDDER_NO,
        BidderValidationResult::ERR_BIDDER_NO_INVALID_ENCODING => Constants\Csv\User::BIDDER_NO,
        BidderValidationResult::ERR_BIDDER_NO_EXIST => Constants\Csv\User::BIDDER_NO,
    ];

    protected const TRANSLATION_DOMAIN = 'admin_validation';

    protected array $columnNames;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(array $columnNames): static
    {
        $this->columnNames = $columnNames;
        return $this;
    }

    /**
     * Translate error message for the result status error code
     *
     * @param ResultStatus $resultStatus
     * @return string
     */
    public function trans(ResultStatus $resultStatus): string
    {
        $code = $resultStatus->getCode();
        $translationKey = self::TRANSLATION_KEYS[$code] ?? '';
        if (!$translationKey) {
            log_error("Can't find translation for error with code {$code}");
            return '';
        }
        $errorColumn = self::ERR_COLUMNS[$code] ?? '';
        return $this->getAdminTranslator()->trans(
            $translationKey,
            [
                'columnName' => $this->columnNames[$errorColumn] ?? ''
            ],
            self::TRANSLATION_DOMAIN
        );
    }
}
