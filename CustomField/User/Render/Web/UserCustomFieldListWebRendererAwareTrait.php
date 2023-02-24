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
 * Trait UserCustomFieldListWebRendererAwareTrait
 * @package Sam\CustomField\User\Render\Web
 */
trait UserCustomFieldListWebRendererAwareTrait
{
    /**
     * @var UserCustomFieldListWebRenderer|null
     */
    protected ?UserCustomFieldListWebRenderer $userCustomFieldListWebRenderer = null;

    /**
     * @return UserCustomFieldListWebRenderer
     */
    protected function getUserCustomFieldListWebRenderer(): UserCustomFieldListWebRenderer
    {
        if ($this->userCustomFieldListWebRenderer === null) {
            $this->userCustomFieldListWebRenderer = UserCustomFieldListWebRenderer::new();
        }
        return $this->userCustomFieldListWebRenderer;
    }

    /**
     * @param UserCustomFieldListWebRenderer $userCustomFieldListWebRenderer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setUserCustomFieldListWebRenderer(UserCustomFieldListWebRenderer $userCustomFieldListWebRenderer): static
    {
        $this->userCustomFieldListWebRenderer = $userCustomFieldListWebRenderer;
        return $this;
    }
}
