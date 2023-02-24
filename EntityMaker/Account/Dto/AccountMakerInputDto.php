<?php
/**
 * Data Transfer Object for passing input data for Account entity creating/updating/validating
 * Dto does not have to accurately describe the fields of the entity, it describes the incoming data from the external interface
 *
 * SAM-10375: Input DTO adjustments and fixes for v3-7
 * SAM-8855: Account entity-maker module structural adjustments for v3-5
 * SAM-3942: Account entity maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Nov 2, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Account\Dto;

use Sam\EntityMaker\Base\Dto\InputDto;

/**
 * @package Sam\EntityMaker\Account
 * @property string|null $address
 * @property string|null $address2
 * @property string|null $auctionIncAllowed
 * @property string|null $city
 * @property string|null $companyName
 * @property string|null $country
 * @property string|null $county
 * @property string|null $email
 * @property string|null $hybridAuctionEnabled
 * @property string|null $buyNowSelectQuantityEnabled
 * @property string|int|null $id
 * @property string|null $name
 * @property string|null $notes
 * @property string|null $phone
 * @property string|null $publicSupportContactName
 * @property string|null $showAll
 * @property string|null $siteUrl
 * @property string|null $stateProvince
 * @property string|null $syncKey
 * @property string|null $syncNamespaceId
 * @property string|null $urlDomain
 * @property string|null $zip
 */
class AccountMakerInputDto extends InputDto
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
