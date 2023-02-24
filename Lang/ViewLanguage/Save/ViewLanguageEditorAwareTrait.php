<?php
/**
 * SAM-4749: View language editor
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-02-08
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lang\ViewLanguage\Save;

/**
 * Trait ViewLanguageEditorAwareTrait
 * @package Sam\Lang\ViewLanguage\Save
 */
trait ViewLanguageEditorAwareTrait
{
    protected ?ViewLanguageEditor $viewLanguageEditor = null;

    /**
     * @return ViewLanguageEditor
     */
    protected function getViewLanguageEditor(): ViewLanguageEditor
    {
        if ($this->viewLanguageEditor === null) {
            $this->viewLanguageEditor = ViewLanguageEditor::new();
        }
        return $this->viewLanguageEditor;
    }

    /**
     * @param ViewLanguageEditor $viewLanguageEditor
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setViewLanguageEditor(ViewLanguageEditor $viewLanguageEditor): static
    {
        $this->viewLanguageEditor = $viewLanguageEditor;
        return $this;
    }
}
