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

/**
 * Trait BidderValidationResultTranslatorCreateTrait
 * @package Sam\Import\Csv\Bidder\Internal\Validate\Internal\Translate
 */
trait BidderValidationResultTranslatorCreateTrait
{
    /**
     * @var BidderValidationResultTranslator|null
     */
    protected ?BidderValidationResultTranslator $bidderValidationResultTranslator = null;

    /**
     * @return BidderValidationResultTranslator
     */
    protected function createBidderValidationResultTranslator(): BidderValidationResultTranslator
    {
        return $this->bidderValidationResultTranslator ?: BidderValidationResultTranslator::new();
    }

    /**
     * @param BidderValidationResultTranslator $bidderValidationResultTranslator
     * @return static
     * @internal
     */
    public function setBidderValidationResultTranslator(BidderValidationResultTranslator $bidderValidationResultTranslator): static
    {
        $this->bidderValidationResultTranslator = $bidderValidationResultTranslator;
        return $this;
    }
}
