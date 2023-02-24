<?php
/**
 * SAM-10237: Make fixes on "Local configuration files management page" page ( /admin/installation-setting/)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           02-13, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\WebData\Area\LocalConfig;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollection;
use Sam\Installation\Config\Edit\Meta\Descriptor\MissingDescriptor;
use Sam\Installation\Config\Edit\Render\DeleteBlockRenderer;

/**
 * Class OptionDeleteControlsRenderer
 * @package Sam\Installation\Config\Edit\Render\WebData\Area\LocalConfig
 */
class OptionDeleteControlsRenderer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $optionKey
     * @param DescriptorCollection $descriptorCollection
     * @return string
     */
    public function renderOptionDeleteButtonHtml(string $optionKey, DescriptorCollection $descriptorCollection): string
    {
        $output = $this->isAllowToRenderDeleteControls($optionKey, $descriptorCollection)
            ? DeleteBlockRenderer::new()->renderDeleteButton($descriptorCollection->getConfigName(), $optionKey)
            : '';
        return $output;
    }

    /**
     * @param string $optionKey
     * @param DescriptorCollection $descriptorCollection
     * @return string
     */
    public function renderOptionDeleteCheckboxHtml(string $optionKey, DescriptorCollection $descriptorCollection): string
    {
        $output = $this->isAllowToRenderDeleteControls($optionKey, $descriptorCollection)
            ? DeleteBlockRenderer::new()->renderDeleteCheckbox($optionKey)
            : '';
        return $output;
    }

    /**
     * Is allow to render a delete controls HTML (delete button and checkbox)
     * We allow these controls for:
     *  a. Missing options
     *  b. Actual deletable and editable options
     *
     * @param string $optionKey
     * @param DescriptorCollection $descriptorCollection
     * @return bool
     */
    protected function isAllowToRenderDeleteControls(string $optionKey, DescriptorCollection $descriptorCollection): bool
    {
        $metaDescriptor = $descriptorCollection->get($optionKey);
        $isDeletable = $metaDescriptor->isDeletable();
        $isEditable = $metaDescriptor->isEditable();
        $isMissingOption = $metaDescriptor instanceof MissingDescriptor;
        $isActualEditableEndDeletableOption = !$metaDescriptor instanceof MissingDescriptor
            && $isEditable
            && $isDeletable;

        return $isMissingOption || $isActualEditableEndDeletableOption;
    }
}
