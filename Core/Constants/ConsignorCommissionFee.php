<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class ConsignorCommissionFee
 * @package Sam\Core\Constants
 */
class ConsignorCommissionFee
{
    public const LEVEL_ACCOUNT = 1;
    public const LEVEL_USER = 2;
    public const LEVEL_AUCTION = 3;
    public const LEVEL_LOT_ITEM = 4;
    public const LEVEL_AUCTION_LOT = 5;

    public const CALCULATION_METHOD_SLIDING = 1;
    public const CALCULATION_METHOD_TIERED = 2;
    public const CALCULATION_METHODS = [
        self::CALCULATION_METHOD_SLIDING,
        self::CALCULATION_METHOD_TIERED,
    ];

    public const CALCULATION_METHOD_SLIDING_NAME = 'sliding';
    public const CALCULATION_METHOD_TIERED_NAME = 'tiered';
    public const CALCULATION_METHOD_NAMES = [
        self::CALCULATION_METHOD_SLIDING => self::CALCULATION_METHOD_SLIDING_NAME,
        self::CALCULATION_METHOD_TIERED => self::CALCULATION_METHOD_TIERED_NAME,
    ];


    public const FEE_REFERENCE_ZERO = 'zero';
    public const FEE_REFERENCE_HAMMER_PRICE = 'hammer_price';
    public const FEE_REFERENCE_START_BID = 'starting_bid';
    public const FEE_REFERENCE_RESERVE_PRICE = 'reserve_price';
    public const FEE_REFERENCE_MAX_BID = 'max_bid';
    public const FEE_REFERENCE_CURRENT_BID = 'current_bid';
    public const FEE_REFERENCE_LOW_ESTIMATE = 'low_estimate';
    public const FEE_REFERENCE_HIGH_ESTIMATE = 'high_estimate';
    public const FEE_REFERENCE_COST = 'cost';
    public const FEE_REFERENCE_REPLACEMENT_PRICE = 'replacement_price';
    public const FEE_REFERENCE_CUSTOM_FIELD_PREFIX = 'custom_field:';

    public const FEE_REFERENCES = [
        self::FEE_REFERENCE_ZERO,
        self::FEE_REFERENCE_HAMMER_PRICE,
        self::FEE_REFERENCE_START_BID,
        self::FEE_REFERENCE_RESERVE_PRICE,
        self::FEE_REFERENCE_MAX_BID,
        self::FEE_REFERENCE_CURRENT_BID,
        self::FEE_REFERENCE_LOW_ESTIMATE,
        self::FEE_REFERENCE_HIGH_ESTIMATE,
        self::FEE_REFERENCE_COST,
        self::FEE_REFERENCE_REPLACEMENT_PRICE,
    ];

    public const RANGE_MODE_SUM = 1;
    public const RANGE_MODE_GREATER = 2;
    public const RANGE_MODES = [
        self::RANGE_MODE_SUM,
        self::RANGE_MODE_GREATER,
    ];

    public const RANGE_MODE_SUM_NAME = '+';
    public const RANGE_MODE_GREATER_NAME = '>';
    public const RANGE_MODE_NAMES = [
        self::RANGE_MODE_SUM => self::RANGE_MODE_SUM_NAME,
        self::RANGE_MODE_GREATER => self::RANGE_MODE_GREATER_NAME,
    ];

    public const RANGE_MODE_SOAP_SUM_NAME = 'sum';
    public const RANGE_MODE_SOAP_GREATER_NAME = 'greater';
    public const RANGE_MODE_SOAP_NAMES = [
        self::RANGE_MODE_SUM => self::RANGE_MODE_SOAP_SUM_NAME,
        self::RANGE_MODE_GREATER => self::RANGE_MODE_SOAP_GREATER_NAME,
    ];

    public const OPTION_CUSTOM = 'custom';
    public const OPTION_DEFAULT = '';
}
