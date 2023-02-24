<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\ConsignorCommissionFee;

use Sam\Core\Constants;

/**
 * General repository for ConsignorCommissionFee entity
 *
 * Class ConsignorCommissionRepository
 * @package Sam\Storage\Repository
 */
class ConsignorCommissionFeeReadRepository extends AbstractConsignorCommissionFeeReadRepository
{
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = ccf.related_entity_id AND ccf.level = ' . Constants\ConsignorCommissionFee::LEVEL_ACCOUNT
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * join `account` table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }
}
