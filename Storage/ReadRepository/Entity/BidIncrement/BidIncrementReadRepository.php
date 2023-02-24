<?php
/**
 * General repository for BidIncrement entity
 *
 * SAM-3690: Bidding related repositories https://bidpath.atlassian.net/browse/SAM-3690
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           08 May, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\BidIncrement;

/**
 * Class BidIncrementReadRepository
 */
class BidIncrementReadRepository extends AbstractBidIncrementReadRepository
{
    protected array $joins = [
        'account' => 'JOIN account acc ON acc.id = bi.account_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join 'account' table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }
}
