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

namespace Sam\Report\Lot\CustomList\Media\PrintTable;

use CustomLotsTemplateConfig;
use Generator;
use LotItemCustField;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Report\Base\PrintTable\ReporterBase;
use Sam\Report\Lot\CustomList\Load\LotCustomListDataLoader;
use Sam\Report\Lot\CustomList\Media\Base\LotCustomListReporterInterface;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;

/**
 * This class contains methods for generating lot custom list report in HTML format
 *
 * Class LotCustomListPrintTableReporter
 * @package Sam\Report\Lot\CustomList\Media\PrintTable
 */
class LotCustomListPrintTableReporter extends ReporterBase implements LotCustomListReporterInterface
{
    use AccountAwareTrait;
    use LotCustomFieldLoaderCreateTrait;

    private ?LotCustomListDataLoader $customListDataLoader = null;
    private ?LotCustomListPrintTableRowBuilder $rowBuilder = null;
    private ?LotCustomListPrintTableRenderer $renderer = null;
    private ?array $fieldsTitles = null;
    private ?array $lotCustomFields = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function init(
        array $fieldsTitles,
        LotCustomListDataLoader $customListDataLoader,
        CustomLotsTemplateConfig $customLotsTemplateConfig = null,
        array $lotCustomFields = null
    ): static {
        $this->fieldsTitles = $fieldsTitles;
        $this->customListDataLoader = $customListDataLoader;
        $this->lotCustomFields = $lotCustomFields;
        return $this;
    }

    /**
     * @return LotCustomListPrintTableRenderer
     */
    public function getRenderer(): LotCustomListPrintTableRenderer
    {
        if ($this->renderer === null) {
            $this->renderer = LotCustomListPrintTableRenderer::new();
        }
        return $this->renderer;
    }

    /**
     * @inheritDoc
     */
    protected function outputTableStart(): string
    {
        $tableStart = '<table id="lsrf09" class="datagrid borderOne" style="border-width:1px;border-style:solid;">';
        return $this->processOutput($tableStart);
    }

    /**
     * @inheritDoc
     */
    protected function outputTableEnd(): string
    {
        return $this->processOutput('</table>');
    }

    /**
     * @inheritDoc
     */
    protected function outputTitles(): string
    {
        $fieldTitles = $this->getFieldsTitles();
        $output = $this->getRenderer()->renderHeaderRow($fieldTitles);
        return $this->processOutput($output);
    }

    /**
     * During printing, the table is divided into pages and the heading is repeated using css in /css/custom-lots-print.css
     * <css>
     *  table {
     *      page-break-inside: avoid;
     *      page-break-after: always;
     *  }
     *  thead {
     *      display: table-header-group;
     *  }
     * </css>
     * @inheritDoc
     */
    protected function outputBody(): string
    {
        $output = $this->processOutput('<tbody>');
        $fields = $this->getFields();
        foreach ($this->yieldReportData($fields) as $rowData) {
            $row = $this->getRowBuilder()->buildRow($rowData, $fields);
            $rowOutput = $this->getRenderer()->renderRow($row);
            $output .= $this->processOutput($rowOutput);
        }
        $output .= $this->processOutput('</tbody>');
        return $output;
    }

    /**
     * @param array $fields
     * @return Generator
     */
    private function yieldReportData(array $fields): Generator
    {
        return $this->customListDataLoader->yieldRows($fields);
    }

    /**
     * @return LotCustomListPrintTableRowBuilder
     */
    private function getRowBuilder(): LotCustomListPrintTableRowBuilder
    {
        if ($this->rowBuilder === null) {
            $this->rowBuilder = LotCustomListPrintTableRowBuilder::new()
                ->setAccountId($this->getAccountId())
                ->setLotCustomFields($this->getLotCustomFields());
        }
        return $this->rowBuilder;
    }

    /**
     * @return array
     */
    private function getFieldsTitles(): array
    {
        return $this->fieldsTitles;
    }

    /**
     * @return array
     */
    private function getFields(): array
    {
        return array_keys($this->getFieldsTitles());
    }

    /**
     * @return LotItemCustField[]
     */
    private function getLotCustomFields(): array
    {
        if ($this->lotCustomFields === null) {
            $this->lotCustomFields = $this->createLotCustomFieldLoader()->loadAll();
        }
        return $this->lotCustomFields;
    }
}
