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

namespace Sam\Import\Csv\Bidder\Internal\Validate;

/**
 * Trait ValidatorCreateTrait
 * @package Sam\Import\Csv\Bidder\Internal\Validate
 * @internal
 */
trait ValidatorCreateTrait
{
    /**
     * @var Validator|null
     */
    protected ?Validator $validator = null;

    /**
     * @return Validator
     */
    protected function createValidator(): Validator
    {
        return $this->validator ?: Validator::new();
    }

    /**
     * @param Validator $validator
     * @return static
     * @internal
     */
    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;
        return $this;
    }
}
