<?php
/**
 * SAM-4697: Feed entity editor
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           3/21/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feed\Edit\Internal\Validate;

/**
 * Class Constants
 * @package
 */
class FeedEditorConstants
{
    // Entity errors
    public const ERR_FEED_ABSENT = 1;
    public const ERR_FEED_DELETED = 2;
    public const ENTITY_ERRORS = [self::ERR_FEED_ABSENT, self::ERR_FEED_DELETED];

    // Access errors
    public const ERR_USER_ABSENT = 11;
    public const ERR_NO_ACCESS_BY_PRIVILEGE = 12;
    public const ERR_NO_ACCESS_BY_ACCOUNT = 13;
    public const ACCESS_ERRORS = [self::ERR_USER_ABSENT, self::ERR_NO_ACCESS_BY_PRIVILEGE, self::ERR_NO_ACCESS_BY_ACCOUNT];

    // Input errors
    public const ERR_ACCOUNT_REQUIRED = 21;
    public const ERR_ACCOUNT_INVALID = 22;
    public const ERR_ACCOUNT_ABSENT = 23;
    public const ACCOUNT_ERRORS = [self::ERR_ACCOUNT_REQUIRED, self::ERR_ACCOUNT_INVALID, self::ERR_ACCOUNT_ABSENT];

    public const ERR_CACHE_TIMEOUT_REQUIRED = 31;
    public const ERR_CACHE_TIMEOUT_INVALID = 32;
    public const CACHE_TIMEOUT_ERRORS = [self::ERR_CACHE_TIMEOUT_REQUIRED, self::ERR_CACHE_TIMEOUT_INVALID];

    public const ERR_CURRENCY_REQUIRED = 41;
    public const ERR_CURRENCY_INVALID = 42;
    public const ERR_CURRENCY_ABSENT = 43;
    public const CURRENCY_ERRORS = [self::ERR_CURRENCY_REQUIRED, self::ERR_CURRENCY_INVALID, self::ERR_CURRENCY_ABSENT];

    public const ERR_ENCODING_REQUIRED = 51;
    public const ERR_ENCODING_INVALID = 52;
    public const ENCODING_ERRORS = [self::ERR_ENCODING_REQUIRED, self::ERR_ENCODING_INVALID];

    public const ERR_ESCAPING_REQUIRED = 61;
    public const ERR_ESCAPING_INVALID = 62;
    public const ESCAPING_ERRORS = [self::ERR_ESCAPING_REQUIRED, self::ERR_ESCAPING_INVALID];

    public const ERR_ITEMS_PER_PAGE_REQUIRED = 71;
    public const ERR_ITEMS_PER_PAGE_INVALID = 72;
    public const ITEMS_PER_PAGE_ERRORS = [self::ERR_ITEMS_PER_PAGE_REQUIRED, self::ERR_ITEMS_PER_PAGE_INVALID];

    public const ERR_LOCALE_REQUIRED = 81;
    public const ERR_LOCALE_INVALID = 82;
    public const LOCALE_ERRORS = [self::ERR_LOCALE_REQUIRED, self::ERR_LOCALE_INVALID];

    public const ERR_NAME_REQUIRED = 91;
    public const ERR_NAME_LENGTH = 92;
    public const NAME_ERRORS = [self::ERR_NAME_REQUIRED, self::ERR_NAME_LENGTH];

    public const ERR_SLUG_REQUIRED = 101;
    public const ERR_SLUG_LENGTH = 102;
    public const ERR_SLUG_EXIST = 103;
    public const SLUG_ERRORS = [self::ERR_SLUG_REQUIRED, self::ERR_SLUG_EXIST, self::ERR_SLUG_LENGTH];

    public const ERR_TYPE_REQUIRED = 111;
    public const ERR_TYPE_INVALID = 112;
    public const TYPE_ERRORS = [self::ERR_TYPE_REQUIRED, self::ERR_TYPE_INVALID];
}
