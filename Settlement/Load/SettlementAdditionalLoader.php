<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-11-27
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Load;

use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\SettlementAdditional\SettlementAdditionalReadRepositoryCreateTrait;

/**
 * Class SettlementAdditionalLoader
 * @package Sam\Settlement\Load
 */
class SettlementAdditionalLoader extends EntityLoaderBase
{

    use SettlementAdditionalReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return array|\SettlementAdditional[]
     */
    public function load(int $settlementId, bool $isReadOnlyDb = false): array
    {
        $settlementAdditionals = $this->createSettlementAdditionalReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterSettlementId($settlementId)
            ->loadEntities();
        return $settlementAdditionals;
    }
}
