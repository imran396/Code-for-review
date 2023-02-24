<?php
/**
 * SAM-4702: User Log modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.02.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\User\Log\Save;

/**
 * Trait UserLogEditorAwareTrait
 * @package Sam\User\Log\Save
 */
trait UserLogEditorAwareTrait
{
    protected ?UserLogEditor $userLogEditor = null;

    /**
     * @return UserLogEditor
     */
    protected function getUserLogEditor(): UserLogEditor
    {
        if ($this->userLogEditor === null) {
            $this->userLogEditor = UserLogEditor::new();
        }
        return $this->userLogEditor;
    }

    /**
     * @param UserLogEditor $userLogEditor
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setUserLogEditor(UserLogEditor $userLogEditor): static
    {
        $this->userLogEditor = $userLogEditor;
        return $this;
    }
}
