<?php
/**
 * SAM-5826: Decouple SyncNamespace Editor to classes and add unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SyncNamespace\Edit\Internal\Validate;

/**
 * Class SyncNamespaceEditorConstants
 * @package Sam\SyncNamespace\Edit\Internal\Validator
 */
class SyncNamespaceEditorConstants
{
    //Entity Errors
    public const ERR_ID_NOT_EXISTED = 1;
    public const ERR_SYNC_NAMESPACE_DELETED = 2;
    public const ENTITY_ERRORS = [self::ERR_ID_NOT_EXISTED, self::ERR_SYNC_NAMESPACE_DELETED];

    //Access Errors
    public const ERR_USER_ABSENT = 3;
    public const ERR_NO_ACCESS_BY_PRIVILEGE = 4;
    public const ERR_NO_ACCESS_BY_ACCOUNT = 5;
    public const ACCESS_ERRORS = [self::ERR_USER_ABSENT, self::ERR_NO_ACCESS_BY_PRIVILEGE, self::ERR_NO_ACCESS_BY_ACCOUNT];

    //Input Errrors
    public const ERR_NAME_REQUIRED = 6;
    public const ERR_NAME_EXISTED = 7;
    public const NAME_ERRORS = [self::ERR_NAME_REQUIRED, self::ERR_NAME_EXISTED];
}
