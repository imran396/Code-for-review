<?php
/**
 * Data provider class.
 * It binds relation between placeholder and db fields.
 * It allows to filter, order, paginate fetched result.
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Feb 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Base;

use AuctionCustField;
use InvalidArgumentException;
use Sam\Core\Auction\AuctionList;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\Details\Core\DataProviderInterface;
use Sam\Details\Core\PlaceholderManager;
use Sam\Details\Core\Options;

/**
 * Class DataProvider
 * @package Sam\Details
 * @template DataSourceTemplate of DataSourceMysql
 */
abstract class DataProvider extends CustomizableClass implements DataProviderInterface
{
    /** @var DataSourceTemplate|null */
    protected ?DataSourceMysql $dataSource = null;
    protected ?Options $options = null;
    protected ?PlaceholderManager $placeholderManager = null;
    /**
     * Result set fields required to fetch from db for placeholder rendering
     * Alphabetically ordered
     * @var string[]
     */
    protected array $placeholdersToResultSetFields = [];
    /**
     * Define mandatory fields required to fetch from data source.
     * If we don't define any, this may cause empty loading result.
     * @var string[]
     */
    protected array $requiredResultSetFields = ['id'];

    /**
     * @param DataSourceMysql $dataSource
     */
    abstract protected function initDataSource($dataSource): ?DataSourceMysql;

    public function load(): array
    {
        $dataSource = $this->initDataSource($this->getDataSource());
        // Fetch results
        $rows = [];
        if ($dataSource !== null) {
            $dataSource = $this->getDataSource();
            $rows = AuctionList::new()
                ->setDataSource($dataSource)
                ->getAuctionListArray();
        }
        return $rows;
    }

    /**
     * @return AuctionCustField[]
     */
    protected function getActualAuctionCustomFields(): array
    {
        $actualCustomFields = [];
        $keys = $this->getPlaceholderManager()->getActualCustomFieldPlaceholderKeys();
        foreach ($keys as $key) {
            $customField = $this->getPlaceholderManager()->getConfigManager()
                ->getAuctionCustomFieldByPlaceholderKey($key);
            $actualCustomFields[$key] = $customField;
        }
        return $actualCustomFields;
    }

    protected function collectResultSetFields(): array
    {
        $resultSetFields = [];
        foreach ($this->getPlaceholderManager()->getActualPlaceholders() as $actualPlaceholder) {
            if ($actualPlaceholder->getType() === Constants\Placeholder::COMPOSITE) {
                $placeholders = $actualPlaceholder->getSubPlaceholders();
            } else {
                $placeholders = [$actualPlaceholder];
            }
            foreach ($placeholders as $placeholder) {
                $key = $placeholder->getKey();
                if (!empty($this->placeholdersToResultSetFields[$key])) {
                    $fields = (array)$this->placeholdersToResultSetFields[$key];
                    $resultSetFields = array_merge($resultSetFields, $fields);
                }
            }
        }
        $resultSetFields = array_merge($resultSetFields, $this->requiredResultSetFields);
        return array_unique($resultSetFields);
    }

    protected function completePlaceholdersToResultSetFields(): void
    {
        // take known from config
        $configsGroupedByType = $this->getPlaceholderManager()->getConfigManager()->getKeysConfig();
        foreach ($configsGroupedByType as $configs) {
            foreach ($configs as $key => $options) {
                if (!empty($options['select'])) {
                    $this->placeholdersToResultSetFields[$key] = is_array($options['select'])
                        ? $options['select'] : [$options['select']];
                }
            }
        }
        // add for auction custom fields actual for template
        $dbTransformer = DbTextTransformer::new();
        foreach ($this->getActualAuctionCustomFields() as $key => $customField) {
            $alias = 'c' . $dbTransformer->toDbColumn($customField->Name);
            // 'id' - we use auction id as argument of customized rendering method
            $this->placeholdersToResultSetFields[$key] = [$alias, 'id'];
        }
    }

    public function getOptions(): ?Options
    {
        return $this->options;
    }

    public function setOptions(Options $options): static
    {
        $this->options = $options;
        return $this;
    }

    public function getDataSource(): DataSourceMysql
    {
        if ($this->dataSource === null) {
            $dataSource = DataSourceMysql::new();
            $this->setDataSource($dataSource);
        }
        return $this->dataSource;
    }

    /**
     * @param DataSourceMysql $dataSource
     * @return static
     */
    public function setDataSource(DataSourceMysql $dataSource): static
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    public function getPlaceholderManager(): PlaceholderManager
    {
        if ($this->placeholderManager === null) {
            throw new InvalidArgumentException("PlaceholderManager not defined");
        }
        return $this->placeholderManager;
    }

    public function setPlaceholderManager(PlaceholderManager $placeholderManager): static
    {
        $this->placeholderManager = $placeholderManager;
        return $this;
    }
}
