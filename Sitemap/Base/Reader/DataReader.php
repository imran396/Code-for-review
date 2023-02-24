<?php
/**
 * Read data by portions from predefined data source
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           18 Dec, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sitemap\Base\Reader;

use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\DataSource\DataSourceInterface;

/**
 * Class DataReader
 * @package Sam\Sitemap\Base\Reader
 */
class DataReader extends CustomizableClass implements ReaderInterface
{
    use LimitInfoAwareTrait;

    protected int $page;
    protected ?DataSourceInterface $dataSource = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->page = 0;
        $this->setLimit(100);
        return $this;
    }

    /**
     * @return array
     */
    public function read(): array
    {
        $this->dataSource->setLimit($this->getLimit());
        $this->dataSource->setOffset($this->page * $this->getLimit());
        $data = $this->dataSource->getResults();
        $this->page++;
        return $data;
    }

    /**
     * @param DataSourceInterface $dataSource
     * @return static
     */
    public function setDataSource(DataSourceInterface $dataSource): static
    {
        $this->dataSource = $dataSource;
        return $this;
    }
}
