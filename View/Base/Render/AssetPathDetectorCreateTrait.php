<?php
/**
 * SAM-4586: Refactor CssLinksManager view helper to customized class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/25/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\Render;

/**
 * Trait AssetPathDetectorCreateTrait
 * @package Sam\View\Base\Render
 */
trait AssetPathDetectorCreateTrait
{
    private ?AssetPathDetector $pathDetector = null;

    /**
     * @return AssetPathDetector
     */
    protected function createAssetPathDetector(): AssetPathDetector
    {
        return $this->pathDetector ?: AssetPathDetector::new();
    }

    /**
     * @param AssetPathDetector $pathDetector
     * @return static
     * @internal
     */
    public function setAssetPathDetector(AssetPathDetector $pathDetector): static
    {
        $this->pathDetector = $pathDetector;
        return $this;
    }
}
