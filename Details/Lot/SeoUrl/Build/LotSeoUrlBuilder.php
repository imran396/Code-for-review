<?php
/**
 * Lot Seo Url content builder. Main module
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 * SAM-6595: Templated-content building - simplify module structure for v3.5
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jul 2, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\SeoUrl\Build;

use InvalidArgumentException;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Details\Core\Builder;
use Sam\Details\Core\DataProviderInterface;
use Sam\Details\Lot\SeoUrl\Common\Config\ConfigManagerAwareTrait;
use Sam\Details\Lot\SeoUrl\Build\Internal\Option\Options;
use Sam\Details\Lot\SeoUrl\Build\Internal\DataSource\DataProvider;
use Sam\Details\Lot\SeoUrl\Build\Internal\Template\TemplateDetector;
use Sam\Details\Lot\SeoUrl\Build\Internal\Template\TemplateParser;

/**
 * Class Builder
 * @package Sam\Details
 * @property TemplateParser $templateParser
 */
class LotSeoUrlBuilder extends Builder
{
    use ConfigManagerAwareTrait;

    /**
     * @var int[]
     */
    protected array $auctionLotIds;
    /**
     * @var array|null
     */
    protected ?array $lotRows = null;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function construct(int $serviceAccountId, array $auctionLotIds = []): static
    {
        $this->setSystemAccountId($serviceAccountId);
        $this->auctionLotIds = ArrayCast::makeIntArray($auctionLotIds);
        if (!$this->auctionLotIds) {
            throw new InvalidArgumentException("AuctionLotIds not defined");
        }
        return $this;
    }

    /**
     * @return string[] [<auction lot id> => <content>, ... ]
     */
    public function render(): array
    {
        if (!$this->cfg()->get('core->lot->seoUrl->enabled')) {
            log_trace('SeoUrl generating for lot is disabled');
            return [];
        }
        $this->options = Options::new()->construct($this->getSystemAccountId());
        $this->options->auctionLotIds = $this->auctionLotIds;
        return $this->build();
    }

    public function getDataProvider(): DataProviderInterface
    {
        if ($this->dataProvider === null) {
            $this->dataProvider = DataProvider::new()
                ->setPlaceholderManager($this->getPlaceholderManager())
                ->setOptions($this->options);
        }
        return $this->dataProvider;
    }

    public function getTemplate(): string
    {
        if ($this->template === null) {
            $this->template = TemplateDetector::new()
                ->construct($this->getSystemAccountId())
                ->detect();
        }
        return $this->template;
    }

    public function getTemplateParser(): TemplateParser
    {
        if ($this->templateParser === null) {
            $this->templateParser = TemplateParser::new()
                ->setConfigManager($this->getConfigManager())
                ->setSystemAccountId($this->getSystemAccountId());
        }
        return $this->templateParser;
    }

    /**
     * @return string[]
     */
    protected function build(): array
    {
        $outputs = [];
        $template = $this->getTemplate();
        $placeholders = $this->getPlaceholderManager()->getActualPlaceholders();
        foreach ($this->getLotRows() as $row) {
            $auctionLotId = $row['alid'];
            $outputs[$auctionLotId] = $this->getTemplateParser()->parse($template, $placeholders, $row);
        }
        return $outputs;
    }

    protected function getLotRows(): array
    {
        if ($this->lotRows === null) {
            $this->lotRows = $this->getDataProvider()->load();
        }
        return $this->lotRows;
    }

    /**
     * @internal for injecting data, e.g. in unit tests
     */
    public function setLotRows(array $rows): static
    {
        $this->lotRows = $rows;
        return $this;
    }
}
