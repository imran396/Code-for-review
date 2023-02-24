<?php
/**
 *
 * SAM-4743: MySearch loader and existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-27
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class MySearch
 * @package Sam\Core\Constants
 */
class MySearch
{
    public const DESC = 'descending';
    public const ASC = 'ascending';

    public const CATEGORY_MATCH_ALL = 1;
    public const CATEGORY_MATCH_ANY = 2;

    // Overall Lot Status Filtering
    public const OLSF_ALL = 1;
    public const OLSF_UNASSIGNED = 2;
    public const OLSF_ASSIGNED = 3;
    public const OLSF_SOLD = 4;
    public const OLSF_UNSOLD = 5;
    public const OLSF_RECEIVED = 6;

    /**
     * All overall lot status filters
     * @var int[]
     */
    public static array $overallLotStatusFilters = [
        self::OLSF_ALL,
        self::OLSF_UNASSIGNED,
        self::OLSF_ASSIGNED,
        self::OLSF_SOLD,
        self::OLSF_UNSOLD,
        self::OLSF_RECEIVED,
    ];

    /** @var string[] */
    public static array $overallLotStatusFilterNames = [
        self::OLSF_ALL => 'All',
        self::OLSF_UNASSIGNED => 'Unassigned',
        self::OLSF_ASSIGNED => 'Assigned',
        self::OLSF_SOLD => 'Sold',
        self::OLSF_UNSOLD => 'Unsold',
        self::OLSF_RECEIVED => 'Received',
    ];

    /**
     * Filtering options available at Auction Lots page for Assign-ready Lot List
     * @var int[]
     */
    public static array $assignReadyLotStatusFilters = [
        self::OLSF_ALL,
        self::OLSF_UNASSIGNED,
        self::OLSF_ASSIGNED,
        self::OLSF_UNSOLD,
    ];

    // Assign-ready lot status default filtering
    public const ASSIGN_READY_OLSF_DEFAULT = self::OLSF_UNSOLD;

    /**
     * Filtering options available at Inventory List page
     * @var int[]
     */
    public static array $inventoryLotStatusFilters = [
        self::OLSF_ALL,
        self::OLSF_UNASSIGNED,
        self::OLSF_ASSIGNED,
        self::OLSF_SOLD,
        self::OLSF_UNSOLD,
        self::OLSF_RECEIVED,
    ];

    // Inventory lot status default filtering
    public const INVENTORY_OLSF_DEFAULT = self::OLSF_ALL;

    // Lot Billing Status Filter
    public const LBSF_ALL = 1;
    public const LBSF_OPEN = 2;
    public const LBSF_BILLED = 3;
    public const LBSF_DEFAULT = self::LBSF_ALL;
    /** @var int[] */
    public static array $lotBillingStatuses = [self::LBSF_ALL, self::LBSF_OPEN, self::LBSF_BILLED];
    /** @var string[] */
    public static array $lotBillingStatusNames = [
        self::LBSF_ALL => 'ALL',
        self::LBSF_OPEN => 'Open',
        self::LBSF_BILLED => 'Billed'
    ];
}
