<?php
/**
 * SAM-4629: Refactor custom lot report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 09, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\CustomList\Media\Base;

use CustomLotsTemplateConfig;
use LotItemCustField;
use Sam\Report\Base\ReporterInterface;
use Sam\Report\Lot\CustomList\Load\LotCustomListDataLoader;

/**
 * Interface LotCustomListReporterInterface
 * @package Sam\Report\Lot\CustomList\Output
 */
interface LotCustomListReporterInterface extends ReporterInterface
{
    /**
     * @param array $fieldsTitles
     * @param LotCustomListDataLoader $customListDataLoader
     * @param CustomLotsTemplateConfig|null $customLotsTemplateConfig
     * @param LotItemCustField[]|null $lotCustomFields
     * @return self
     */
    public function init(
        array $fieldsTitles,
        LotCustomListDataLoader $customListDataLoader,
        ?CustomLotsTemplateConfig $customLotsTemplateConfig = null,
        ?array $lotCustomFields = null
    ): self;

    /**
     * @param int|null $id
     * @return self
     */
    public function setAccountId(?int $id): self;

    /**
     * False for disable echo output and get result as string, true for echo output
     * @param bool $isEcho
     * @return self
     */
    public function enableEcho(bool $isEcho);
}
