<?php
/**
 * Data transfer object for passing input data for User entity creating/updating/validating
 * Dto does not have to accurately describe the fields of the entity, it describes the incoming data from the external interface
 *
 * SAM-8841: User entity-maker module structural adjustments for v3-5
 * SAM-4989: User Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           May 22, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Dto;

use Sam\EntityMaker\Base\Dto\ConfigDto;
use UserCustField;

/**
 * @package Sam\EntityMaker\User
 */
class UserMakerConfigDto extends ConfigDto
{
    /** @var bool */
    public bool $isSignupAccountAdmin = false;
    public bool $isSignupPage = false;
    public bool $wasCcModified = false;
    public bool $saveAdminPrivileges = false;
    public bool $saveBidderPrivileges = false;
    public bool $saveConsignorPrivileges = false;
    /** @var UserCustField[] */
    public ?array $allCustomFields = null;
    public bool $isConfirmPage = false;
    public bool $autoAssignPreferredBidder = false;

    /**
     * Store acquired DB locks.
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
