<?php
/**
 * SAM-4727 : Additional Signup Confirmation Editor and Deleter
 * https://bidpath.atlassian.net/browse/SAM-4727
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/8/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Signup\AdditionalConfirmation\Save;

/**
 * Trait AdditionalSignupConfirmationEditorAwareTrait
 * @package Sam\User\Signup\AdditionalConfirmation\Save
 */
trait AdditionalSignupConfirmationEditorAwareTrait
{
    protected ?AdditionalSignupConfirmationEditor $additionalSignupConfirmationEditor = null;

    /**
     * @return AdditionalSignupConfirmationEditor
     */
    protected function getAdditionalSignupConfirmationEditor(): AdditionalSignupConfirmationEditor
    {
        if ($this->additionalSignupConfirmationEditor === null) {
            $this->additionalSignupConfirmationEditor = AdditionalSignupConfirmationEditor::new();
        }
        return $this->additionalSignupConfirmationEditor;
    }

    /**
     * @param AdditionalSignupConfirmationEditor $additionalSignupConfirmationEditor
     * @return static
     * @internal
     */
    public function setAdditionalSignupConfirmationEditor(AdditionalSignupConfirmationEditor $additionalSignupConfirmationEditor): static
    {
        $this->additionalSignupConfirmationEditor = $additionalSignupConfirmationEditor;
        return $this;
    }
}
