<?php
/**
 * SAM-10316: Decouple DtoHelperAwareTrait from BaseMakerValidator and BaseMakerProducer
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Dto;

/**
 * Trait UserMakerDtoHelperAwareTrait
 * @package Sam\EntityMaker\User\Dto
 */
trait UserMakerDtoHelperAwareTrait
{
    protected ?UserMakerDtoHelper $userMakerDtoHelper = null;

    /**
     * @return UserMakerDtoHelper
     */
    protected function getUserMakerDtoHelper(): UserMakerDtoHelper
    {
        if ($this->userMakerDtoHelper === null) {
            $this->userMakerDtoHelper = UserMakerDtoHelper::new();
        }
        return $this->userMakerDtoHelper;
    }

    /**
     * @param UserMakerDtoHelper $userMakerDtoHelper
     * @return static
     * @internal
     */
    public function setUserMakerDtoHelper(UserMakerDtoHelper $userMakerDtoHelper): static
    {
        $this->userMakerDtoHelper = $userMakerDtoHelper;
        return $this;
    }
}
