<?php
/**
 * Data Transfer Object for passing input data for Auction Lot entity creating/updating/validating
 * Dto does not have to accurately describe the fields of the entity, it describes the incoming data from the external interface
 *
 * SAM-8839: Auction Lot entity-maker module structural adjustments for v3-5
 * SAM-4015: Auction Lot and Lot Item Entity Makers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Nov 17, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Dto;

use Sam\EntityMaker\Base\Dto\ConfigDto;

/**
 * @package Sam\EntityMaker\AuctionLot
 */
class AuctionLotMakerConfigDto extends ConfigDto
{
    // Service fields
    public string $auctionType = '';
    public bool $extendAll = false;
    public bool $overwriting = false;
    public bool $reverse = false;
    /**
     * We have to pass input of the Tax Default Country field of LotItem entity,
     * because it affects the tax country detection required for the Auction Lot operations.
     * @var string|null null means field value not assigned, '' empty means none tax country is assigned.
     */
    public ?string $lotItemTaxDefaultCountryInput = null;
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
