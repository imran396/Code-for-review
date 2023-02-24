<?php
/**
 * Describe fields and their properties for soap documentation and wsdl for entity-maker of Location.
 *
 * SAM-10273: Entity locations: Implementation (Dev)
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Feb 7, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Location\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * @package Sam\EntityMaker\LotCategory
 */
class LocationMakerInputMeta extends CustomizableClass
{
    /**
     * @var string
     */
    public $address;
    /**
     * @var string
     */
    public $city;
    /**
     * @var string <a href="/api/soap12?op=CountriesAndStates">2 character country Code</a>
     */
    public $country;
    /**
     * @var string
     */
    public $county;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $id;
    /**
     * @var string
     */
    public $logo;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string <a href="/api/soap12?op=CountriesAndStates">2 character code</a> for US states, Canadian provinces or Mexico states
     */
    public $state;
    /**
     * @var string LocationSyncKey
     * @group Depending on Namespace setting
     */
    public $syncKey;
    /**
     * @var string
     */
    public $syncNamespaceId;
    /**
     * @var string
     */
    public $zip;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
