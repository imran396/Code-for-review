<?php
/**
 * SAM-4647: Refactor csv import sample builders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/23/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\ImportSample\Lot\Admin;

use Sam\Core\Constants;
use Sam\Report\ImportSample\Lot\LotImportSamplerBase;

/**
 * Class InventoryItemImportSampler
 * @package Sam\Report\ImportSample\Lot\Admin
 */
class InventoryItemImportSampler extends LotImportSamplerBase
{
    protected ?string $outputFileName = 'inventory-items.csv';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function output(): string
    {
        $this->setTitles($this->cfg()->get('csv->admin->inventory')->toArray());
        $this->sendHttpHeader();
        $this->outputContent();
        return '';
    }

    /**
     * @return void
     */
    protected function outputContent(): void
    {
        $this->sampleValues[Constants\Csv\Lot::ITEM_NUM] = ['766987', '766988', '766989'];
        echo $this->produceContent();
    }
}
