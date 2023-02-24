<?php
/**
 *
 * SAM-4814: User Custom Field renderer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-22
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Render\Csv;

/**
 * Trait UserCustomFieldCsvRendererAwareTrait
 * @package Sam\CustomField\User\Render\Csv
 */
trait UserCustomFieldCsvRendererAwareTrait
{
    protected ?UserCustomFieldCsvRenderer $userCustomFieldCsvRenderer = null;

    /**
     * @return UserCustomFieldCsvRenderer
     */
    protected function getUserCustomFieldCsvRenderer(): UserCustomFieldCsvRenderer
    {
        if ($this->userCustomFieldCsvRenderer === null) {
            $this->userCustomFieldCsvRenderer = UserCustomFieldCsvRenderer::new();
        }
        return $this->userCustomFieldCsvRenderer;
    }

    /**
     * @param UserCustomFieldCsvRenderer $userCustomFieldCsvRenderer
     * @return static
     * @internal
     */
    public function setUserCustomFieldCsvRenderer(UserCustomFieldCsvRenderer $userCustomFieldCsvRenderer): static
    {
        $this->userCustomFieldCsvRenderer = $userCustomFieldCsvRenderer;
        return $this;
    }
}
