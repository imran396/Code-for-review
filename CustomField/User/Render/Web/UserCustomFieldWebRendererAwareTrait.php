<?php
/**
 *
 * SAM-5404: User custom field data rendering at web side
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-09-24
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Render\Web;

/**
 * Trait UserCustomFieldWebRendererAwareTrait
 * @package Sam\CustomField\User\Render\Web
 */
trait UserCustomFieldWebRendererAwareTrait
{
    /**
     * @var UserCustomFieldWebRenderer|null
     */
    protected ?UserCustomFieldWebRenderer $userCustomFieldWebRenderer = null;

    /**
     * @return UserCustomFieldWebRenderer
     */
    protected function getUserCustomFieldWebRenderer(): UserCustomFieldWebRenderer
    {
        if ($this->userCustomFieldWebRenderer === null) {
            $this->userCustomFieldWebRenderer = UserCustomFieldWebRenderer::new();
        }
        return $this->userCustomFieldWebRenderer;
    }

    /**
     * @param UserCustomFieldWebRenderer $userCustomFieldWebRenderer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setUserCustomFieldWebRenderer(UserCustomFieldWebRenderer $userCustomFieldWebRenderer): static
    {
        $this->userCustomFieldWebRenderer = $userCustomFieldWebRenderer;
        return $this;
    }
}
