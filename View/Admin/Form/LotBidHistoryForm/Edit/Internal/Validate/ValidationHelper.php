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

namespace Sam\View\Admin\Form\LotBidHistoryForm\Edit\Internal\Validate;

use BidTransaction;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoader;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollector;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;

/**
 * Class ValidationHelper
 */
class ValidationHelper extends CustomizableClass
{
    use AuctionAwareTrait;
    use EditorUserAwareTrait;
    use LotItemAwareTrait;
    use OptionalsTrait;
    use ResultStatusCollectorAwareTrait;

    // --- Incoming values ---

    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_BID_TRANSACTION = 'bidTransaction'; // ?BidTransaction

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
     * @param ResultStatusCollector $collector
     * @param int $bidTransactionId
     * @param int $lotItemId
     * @param int $auctionId
     * @param array $optionals
     * @return $this
     */
    public function construct(
        ResultStatusCollector $collector,
        int $bidTransactionId,
        int $lotItemId,
        int $auctionId,
        array $optionals = []
    ): static {
        $this->lotItemId = $lotItemId;
        $this->auctionId = $auctionId;
        $this->bidTransactionId = $bidTransactionId;
        $this->setResultStatusCollector($collector);
        $this->initOptionals($optionals);
        return $this;
    }

    // --- Main command method ---

    /**
     * Validate namespaceId
     * @return bool
     */
    public function validateEntity(): bool
    {
        /** @var BidTransaction|null $bidTransaction */
        $bidTransaction = $this->fetchOptional(self::OP_BID_TRANSACTION);
        if ($bidTransaction === null) {
            $this->getResultStatusCollector()->addError(BidTransactionEditorConstants::ERR_ID_NOT_EXISTED);
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function validateBidTransaction(): bool
    {
        /** @var BidTransaction|null $bidTransaction */
        $bidTransaction = $this->fetchOptional(self::OP_BID_TRANSACTION);
        $isValid = $bidTransaction
            && $bidTransaction->AuctionId === $this->auctionId
            && $bidTransaction->LotItemId === $this->lotItemId;
        if (!$isValid) {
            $this->getResultStatusCollector()->addError(BidTransactionEditorConstants::ERR_BID_TRANSACTION_INCORRECT);
        }
        return $isValid;
    }

    // --- Read results ---

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    // --- Internal logic ---

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $bidTransactionId = $this->bidTransactionId;
        $isReadOnlyDb = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $optionals[self::OP_BID_TRANSACTION] = $optionals[self::OP_BID_TRANSACTION]
            ?? static function () use ($bidTransactionId, $isReadOnlyDb): ?BidTransaction {
                return BidTransactionLoader::new()->loadById($bidTransactionId, $isReadOnlyDb);
            };
        $this->setOptionals($optionals);
    }
}

