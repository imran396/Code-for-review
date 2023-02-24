<?php
/**
 * Lot Info page details output builder. Main module
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 * SAM-6595: Templated-content building - simplify module structure for v3.5
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 9, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\Web\Base\Build;

use InvalidArgumentException;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Details\Core\DataProviderInterface;
use Sam\Details\Lot\Base\Render\TemplateParser;
use Sam\Details\Lot\Web\Base\Build\Internal\DataSource\DataProvider;
use Sam\Details\Lot\Web\Base\Build\Internal\Option\Options;
use Sam\Details\Lot\Web\Base\Config\ConfigManager;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Category\Template\LotCategoryTemplateLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;

/**
 * Class Builder
 * @package Sam\Details
 * @property TemplateParser $templateParser
 */
abstract class Builder extends \Sam\Details\Core\Builder
{
    use EditorUserAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCategoryTemplateLoaderAwareTrait;
    use LotItemAwareTrait;
    use LotItemLoaderAwareTrait;

    public const OP_LOT_CATEGORIES = 'lotCategories';

    /** @var Options|null */
    protected ?\Sam\Details\Core\Options $options;
    protected ?array $lotRow = null;

    public function render(): string
    {
        return $this->build();
    }

    /**
     * It depends on lot's categories
     * @param array $optionals [
     *    self::OP_LOT_CATEGORIES => LotCategory[]
     * ]
     */
    protected function initConfigManager(ConfigManager $lotConfigManager, array $optionals = []): void
    {
        $lotCategories = $optionals[self::OP_LOT_CATEGORIES]
            ?? $this->getLotCategoryLoader()->loadForLot($this->getLotItemId(), true);
        $lotCategoryIds = $this->getLotCategoryLoader()
            ->loadCategoryWithAncestorIdsForCategories($lotCategories, true);
        $lotConfigManager
            ->setLotCustomFieldCategoryIds($lotCategoryIds)
            ->construct();
        $this->setConfigManager($lotConfigManager);
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

    public function getTemplateParser(): TemplateParser
    {
        if ($this->templateParser === null) {
            $this->templateParser = TemplateParser::new()
                ->enableHideEmptyFields($this->shouldHideEmptyFields())
                ->setConfigManager($this->getConfigManager())
                ->setSystemAccountId($this->getSystemAccountId())
                ->setEscapingTool($this->getEscapingTool())
                ->setUserId($this->options->userId);
        }
        return $this->templateParser;
    }

    protected function init(int $systemAccountId, int $lotItemId, ?int $auctionId = null): void
    {
        $lotItem = $this->getLotItemLoader()->load($lotItemId, true);
        if ($lotItem === null) {
            throw new InvalidArgumentException("LotItem not found" . composeSuffix(['li' => $lotItemId]));
        }
        $this->setLotItem($lotItem);
        $this->setSystemAccountId($systemAccountId);

        $this->options = Options::new()->construct($systemAccountId);
        $this->options->auctionId = $auctionId;
        $this->options->lotItemId = $lotItemId;
        $this->options->userId = $this->getEditorUserId();
    }

    protected function build(): string
    {
        $template = $this->getTemplate();
        $placeholders = $this->getPlaceholderManager()->getActualPlaceholders();
        $row = $this->getLotRow();
        return $this->getTemplateParser()->parse($template, $placeholders, $row);
    }

    protected function getLotRow(): array
    {
        if ($this->lotRow === null) {
            $rows = $this->getDataProvider()->load();
            $this->lotRow = $rows ? $rows[0] : [];
        }
        return $this->lotRow;
    }

    public function setLotRow(array $row): static
    {
        $this->lotRow = $row;
        return $this;
    }
}
