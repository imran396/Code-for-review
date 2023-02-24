<?php
/**
 * SAM-6068: Issue related to "Show content from all account" on a portal account
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\CrossAccountTransparency;

/**
 * @package Sam\Account\CrossAccountTransparency
 */
trait CrossAccountTransparencyCheckerCreateTrait
{
    /**
     * @var CrossAccountTransparencyChecker|null
     */
    protected ?CrossAccountTransparencyChecker $crossAccountTransparencyChecker = null;

    /**
     * @return CrossAccountTransparencyChecker
     */
    protected function createCrossAccountTransparencyChecker(): CrossAccountTransparencyChecker
    {
        return $this->crossAccountTransparencyChecker ?: CrossAccountTransparencyChecker::new();
    }

    /**
     * @param CrossAccountTransparencyChecker $crossAccountTransparencyChecker
     * @return $this
     * @internal
     */
    public function setCrossAccountTransparencyChecker(CrossAccountTransparencyChecker $crossAccountTransparencyChecker): static
    {
        $this->crossAccountTransparencyChecker = $crossAccountTransparencyChecker;
        return $this;
    }
}
