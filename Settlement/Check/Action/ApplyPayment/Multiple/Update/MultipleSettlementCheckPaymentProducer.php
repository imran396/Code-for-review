<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\ApplyPayment\Multiple\Update;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\ApplyPayment\Single\Update\SingleSettlementCheckPaymentProductionResult;
use Sam\Settlement\Check\Load\SettlementCheckLoaderCreateTrait;
use Sam\Settlement\Check\Action\ApplyPayment\Single\Update\SingleSettlementCheckPaymentProducerCreateTrait;

/**
 * Class MultipleSettlementCheckPaymentProducer
 * @package Sam\Settlement\Check
 */
class MultipleSettlementCheckPaymentProducer extends CustomizableClass
{
    use SettlementCheckLoaderCreateTrait;
    use SingleSettlementCheckPaymentProducerCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $settlementCheckIds
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return SingleSettlementCheckPaymentProductionResult[]
     */
    public function produce(array $settlementCheckIds, int $editorUserId, bool $isReadOnlyDb = false): array
    {
        $generator = $this->createSettlementCheckLoader()->yieldByIds($settlementCheckIds, $isReadOnlyDb);
        $paymentProducer = $this->createSingleSettlementCheckPaymentProducer();
        $results = [];
        foreach ($generator as $settlementCheck) {
            $results[] = $paymentProducer->produceByCheck($settlementCheck, $editorUserId);
        }
        return $results;
    }

}
