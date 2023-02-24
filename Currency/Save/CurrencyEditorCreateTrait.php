<?php
/**
 * SAM-4652: Currency editor service
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\Save;

/**
 * Trait CurrencyEditorCreateTrait
 * @package Sam\Currency\Save
 */
trait CurrencyEditorCreateTrait
{
    /**
     * @var CurrencyEditor|null
     */
    protected ?CurrencyEditor $currencyEditor = null;

    /**
     * @return CurrencyEditor
     */
    protected function createCurrencyEditor(): CurrencyEditor
    {
        return $this->currencyEditor ?: CurrencyEditor::new();
    }

    /**
     * @param CurrencyEditor $currencyEditor
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setCurrencyEditor(CurrencyEditor $currencyEditor): static
    {
        $this->currencyEditor = $currencyEditor;
        return $this;
    }
}
