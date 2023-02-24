<?php
/**
 * SAM-9888: Check Printing for Settlements: Bulk Checks Processing - Account level, Settlements List level (Part 2)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementCheckCreateBatchForm\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Content\Build\SettlementCheckAllContent;

/**
 * Class NewSettlementCheckContent
 * @package Sam\View\Admin\Form\SettlementCheckCreateBatchForm\Load
 */
class NewSettlementCheckContent extends CustomizableClass
{
    public readonly int $settlementId;
    public readonly ?int $settlementNo;
    public readonly SettlementCheckAllContent $content;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $settlementId, ?int $settlementNo, SettlementCheckAllContent $content): static
    {
        $this->settlementId = $settlementId;
        $this->settlementNo = $settlementNo;
        $this->content = $content;
        return $this;
    }
}
