<?php
/**
 * SAM-9365: Refactor BidIncrementCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\BidIncrement\Internal\Process;

use Sam\Bidding\BidIncrement\Load\BidIncrementLoaderAwareTrait;
use Sam\Bidding\BidIncrement\Save\BidIncrementProducerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\BidIncrement\Internal\Dto\RowInput;
use Sam\Storage\WriteRepository\Entity\BidIncrement\BidIncrementWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * This class is responsible for producing bid increment from the CSV row
 *
 * Class RowProcessor
 * @package Sam\Import\Csv\BidIncrement\Internal\Process
 */
class RowProcessor extends CustomizableClass
{
    use BidIncrementLoaderAwareTrait;
    use BidIncrementProducerAwareTrait;
    use BidIncrementWriteRepositoryAwareTrait;
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Process row data.
     * Update the increment if it already exists with that amount, or create a new one.
     *
     * @param RowInput $row
     * @param string $auctionType
     * @param int $editorUserId
     * @param int $systemAccountId
     * @return Result
     */
    public function process(RowInput $row, string $auctionType, int $editorUserId, int $systemAccountId): Result
    {
        $numberFormatter = $this->getNumberFormatter()->construct($systemAccountId);
        $amount = $numberFormatter->parseMoney($row->amount);
        $increment = $numberFormatter->parseMoney($row->increment);

        $bidIncrement = $this->getBidIncrementLoader()->loadByAmountForAccount($amount, $systemAccountId, $auctionType);
        if ($bidIncrement) {
            $bidIncrement->Increment = $increment;
            $this->getBidIncrementWriteRepository()->saveWithModifier($bidIncrement, $editorUserId);
            return Result::new()->updated();
        }

        $this->getBidIncrementProducer()->create(
            $amount,
            $increment,
            $editorUserId,
            $systemAccountId,
            $auctionType
        );
        return Result::new()->created();
    }
}
