<?php
/**
 * SAM-4704: Settlement Editor
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 18, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Save;

/**
 * Trait SettlementEditorAwareTrait
 * @package Sam\Settlement\Save
 */
trait SettlementEditorAwareTrait
{
    protected ?SettlementEditor $settlementEditor = null;

    /**
     * @return SettlementEditor
     */
    protected function getSettlementEditor(): SettlementEditor
    {
        if ($this->settlementEditor === null) {
            $this->settlementEditor = SettlementEditor::new();
        }
        return $this->settlementEditor;
    }

    /**
     * @param SettlementEditor $settlementEditor
     * @return static
     * @internal
     */
    public function setSettlementEditor(SettlementEditor $settlementEditor): static
    {
        $this->settlementEditor = $settlementEditor;
        return $this;
    }
}
