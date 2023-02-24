<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           2/6/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\EditBlock\EditComponent\EditData;

/**
 * Trait EditComponentDataAwareTrait
 * @package
 */
trait EditComponentDataAwareTrait
{
    /**
     * @var EditComponentData|null
     */
    protected ?EditComponentData $editComponentData = null;

    /**
     * @return EditComponentData
     */
    protected function getEditComponentData(): EditComponentData
    {
        return $this->editComponentData;
    }

    /**
     * @param EditComponentData $editComponentData
     * @return $this
     */
    public function setEditComponentData(EditComponentData $editComponentData): static
    {
        $this->editComponentData = $editComponentData;
        return $this;
    }
}
