<?php
/** @noinspection SpellCheckingInspection */

namespace Sam\Core\Constants;

/**
 * Class UserShipping
 * @package Sam\Core\Constants
 */
class CarrierService
{
    // Carrier Methods
    public const M_NONE = 'N';
    public const M_PICKUP = 'PU';
    /** @var string[] */
    public const CARRIER_METHODS = [self::M_NONE, self::M_PICKUP];
    /** @var string[] */
    public const CARRIER_METHOD_NAMES = [
        self::M_NONE => 'None',
        self::M_PICKUP => 'Pickup',
    ];

    // Carrier Companies
    public const COMP_DHL = 'DHL';
    public const COMP_FEDEX = 'FEDEX';
    public const COMP_UPS = 'UPS';
    public const COMP_USPS = 'USPS';

    /** @var string[][] */
    public const CARRIER_SERVICE_NAMES = [
        self::COMP_DHL => [
            'DHLWPE' => 'Worldwide Priority Express',
            'DHL9AM' => 'Express 9 A.M.',
            'DHL10AM' => 'Express 10:30 A.M.',
            'DHL12PM' => 'Express 12 P.M.'
        ],
        self::COMP_FEDEX => [
            'FDX2D' => '2 Day',
            'FDXES' => 'Express Saver',
            'FDXFO' => 'First Overnight',
            'FDXPO' => 'Priority Overnight',
            'FDXPOS' => 'Priority Overnight Saturday Delivery',
            'FDXSO' => 'Standard Overnight',
            'FDXGND' => 'Ground',
            'FDXHD' => 'Home Delivery'
        ],
        self::COMP_UPS => [
            'UPSNDA' => 'Next Day Air',
            'UPSNDE' => 'Next Day Air Early AM',
            'UPSNDAS' => 'Next Day Air Saturday Delivery',
            'UPSNDS' => 'Next Day Air Saver',
            'UPS2DE' => '2 Day Air AM',
            'UPS2ND' => '2nd Day Air',
            'UPS3DS' => '3 Day Select',
            'UPSGND' => 'Ground',
            'UPSCAN' => 'UPS Standard',
            'UPSWEX' => 'Worldwide Express',
            'UPSWSV' => 'Worldwide Saver',
            'UPSWEP' => 'Worldwide Expedited'
        ],
        self::COMP_USPS => [
            'USPFC' => 'First-Class Mail',
            'USPEXP' => 'Express Mail',
            'USPBPM' => 'Bound/Printed-- Inactive',
            'USPLIB' => 'Library',
            'USPMM' => 'Media Mail',
            'USPPM' => 'Priority Mail',
            'USPPP' => 'Standard Post',
            'USPFCI' => 'First Class International',
            'USPPMI' => 'Priority Mail International',
            'USPEMI' => 'Express Mail International',
            'USPGXG' => 'Global Express Guaranteed',
            'USPALP' => 'Airmail Letter Post- Deprecated',
            'USPAPP' => 'Airmail Parcel Post- Deprecated',
            'USPELP' => 'Economy Letter Post- Deprecated',
            'USPEPP' => 'Economy Parcel Post- Deprecated',
            'USPGE' => 'Global Express Mail- Deprecated',
            'USPGPM' => 'Global Priority Mail- Deprecated'
        ],
    ];
}
