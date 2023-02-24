<?php
/**
 * SAM-5546: Auction registration step detection and redirect
 *
 * @copyright       2019 Yura Vakulenko.
 * @author          Yura Vakulenko
 * @since           01.12.2019
 * file encoding    UTF-8
 *
 */

namespace Sam\Core\Constants;

/**
 * Class RegistrationStepStatuses
 * @package Sam\Core\Constants
 */
class RegistrationStepStatuses
{
    public const STEP_ANONYMOUS_USER = 1;
    public const STEP_AUCTION_REGISTERED = 2;  // Login
    public const STEP_CONFIRM_BIDDER_OPTIONS = 3; // Login, Auction register
    public const STEP_CONFIRM_SHIPPING = 4; // Login, Auction register
    public const STEP_AUCTION_ABSENT = 5; // Login
    public const STEP_NO_BIDDER_PRIVILEGES = 6; // Auction register
    public const STEP_RENEW_BILLING = 7; // Auction register
    public const STEP_REVISE_BILLING = 8; // Login, Auction register
    public const STEP_TERMS_AND_CONDITIONS = 9; // Login, Auction register
}
