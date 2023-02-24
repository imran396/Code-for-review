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

namespace Sam\EntityMaker\Account\Dto;


/**
 * Trait AccountMakerDtoHelperAwareTrait
 * @package Sam\EntityMaker\Account\Dto
 */
trait AccountMakerDtoHelperAwareTrait
{
    protected ?AccountMakerDtoHelper $accountMakerDtoHelper = null;

    /**
     * @return AccountMakerDtoHelper
     */
    protected function getAccountMakerDtoHelper(): AccountMakerDtoHelper
    {
        if ($this->accountMakerDtoHelper === null) {
            $this->accountMakerDtoHelper = AccountMakerDtoHelper::new();
        }
        return $this->accountMakerDtoHelper;
    }

    /**
     * @param AccountMakerDtoHelper $accountMakerDtoHelper
     * @return static
     * @internal
     */
    public function setAccountMakerDtoHelper(AccountMakerDtoHelper $accountMakerDtoHelper): static
    {
        $this->accountMakerDtoHelper = $accountMakerDtoHelper;
        return $this;
    }
}
