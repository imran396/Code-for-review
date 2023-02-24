<?php
/**
 * Data Transfer Object for passing input data for Lot entity creating/updating/validating
 * Dto does not have to accurately describe the fields of the entity, it describes the incoming data from the external interface
 *
 * SAM-4015: Auction Lot and Lot Item Entity Makers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Dec 7, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Dto;

use LotItemCustField;
use Sam\EntityMaker\Base\Dto\ConfigDto;

class LotItemMakerConfigDto extends ConfigDto
{
    // Service fields
    /** @var LotItemCustField[] */
    public ?array $allCustomFields = null;
    public ?int $auctionId = null;
    /**
     * @var string '' when updating inventory lot items
     */
    public string $auctionType = '';
    public ?int $lotStatusId = null;
    public bool $noBidding = false;
    public bool $reverse = false;
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
