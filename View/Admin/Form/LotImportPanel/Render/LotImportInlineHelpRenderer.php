<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 23, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotImportPanel\Render;

use InlineHelp;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Lot\LotFieldConfig\Provider\LotFieldConfigProviderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\Map\LotImportCsvFieldMap;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class LotImportInlineHelpRenderer
 * @package Sam\View\Admin\Form\LotImportPanel\Render
 */
class LotImportInlineHelpRenderer extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotFieldConfigProviderAwareTrait;
    use OptionalsTrait;

    public const OP_ITEM_NO_CONCATENATED = 'itemNoConcatenated';
    public const OP_LOT_NO_CONCATENATED = 'lotNoConcatenated';
    public const OP_COLUMN_HEADERS = 'columnHeaders';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->getLotFieldConfigProvider()->setFieldMap(LotImportCsvFieldMap::new());
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Get tooltip string
     * @param int $accountId
     * @return string
     */
    public function renderForInventory(int $accountId): string
    {
        return $this->makeInlineHelp('inventory', $accountId);
    }

    /**
     * Get tooltip string
     * @param int $accountId
     * @return string
     */
    public function renderForTimedAuction(int $accountId): string
    {
        return $this->makeInlineHelp('timed', $accountId);
    }

    /**
     * Get tooltip string
     * @param int $accountId
     * @return string
     */
    public function renderForLiveAuction(int $accountId): string
    {
        return $this->makeInlineHelp('live', $accountId);
    }

    /**
     * @param string $type
     * @param int $accountId
     * @return string
     */
    protected function makeInlineHelp(string $type, int $accountId): string
    {
        $columnHeaders = $this->detectColumnHeaders($type);
        $fieldInfo = array_merge(
            $this->makeFieldsInfo($columnHeaders, $type, $accountId),
            $this->makeCustomFieldsInfo($accountId)
        );
        $message = implode(', ', $fieldInfo);

        $label = $this->getAdminTranslator()->trans('import.csv.column_format.label');
        return "{$label}:<br/>{$message}";
    }

    /**
     * @param array $titles
     * @param string $type
     * @param int $accountId
     * @return array
     */
    protected function makeFieldsInfo(array $titles, string $type, int $accountId): array
    {
        $fieldConfigProvider = $this->getLotFieldConfigProvider();
        $fieldInfo = [];
        foreach ($titles as $field => $title) {
            if (!$fieldConfigProvider->isVisible($field, $accountId)) {
                continue;
            }
            $fieldFormat = $this->detectFieldFormat($field, $type);
            if ($fieldFormat) {
                $required = $fieldConfigProvider->isRequired($field, $accountId) ? 'required' : 'optional';
                $fieldInfo[] = "{$title} ({$required}, {$fieldFormat})";
            }
        }
        return $fieldInfo;
    }

    protected function makeCustomFieldsInfo(int $accountId): array
    {
        $fieldConfigProvider = $this->getLotFieldConfigProvider();
        $lotCustomFields = $this->createLotCustomFieldLoader()->loadAll();
        $fieldInfo = [];
        foreach ($lotCustomFields as $lotCustomField) {
            if (!$fieldConfigProvider->isVisibleCustomField($lotCustomField->Id, $accountId)) {
                continue;
            }
            $dataType = '';
            if ($lotCustomField->Type === Constants\CustomField::TYPE_INTEGER) {
                $dataType = 'int';
            } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_DECIMAL) {
                $dataType = 'float';
            } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_TEXT) {
                $dataType = 'varchar';
            } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_SELECT) {
                $dataType = 'varchar';
            } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_DATE) {
                $dataType = 'format:1970-01-01 00:00:00';
            } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_FILE) {
                $dataType = 'pipe | separated list of file names';
            } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_POSTALCODE) {
                $dataType = '5 digits';
            }
            $required = $fieldConfigProvider->isRequiredCustomField($lotCustomField->Id, $accountId) ? 'required' : 'optional';
            $fieldInfo[] = "{$lotCustomField->Name} ({$required}, {$dataType})";
        }
        return $fieldInfo;
    }

    /**
     * @param string $field
     * @param string $type
     * @return string
     */
    protected function detectFieldFormat(string $field, string $type): string
    {
        $inlineHelp = InlineHelp::getInstance();
        $inlineHelp->setSection('admin_lots_upload');
        $inlineHelp->setKey("admin_{$type}_{$field}");
        $fieldFormat = $inlineHelp->getMessage();
        return $fieldFormat;
    }

    /**
     * @param string $type
     * @return string[]
     */
    protected function detectColumnHeaders(string $type): array
    {
        $fields = $this->fetchOptional(self::OP_COLUMN_HEADERS, [$type]);
        $fields = $this->excludeConcatenatedFields($fields);
        return $fields;
    }

    /**
     * Exclude concatenated fields
     * @param string[] $fields
     * @return string[]
     */
    protected function excludeConcatenatedFields(array $fields): array
    {
        $exclude = [];
        if ($this->fetchOptional(self::OP_ITEM_NO_CONCATENATED)) {
            $exclude[Constants\Csv\Lot::ITEM_NUM] = null;
            $exclude[Constants\Csv\Lot::ITEM_NUM_EXT] = null;
        } else {
            $exclude[Constants\Csv\Lot::ITEM_FULL_NUMBER] = null;
        }
        if ($this->fetchOptional(self::OP_LOT_NO_CONCATENATED)) {
            $exclude[Constants\Csv\Lot::LOT_NUM] = null;
            $exclude[Constants\Csv\Lot::LOT_NUM_EXT] = null;
            $exclude[Constants\Csv\Lot::LOT_NUM_PREFIX] = null;
        } else {
            $exclude[Constants\Csv\Lot::LOT_FULL_NUMBER] = null;
        }
        return array_diff_key($fields, $exclude);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_ITEM_NO_CONCATENATED] = $optionals[self::OP_ITEM_NO_CONCATENATED]
            ?? static function (): bool {
                return ConfigRepository::getInstance()->get('core->lot->itemNo->concatenated');
            };
        $optionals[self::OP_LOT_NO_CONCATENATED] = $optionals[self::OP_LOT_NO_CONCATENATED]
            ?? static function (): bool {
                return ConfigRepository::getInstance()->get('core->lot->lotNo->concatenated');
            };
        $optionals[self::OP_COLUMN_HEADERS] = $optionals[self::OP_COLUMN_HEADERS]
            ?? static function (string $type): array {
                return ConfigRepository::getInstance()->get("csv->admin->{$type}")->toArray();
            };
        $this->setOptionals($optionals);
    }
}
