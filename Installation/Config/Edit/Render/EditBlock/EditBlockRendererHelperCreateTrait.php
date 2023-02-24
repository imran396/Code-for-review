<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-11, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\EditBlock;

/**
 * Trait EditBlockRendererHelperCreateTrait
 * @package Sam\Installation\Config
 */
trait EditBlockRendererHelperCreateTrait
{
    /**
     * @var EditBlockRendererHelper|null
     */
    protected ?EditBlockRendererHelper $editBlockRendererHelper = null;

    /**
     * @return EditBlockRendererHelper
     */
    protected function createEditBlockRendererHelper(): EditBlockRendererHelper
    {
        $editBlockRendererHelper = $this->editBlockRendererHelper ?: EditBlockRendererHelper::new();
        return $editBlockRendererHelper;
    }

    /**
     * @param EditBlockRendererHelper $editBlockRendererHelper
     * @return static
     * @noinspection PhpUnused
     */
    public function setEditBlockRendererHelper(EditBlockRendererHelper $editBlockRendererHelper): static
    {
        $this->editBlockRendererHelper = $editBlockRendererHelper;
        return $this;
    }
}
