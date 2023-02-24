<?php
/**
 * Repository for LotFieldConfig
 *
 * SAM-3653: LotFieldConfig lot related repositories  https://bidpath.atlassian.net/browse/SAM-3688
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           26 Apr, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\LotFieldConfig;

/**
 * Class LotFieldConfigReadRepository
 */
class LotFieldConfigReadRepository extends AbstractLotFieldConfigReadRepository
{
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = lfc.account_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
