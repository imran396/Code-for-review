<?php
/**
 * SAM-6684: Merge the two admin bidding histories and Improvement for Lot bidding History
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/29/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotBidHistoryForm\Edit\Validate;

use Sam\View\Admin\Form\LotBidHistoryForm\Edit\Internal\Validate\BidTransactionEditorConstants;
use Sam\View\Admin\Form\LotBidHistoryForm\Edit\Internal\Validate\ValidationHelperCreateTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class Validator
 * @package Sam\View\Admin\Form\LotBidHistoryForm\Edit
 */
class LotBidHistoryValidator extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;
    use ValidationHelperCreateTrait;

    // --- Incoming values ---

    protected int $lotItemId;
    protected int $auctionId;
    protected int $bidTransactionId;

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
     * @param int $bidTransactionId
     * @param int $lotItemId
     * @param int $auctionId
     * @return $this
     */
    public function construct(int $bidTransactionId, int $lotItemId, int $auctionId): static
    {
        $this->bidTransactionId = $bidTransactionId;
        $this->lotItemId = $lotItemId;
        $this->auctionId = $auctionId;
        $errorMessages = [
            BidTransactionEditorConstants::ERR_ID_NOT_EXISTED => 'Transaction ID is not existed',
            BidTransactionEditorConstants::ERR_BID_TRANSACTION_INCORRECT => 'Bid transaction incorrect',
        ];
        $this->getResultStatusCollector()->initAllErrors($errorMessages);
        return $this;
    }

    // --- Main command method ---

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $validationHelper = $this->createValidationHelper()
            ->construct($this->getResultStatusCollector(), $this->bidTransactionId, $this->lotItemId, $this->auctionId);
        $isValid = $validationHelper->validateEntity()
            && $validationHelper->validateBidTransaction();
        if (!$isValid) {
            $this->setResultStatusCollector($validationHelper->getResultStatusCollector()); // JIC
        }
        return $isValid;
    }

    // --- Read results ---

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }
}
