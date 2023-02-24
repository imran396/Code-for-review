<?php
/**
 * SAM-6928: Sales staff user assignment and filtering control adjustments at the "User Edit" and the "Sales Report" pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\AddedBy\Validate;

/**
 * Trait AddedByUserAssignmentValidatorCreateTrait
 * @package Sam\User\AddedBy\Validate
 */
trait AddedByUserAssignmentValidatorCreateTrait
{
    protected ?AddedByUserAssignmentValidator $addedByUserAssignmentValidator = null;

    /**
     * @return AddedByUserAssignmentValidator
     */
    protected function createAddedByUserAssignmentValidator(): AddedByUserAssignmentValidator
    {
        return $this->addedByUserAssignmentValidator ?: AddedByUserAssignmentValidator::new();
    }

    /**
     * @param AddedByUserAssignmentValidator $addedByUserAssignmentValidator
     * @return $this
     * @internal
     */
    public function setAddedByUserAssignmentValidator(AddedByUserAssignmentValidator $addedByUserAssignmentValidator): static
    {
        $this->addedByUserAssignmentValidator = $addedByUserAssignmentValidator;
        return $this;
    }
}
