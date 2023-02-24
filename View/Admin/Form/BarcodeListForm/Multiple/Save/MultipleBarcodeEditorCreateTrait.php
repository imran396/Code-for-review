<?php
/**
 * Multiple Barcode Editor Create Trait
 *
 * SAM-5876: Refactor data loader for Barcode List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 4, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BarcodeListForm\Multiple\Save;

/**
 * Trait MultipleBarcodeEditorCreateTrait
 */
trait MultipleBarcodeEditorCreateTrait
{
    protected ?MultipleBarcodeEditor $multipleBarcodeEditor = null;

    /**
     * @return MultipleBarcodeEditor
     */
    protected function createMultipleBarcodeEditor(): MultipleBarcodeEditor
    {
        $multipleBarcodeEditor = $this->multipleBarcodeEditor ?: MultipleBarcodeEditor::new();
        return $multipleBarcodeEditor;
    }

    /**
     * @param MultipleBarcodeEditor $multipleBarcodeEditor
     * @return $this
     * @noinspection PhpUnused
     * @internal
     */
    public function setMultipleBarcodeEditor(MultipleBarcodeEditor $multipleBarcodeEditor): static
    {
        $this->multipleBarcodeEditor = $multipleBarcodeEditor;
        return $this;
    }
}
