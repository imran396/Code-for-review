<?php
/**
 * SAM-8107: Issues related to Validation and Values of Buyer's Premium
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Save\Internal\BuyersPremium\Internal\Save;

use Sam\BuyersPremium\Csv\Parse\BuyersPremiumCsvParserCreateTrait;
use Sam\BuyersPremium\Save\BuyersPremiumRangeProducerCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BuyersPremiumSaver
 * @package Sam\EntityMaker\LotItem\Save\Internal\BuyersPremium
 */
class BuyersPremiumSaver extends CustomizableClass
{
    use BuyersPremiumRangeProducerCreateTrait;
    use BuyersPremiumCsvParserCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param BuyersPremiumSavingInput $input
     * @param bool $isCreate
     * @param bool $isReadOnlyDb
     * @return void
     */
    public function save(BuyersPremiumSavingInput $input, bool $isCreate = false, bool $isReadOnlyDb = false): void
    {
        if (
            $input->buyersPremiumString === null
            && $input->buyersPremiumDataRows === null
        ) {
            return;
        }

        if ($input->mode->isCsv()) {
            $buyersPremiumDataRows = $this->createBuyersPremiumCsvParser()
                ->parse($input->buyersPremiumString, $input->entityContextAccountId);
        } else {
            $buyersPremiumDataRows = $input->buyersPremiumDataRows;
        }

        if ($isCreate) {
            $this->createBuyersPremiumRangeProducer()->create(
                $buyersPremiumDataRows,
                $input->editorUserId,
                null,
                null,
                $input->entityContextAccountId,
                $input->auctionType,
                $input->userId,
                $isReadOnlyDb
            );
            return;
        }

        $this->createBuyersPremiumRangeProducer()->update(
            $buyersPremiumDataRows,
            $input->editorUserId,
            null,
            null,
            $input->entityContextAccountId,
            $input->auctionType,
            $input->userId,
            $isReadOnlyDb
        );
    }

}
