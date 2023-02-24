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

namespace Sam\Report\ImportSample\Lot;

use LotItem;
use Sam\Core\Constants;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\LotFieldConfigProviderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\Map\LotImportCsvFieldMap;
use Sam\Report\ImportSample\ImportSamplerBase;

/**
 * Class LotImportSamplerBase
 * @package Sam\Report\ImportSample\Lot
 */
abstract class LotImportSamplerBase extends ImportSamplerBase
{
    use BaseCustomFieldHelperAwareTrait;
    use ConfigRepositoryAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotFieldConfigProviderAwareTrait;

    protected int $bodyRowCount = 3;
    protected array $sampleValues = [
        Constants\Csv\Lot::ITEM_NUM => ['1', '2', '3'],
        Constants\Csv\Lot::ITEM_FULL_NUMBER => ['1', '2', '3'],
        Constants\Csv\Lot::LOT_NUM => ['1', '2', '3'],
        Constants\Csv\Lot::LOT_FULL_NUMBER => ['1', '2', '3'],
        Constants\Csv\Lot::LOT_NAME => [
            "1958 'Works' MGA Twin Cam Roadster",
            "1960 Jaguar XK150 Drophead Coupe",
            "1970 MGC GT Competition-Spec Race Car with period history",
        ],
        Constants\Csv\Lot::STARTING_BID => [100, 125, 150],
        Constants\Csv\Lot::INCREMENT => ['', '', '10|100:100|1000:1000'],
        Constants\Csv\Lot::BP_SETTING => ['', '', '0:2-0->|500:4-0->|5000:0-5-+'],
        Constants\Csv\Lot::BP_RANGE_CALCULATION => ['', '', LotItem::BP_RANGE_CALCULATION_DEFAULT],
        Constants\Csv\Lot::LOW_ESTIMATE => [100, 125, 150],
        Constants\Csv\Lot::HIGH_ESTIMATE => [500, 600, 700],
        Constants\Csv\Lot::CONSIGNOR_COMMISSION_RANGES => ['0:30-0->|500:70-10->', '', ''],
        Constants\Csv\Lot::CONSIGNOR_COMMISSION_CALCULATION_METHOD => [Constants\ConsignorCommissionFee::CALCULATION_METHOD_SLIDING_NAME, '', ''],
        Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_RANGES => ['0:30-0->|500:70-10->', '', ''],
        Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD => [Constants\ConsignorCommissionFee::CALCULATION_METHOD_TIERED_NAME, '', ''],
        Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_REFERENCE => [Constants\ConsignorCommissionFee::FEE_REFERENCE_ZERO, '', ''],
        Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_RANGES => ['0:30-0->|500:70-10->', '', ''],
        Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD => [Constants\ConsignorCommissionFee::CALCULATION_METHOD_SLIDING_NAME, '', ''],
        Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_REFERENCE => [Constants\ConsignorCommissionFee::FEE_REFERENCE_HIGH_ESTIMATE, '', ''],
    ];
    protected int $accountId;

    public function construct(int $accountId): static
    {
        $this->accountId = $accountId;
        $this->getLotFieldConfigProvider()->setFieldMap(LotImportCsvFieldMap::new());
        return $this;
    }

    /**
     * @param string[] $titles
     * @return static
     */
    public function setTitles(array $titles): static
    {
        $fieldConfigProvider = $this->getLotFieldConfigProvider();
        $titles = array_filter(
            $titles,
            function (string $field) use ($fieldConfigProvider) {
                return $fieldConfigProvider->isVisible($field, $this->accountId);
            },
            ARRAY_FILTER_USE_KEY
        );
        $skipItemTitles = $this->cfg()->get('core->lot->itemNo->concatenated')
            ? [Constants\Csv\Lot::ITEM_NUM, Constants\Csv\Lot::ITEM_NUM_EXT]
            : [Constants\Csv\Lot::ITEM_FULL_NUMBER];
        $skipLotTitles = $this->cfg()->get('core->lot->lotNo->concatenated')
            ? [Constants\Csv\Lot::LOT_NUM_PREFIX, Constants\Csv\Lot::LOT_NUM, Constants\Csv\Lot::LOT_NUM_EXT]
            : [Constants\Csv\Lot::LOT_FULL_NUMBER];
        $titles = array_diff_key($titles, array_flip($skipItemTitles), array_flip($skipLotTitles));
        parent::setTitles($titles);
        return $this;
    }

    /**
     * @return string
     */
    protected function produceContent(): string
    {
        $titles = $this->getTitles();
        [$customFieldTitles, $customFieldValues] = $this->produceCustomFieldCsv();
        $allHeaderTitles = array_merge($titles, $customFieldTitles);
        $headerLine = $this->getReportTool()->makeLine($allHeaderTitles, $this->getEncoding());
        $contentRows[] = $headerLine;

        for ($i = 0; $i < $this->bodyRowCount; $i++) {
            $bodyRow = [];
            foreach (array_keys($titles) as $title) {
                $bodyRow[] = $this->produceValue($title, $i);
            }
            $bodyRow = array_merge($bodyRow, $customFieldValues);
            $bodyLine = $this->getReportTool()->makeLine($bodyRow, $this->getEncoding());
            $contentRows[] = $bodyLine;
        }

        $contentCsv = implode('', $contentRows);
        return $contentCsv;
    }

    /**
     * @return array
     */
    protected function produceCustomFieldCsv(): array
    {
        $fieldConfigProvider = $this->getLotFieldConfigProvider();
        $lotCustomFields = $this->createLotCustomFieldLoader()->loadAll();
        $customFieldValues = [];
        $customFieldTitles = [];
        foreach ($lotCustomFields as $lotCustomField) {
            if (!$fieldConfigProvider->isVisibleCustomField($lotCustomField->Id, $this->accountId)) {
                continue;
            }

            $customFieldTitles[] = $lotCustomField->Name;
            $value = '';
            if ($lotCustomField->Type === Constants\CustomField::TYPE_INTEGER) {
                $value = 5;
            } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_DECIMAL) {
                $value = 250;
            } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_TEXT) {
                $value = $lotCustomField->Unique ? '' : 'text';
            } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_SELECT) {
                $values = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($lotCustomField->Parameters);
                $value = count($values) ? array_shift($values) : '';
            } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_DATE) {
                $value = '1970-01-01 00:00:00';
            } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_FULLTEXT) {
                $value = 'fulltext';
            }
            $customFieldValues[] = $value;
        }
        return [$customFieldTitles, $customFieldValues];
    }
}
