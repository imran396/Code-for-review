<?php
/**
 * Multiple Barcode Editor
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

use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\BarcodeListForm\Single\Save\SingleBarcodeUpdaterCreateTrait;

/**
 * Class MultipleBarcodeEditor
 */
class MultipleBarcodeEditor extends CustomizableClass
{
    use DbConnectionTrait;
    use SingleBarcodeUpdaterCreateTrait;

    protected array $updatedData;
    protected string $report = '';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $updatedData
     * @return $this
     */
    public function construct(array $updatedData): static
    {
        $this->updatedData = $updatedData;
        return $this;
    }

    /**
     * @param string $report
     * @return $this
     */
    protected function addReport(string $report): static
    {
        $this->report .= $report;
        return $this;
    }

    /**
     * @return string
     */
    public function getReport(): string
    {
        return $this->report;
    }

    /**
     * Update Auction Lots status id for multiple barcode
     * @param int $editorUserId
     */
    public function update(int $editorUserId): void
    {
        $singleUpdater = $this->createSingleBarcodeUpdater();
        foreach ($this->updatedData as $data) {
            $singleUpdater->construct($data['cf']->id, $data['value'], $data['cf']->name)
                ->setReport('')
                ->update($editorUserId);
            $this->addReport($singleUpdater->getReport());
        }
    }
}
