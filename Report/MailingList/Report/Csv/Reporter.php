<?php
/**
 *
 * SAM-4751: Refactor mailing list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-16
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\MailingList\Report\Csv;

use Sam\Date\CurrentDateTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoader;
use Sam\Storage\Entity\AwareTrait\MailingListTemplateAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserCustomFieldsAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use UserCustField;

/**
 * Class Reporter
 * @package Sam\Report\MailingList\Report\Csv
 */
class Reporter extends ReporterBase
{
    use AdminPrivilegeCheckerAwareTrait;
    use CurrentDateTrait;
    use FilterAccountAwareTrait;
    use LimitInfoAwareTrait;
    use MailingListTemplateAwareTrait;
    use SortInfoAwareTrait;
    use UserCustomFieldsAwareTrait;

    private const CHUNK_SIZE = 200;

    /** @var RowBuilder|null */
    protected ?RowBuilder $rowBuilder = null;
    /** @var DataLoader|null */
    protected ?DataLoader $dataLoader = null;
    protected ?int $editorUserId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $editorUserId
     * @param int $systemAccountId
     * @return $this
     */
    public function construct(int $editorUserId, int $systemAccountId): Reporter
    {
        $this->editorUserId = $editorUserId;
        $this->setSystemAccountId($systemAccountId);
        return $this;
    }

    /**
     * Overload UserCustomFieldsAwareTrait::getUserCustomFields()
     * @return UserCustField[]
     */
    public function getUserCustomFields(): array
    {
        if ($this->userCustomFields === null) {
            $this->userCustomFields = UserCustomFieldLoader::new()->loadAllEditable([], true);
        }
        return $this->userCustomFields;
    }

    /**
     * @param RowBuilder $renderer
     * @return static
     */
    public function setRowBuilder(RowBuilder $renderer): static
    {
        $this->rowBuilder = $renderer;
        return $this;
    }

    /**
     * Get Output file name
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $currentDateUtc = $this->getCurrentDateUtc();
            $this->outputFileName = 'mailing-list-' . $currentDateUtc->format('m-d-Y-His') . ".csv";
        }
        return $this->outputFileName;
    }

    /**
     * Get DataLoader
     * @return DataLoader
     */
    protected function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new()
                ->filterAccountId($this->getFilterAccountId())
                ->setMailingListTemplateId($this->getMailingListTemplateId())
                ->setSortColumn($this->getSortColumn())
                ->enableAscendingOrder($this->isAscendingOrder())
                ->setChunkSize(self::CHUNK_SIZE);
        }
        return $this->dataLoader;
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $output = '';
        while ($rows = $this->getDataLoader()->loadNextChunk()) {
            foreach ($rows as $row) {
                $bodyRow = $this->getRowBuilder()->buildBodyRow($row);
                $rowOutput = $this->rowToLine($bodyRow);
                $output .= $this->processOutput($rowOutput);
            }
        }
        return $output;
    }

    /**
     * @return RowBuilder
     */
    protected function getRowBuilder(): RowBuilder
    {
        if ($this->rowBuilder === null) {
            $hasPrivilegeForManageCcInfo = $this->getAdminPrivilegeChecker()
                ->enableReadOnlyDb(true)
                ->initByUserId($this->editorUserId)
                ->hasPrivilegeForManageCcInfo();
            $this->rowBuilder = RowBuilder::new()
                ->enablePrivilegeForManageCcInfo($hasPrivilegeForManageCcInfo)
                ->setEncoding($this->getEncoding())
                ->setUserCustomFields($this->getUserCustomFields());
        }
        return $this->rowBuilder;
    }

    /**
     * Output CSV header Titles
     * @return string
     */
    protected function outputTitles(): string
    {
        $headerTitles = $this->getRowBuilder()->buildHeaderLine();
        return $this->processOutput($headerTitles);
    }
}
