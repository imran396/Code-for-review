<?php
/**
 * SAM-6690: Add "Exclude closed lots" option to Live/Hybrid auctions
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\FilterPanel\Calculate\Internal;


/**
 * Trait AdvancedSearchFilterPanelPureCheckerCreateTrait
 * @package Sam\View\Responsive\Form\AdvancedSearch\FilterPanel\Calculate\Internal
 * @internal
 */
trait AdvancedSearchFilterPanelPureCheckerCreateTrait
{
    protected ?AdvancedSearchFilterPanelPureChecker $advancedSearchFilterPanelPureChecker = null;

    /**
     * @return AdvancedSearchFilterPanelPureChecker
     */
    protected function createAdvancedSearchFilterPanelPureChecker(): AdvancedSearchFilterPanelPureChecker
    {
        return $this->advancedSearchFilterPanelPureChecker ?: AdvancedSearchFilterPanelPureChecker::new();
    }

    /**
     * @param AdvancedSearchFilterPanelPureChecker $advancedSearchFilterPanelPureChecker
     * @return static
     * @internal
     */
    public function setAdvancedSearchFilterPanelPureChecker(AdvancedSearchFilterPanelPureChecker $advancedSearchFilterPanelPureChecker): static
    {
        $this->advancedSearchFilterPanelPureChecker = $advancedSearchFilterPanelPureChecker;
        return $this;
    }
}
