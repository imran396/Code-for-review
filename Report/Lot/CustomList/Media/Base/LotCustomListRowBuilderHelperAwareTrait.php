<?php
/**
 * SAM-4629: Refactor custom lot report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\CustomList\Media\Base;

/**
 * Trait LotCustomListRowBuilderHelperAwareTrait
 * @package Sam\Report\Lot\CustomList\Media\Base
 */
trait LotCustomListRowBuilderHelperAwareTrait
{
    protected ?LotCustomListRowBuilderHelper $lotCustomListRowBuilderHelper = null;

    /**
     * @return LotCustomListRowBuilderHelper
     */
    protected function getLotCustomListRowBuilderHelper(): LotCustomListRowBuilderHelper
    {
        if ($this->lotCustomListRowBuilderHelper === null) {
            $this->lotCustomListRowBuilderHelper = LotCustomListRowBuilderHelper::new();
        }
        return $this->lotCustomListRowBuilderHelper;
    }

    /**
     * @param LotCustomListRowBuilderHelper $lotCustomListRowBuilderHelper
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotCustomListRowBuilderHelper(LotCustomListRowBuilderHelper $lotCustomListRowBuilderHelper): static
    {
        $this->lotCustomListRowBuilderHelper = $lotCustomListRowBuilderHelper;
        return $this;
    }
}
