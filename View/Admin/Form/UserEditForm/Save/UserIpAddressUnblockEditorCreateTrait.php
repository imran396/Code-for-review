<?php
/**
 * User Ip Address Unblock Editor
 *
 * SAM-6286: Refactor User Edit page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 14, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\UserEditForm\Save;

/**
 * Trait UserIpAddressUnblockEditorCreateTrait
 */
trait UserIpAddressUnblockEditorCreateTrait
{
    protected ?UserIpAddressUnblockEditor $userIpAddressUnblockEditor = null;

    /**
     * @return UserIpAddressUnblockEditor
     */
    protected function createUserIpAddressUnblockEditor(): UserIpAddressUnblockEditor
    {
        $userIpAddressUnblockEditor = $this->userIpAddressUnblockEditor ?: UserIpAddressUnblockEditor::new();
        return $userIpAddressUnblockEditor;
    }

    /**
     * @param UserIpAddressUnblockEditor $userIpAddressUnblockEditor
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setUserIpAddressUnblockEditor(UserIpAddressUnblockEditor $userIpAddressUnblockEditor): static
    {
        $this->userIpAddressUnblockEditor = $userIpAddressUnblockEditor;
        return $this;
    }
}
