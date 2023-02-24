<?php
/**
 * Describe fields and their properties for soap documentation and wsdl for entity-maker of Account.
 *
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

use Sam\Core\Service\CustomizableClass;

/**
 * @package Sam\EntityMaker\Account
 */
class AccountMakerInputMeta extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @var string
     */
    public $address;
    /**
     * @var string
     */
    public $address2;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $auctionIncAllowed;
    /**
     * @var string
     */
    public $city;
    /**
     * @var string
     */
    public $companyName;
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
     */
    public $email;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $hybridAuctionEnabled;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $buyNowSelectQuantityEnabled;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $id;
    /**
     * @var string
     * @soap-required
     */
    public $name;
    /**
     * @var string
     */
    public $notes;
    /**
     * @var string
     */
    public $phone;
    /**
     * @var string
     */
    public $publicSupportContactName;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $showAll;
    /**
     * @var string
     */
    public $siteUrl;
    /**
     * @var string <a href="/api/soap12?op=CountriesAndStates">2 character code</a> for US states, Canadian provinces or Mexico states
     */
    public $stateProvince;
    /**
     * @var string AccountSyncKey
     * @group Depending on Namespace setting
     */
    public $syncKey;
    /**
     * @var string
     */
    public $syncNamespaceId;
    /**
     * @var string Required for portal mode when main-domain is used for portal url handling
     * @soap-required-conditionally
     */
    public $urlDomain;
    /**
     * @var string
     */
    public $zip;
}
