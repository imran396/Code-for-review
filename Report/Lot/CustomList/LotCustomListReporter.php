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

namespace Sam\Report\Lot\CustomList;

use CustomLotsTemplateConfig;
use LotItemCustField;
use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Report\Lot\CustomList\Load\LotCustomListDataLoader;
use Sam\Report\Lot\CustomList\Load\LotCustomListDataLoaderAwareTrait;
use Sam\Report\Lot\CustomList\Media\Base\LotCustomListReporterInterface;
use Sam\Report\Lot\CustomList\Media\Csv\LotCustomListCsvReporter;
use Sam\Report\Lot\CustomList\Media\PrintTable\LotCustomListPrintTableReporter;
use Sam\Report\Lot\CustomList\Template\Load\CustomLotsTemplateConfigLoaderCreateTrait;
use Sam\Report\Lot\CustomList\Template\LotCustomListTemplate;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * This class is responsible for building lot custom list report in CSV and HTML formats
 *
 * Class LotCustomListReporter
 * @package Sam\Report\Lot\CustomList
 */
class LotCustomListReporter extends CustomizableClass
{
    use ApplicationAccessCheckerCreateTrait;
    use CustomLotsTemplateConfigLoaderCreateTrait;
    use FilterAccountAwareTrait;
    use FilterDatePeriodAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotCustomListDataLoaderAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;
    use UserLoaderAwareTrait;

    public const OUTPUT_CSV = 'csv';
    public const OUTPUT_HTML = 'html';

    private ?array $lotCustomFields = null;
    private ?LotCustomListTemplate $templateManager = null;
    private ?int $templateConfigId = null;
    private ?CustomLotsTemplateConfig $templateConfig = null;
    private ?array $fieldsTitles = null;
    protected ?int $editorUserId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function setEditorUserId(?int $editorUserId): static
    {
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * @param string $outputType csv or html
     * @param bool $isEcho
     * @return string
     */
    public function output(string $outputType, bool $isEcho = false): string
    {
        $reporter = $this
            ->getReporter($outputType)
            ->init(
                $this->getFieldsTitles(),
                $this->prepareDataLoader(),
                $this->getTemplateConfig(),
                $this->getLotCustomFields()
            )
            ->enableEcho($isEcho)
            ->setAccountId($this->detectAccountId());
        return $reporter->output();
    }

    /**
     * @param array $lotCategoryIds
     * @param int $lotCategoryMatch
     * @return static
     */
    public function filterLotCategories(
        array $lotCategoryIds,
        int $lotCategoryMatch = Constants\MySearch::CATEGORY_MATCH_ANY
    ): static {
        if ($lotCategoryIds) {
            $this->getLotCustomListDataLoader()->filterLotCategoryIds($lotCategoryIds, $lotCategoryMatch);
        }
        return $this;
    }

    /**
     * @param int $auctionId
     * @return static
     */
    public function filterAuctionId(int $auctionId): static
    {
        $this->getLotCustomListDataLoader()->filterAuctionId($auctionId);
        return $this;
    }

    /**
     * @param array $lotCustomFieldsFilters
     * @return static
     */
    public function filterCustomFields(array $lotCustomFieldsFilters): static
    {
        $lotCustomFields = $this->getLotCustomFields();
        foreach ($lotCustomFields as $lotCustomField) {
            if (isset($lotCustomFieldsFilters[$lotCustomField->Id])) {
                $this->getLotCustomListDataLoader()->filterCustomField(
                    $lotCustomField,
                    $lotCustomFieldsFilters[$lotCustomField->Id]
                );
            }
        }

        return $this;
    }

    /**
     * @param LotItemCustField[] $lotCustomFields
     * @return static
     */
    public function setLotCustomFields(array $lotCustomFields): static
    {
        $this->lotCustomFields = $lotCustomFields;
        return $this;
    }

    /**
     * @param int|null $templateConfigId
     * @return static
     */
    public function setTemplateConfigId(?int $templateConfigId = null): static
    {
        $this->templateConfigId = $templateConfigId;
        return $this;
    }

    /**
     * @param bool $isIncludeNoHammerPrice
     * @return static
     */
    public function enableIncludeWithoutHammerPrice(bool $isIncludeNoHammerPrice): static
    {
        $this->getLotCustomListDataLoader()->enableIncludeWithoutHammerPrice($isIncludeNoHammerPrice);
        return $this;
    }

    /**
     * @param string $outputType
     * @return LotCustomListReporterInterface
     */
    private function getReporter(string $outputType): LotCustomListReporterInterface
    {
        if ($outputType === static::OUTPUT_CSV) {
            return LotCustomListCsvReporter::new();
        }
        return LotCustomListPrintTableReporter::new();
    }

    /**
     * @return LotCustomListDataLoader
     */
    private function prepareDataLoader(): LotCustomListDataLoader
    {
        $filterAccountId = $this->isAccountFiltering()
            ? $this->getFilterAccountId()
            : $this->detectAccountId();

        $dataLoader = $this->getLotCustomListDataLoader();
        $dataLoader
            ->filterAccountId($filterAccountId)
            ->filterEndDateSysIso($this->getFilterEndDateSysIso())
            ->filterStartDateSysIso($this->getFilterStartDateSysIso());

        if ($this->getSortColumn()) {
            $dataLoader
                ->setSortColumn($this->getSortColumn())
                ->setSortDirection($this->getSortDirection());
        } else {
            [$strOrderColumn, $sortDirection] = $this->getSortOrderOptions();
            $dataLoader
                ->setSortColumn($strOrderColumn)
                ->setSortDirection($sortDirection);
        }

        return $dataLoader;
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

    /**
     * @return LotCustomListTemplate
     */
    private function getTemplateManager(): LotCustomListTemplate
    {
        if (!$this->templateManager) {
            $editorUser = $this->getUserLoader()->load($this->editorUserId, true);
            $this->templateManager = LotCustomListTemplate::new()
                ->setAccountId($this->detectAccountId())
                ->setUser($editorUser);
        }
        return $this->templateManager;
    }

    /**
     * @return CustomLotsTemplateConfig|null
     */
    private function getTemplateConfig(): ?CustomLotsTemplateConfig
    {
        if (!$this->templateConfigId) {
            return null;
        }

        if (!$this->templateConfig) {
            $this->templateConfig = $this->createCustomLotsTemplateConfigLoader()->load($this->templateConfigId, true);
        }
        return $this->templateConfig;
    }

    /**
     * @return int
     */
    private function detectAccountId(): int
    {
        $config = $this->getTemplateConfig();
        return $config->AccountId ?? $this->getSystemAccountId();
    }

    /**
     * @return array
     */
    private function getFieldsTitles(): array
    {
        if ($this->fieldsTitles === null) {
            $this->fieldsTitles = $this->getTemplateManager()->getConfigFields($this->getTemplateConfig(), LotCustomListTemplate::REPORT_CSV);
        }
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
     * @return array
     */
    private function getSortOrderOptions(): array
    {
        return $this->getTemplateManager()->getSortOrderOptions(
            $this->getTemplateConfig(),
            $this->getFields()
        );
    }

    /**
     * @return bool
     */
    public function isAccountFiltering(): bool
    {
        return $this->createApplicationAccessChecker()->isCrossDomainAdminOnMainAccountForMultipleTenant(
            $this->editorUserId,
            $this->getSystemAccountId(),
            true
        );
    }
}
