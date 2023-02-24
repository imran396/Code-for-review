<?php
/**
 *
 * SAM-4557: Settlement management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-02
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\AdditionalCharge;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\DeleteRepository\Entity\SettlementAdditional\SettlementAdditionalDeleteRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\SettlementAdditional\SettlementAdditionalReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\SettlementAdditional\SettlementAdditionalWriteRepositoryAwareTrait;
use SettlementAdditional;

/**
 * Class SettlementAdditionalChargeManager
 * @package Sam\Settlement\AdditionalCharge
 */
class SettlementAdditionalChargeManager extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use SettlementAdditionalDeleteRepositoryCreateTrait;
    use SettlementAdditionalReadRepositoryCreateTrait;
    use SettlementAdditionalWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Add Charge
     * @param int $settlementId
     * @param string $name
     * @param float $amount
     * @param int $editorUserId
     * @return SettlementAdditional
     */
    public function add(int $settlementId, string $name, float $amount, int $editorUserId): SettlementAdditional
    {
        $settlementAdditional = $this->createEntityFactory()->settlementAdditional();
        $settlementAdditional->SettlementId = $settlementId;
        $settlementAdditional->Name = $name;
        $settlementAdditional->Amount = $amount;
        $this->getSettlementAdditionalWriteRepository()->saveWithModifier($settlementAdditional, $editorUserId);
        return $settlementAdditional;
    }

    /**
     * Remove All Charges
     * @param int $settlementId
     */
    public function deleteBySettlementId(int $settlementId): void
    {
        $this->createSettlementAdditionalDeleteRepository()
            ->filterSettlementId($settlementId)
            ->delete();
    }

    /**
     * Get Charges
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return SettlementAdditional[]
     */
    public function loadBySettlementId(int $settlementId, bool $isReadOnlyDb = false): array
    {
        $settlementAdditionals = $this->createSettlementAdditionalReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterSettlementId($settlementId)
            ->orderById()
            ->loadEntities();
        return $settlementAdditionals;
    }
}
