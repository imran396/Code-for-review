<?php

namespace Sam\Core\Constants;

/**
 * Class Role
 * @package Sam\Core\Constants
 */
class Role
{
    public const ADMIN = 'ADMIN';
    public const CONSIGNOR = 'CONSIGNOR';
    public const BIDDER = 'BIDDER';
    public const USER = 'USER';
    public const VISITOR = 'VISITOR';

    /** @var string[] */
    public static array $roles = [self::ADMIN, self::CONSIGNOR, self::BIDDER, self::USER, self::VISITOR];

    /** @var string[] */
    public static array $auctionAccessRoles = [self::ADMIN, self::BIDDER, self::USER, self::VISITOR];

    public const ACL_CUSTOMER = 'customer';
    public const ACL_ADMIN = 'admin';
    public const ACL_STAFF = 'staff';
    public const ACL_ANONYMOUS = 'anonymous';
}
