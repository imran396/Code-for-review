<?php
/**
 * Data transfer object for passing input data for Auction entity creating/updating/validating
 * Dto does not have to accurately describe the fields of the entity, it describes the incoming data from the external interface
 *
 * SAM-8840: Auction entity-maker module structural adjustments for v3-5
 * SAM-4241 Auction Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           May 3, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Dto;

use AuctionCustField;
use Sam\EntityMaker\Base\Dto\ConfigDto;

/**
 * @package Sam\EntityMaker\Auction
 */
class AuctionMakerConfigDto extends ConfigDto
{
    // Service fields
    /** @var AuctionCustField[] */
    public ?array $allCustomFields = null;
    /**
     * Store names of acquired DB locks.
     * @var string[]
     */
    public array $dbLocks = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
