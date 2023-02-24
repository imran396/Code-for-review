<?php
/**
 * Base class for builder with common logic
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
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

namespace Sam\Details\Core;

use InvalidArgumentException;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Core\Render\EscapingTool;
use Sam\Details\Core\Render\EscapingToolInterface;
use Sam\Details\Core\Render\TemplateParser;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class Builder
 * @package Sam\Details
 */
abstract class Builder extends CustomizableClass implements ConfigManagerAwareInterface
{
    use ConfigRepositoryAwareTrait;
    use SystemAccountAwareTrait;

    protected ?DataProviderInterface $dataProvider = null;
    protected ?Options $options = null;
    protected ?PlaceholderManager $placeholderManager = null;
    protected ?bool $shouldHideEmptyFields = true;
    protected ?int $systemAccountId = null;
    protected ?string $template = null;
    protected ?TemplateParser $templateParser = null;
    protected bool $isProfiling = false;
    protected ?EscapingToolInterface $escapingTool = null;
    /**
     * In Pre-building mode we can parse only observable placeholders,
     * because others should be built on-the-fly right before rendering (SAM-4304).
     */
    protected bool $isPreBuilding = false;

    /**
     * Return data provider
     */
    public function getDataProvider(): DataProviderInterface
    {
        if ($this->dataProvider === null) {
            throw new InvalidArgumentException("DataProvider not defined");
        }
        return $this->dataProvider;
    }

    public function setDataProvider(DataProviderInterface $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }

    public function shouldHideEmptyFields(): bool
    {
        if ($this->shouldHideEmptyFields === null) {
            throw new InvalidArgumentException('HideEmptyFields not defined');
        }
        return $this->shouldHideEmptyFields;
    }

    /**
     * Enable/disable "Hide Empty Field" option
     */
    public function enableHideEmptyFields(bool $enabled): static
    {
        $this->shouldHideEmptyFields = $enabled;
        return $this;
    }

    public function getPlaceholderManager(): PlaceholderManager
    {
        if ($this->placeholderManager === null) {
            $this->placeholderManager = PlaceholderManager::new()
                ->setConfigManager($this->getConfigManager())
                ->setTemplate($this->getTemplate());
        }
        return $this->placeholderManager;
    }

    public function setPlaceholderManager(PlaceholderManager $placeholderManager): static
    {
        $this->placeholderManager = $placeholderManager;
        return $this;
    }

    /**
     * Return output template
     */
    public function getTemplate(): string
    {
        if ($this->template === null) {
            throw new InvalidArgumentException('Template not defined');
        }
        return $this->template;
    }

    public function setTemplate(string $template): static
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Return special to entity template parser
     */
    public function getTemplateParser(): TemplateParser
    {
        if ($this->templateParser === null) {
            throw new InvalidArgumentException("TemplateParser not defined");
        }
        return $this->templateParser;
    }

    public function setTemplateParser(TemplateParser $templateParser): static
    {
        $this->templateParser = $templateParser;
        return $this;
    }

    public function isProfiling(): bool
    {
        return $this->isProfiling;
    }

    public function enableProfiling(bool $isProfiling): static
    {
        $this->isProfiling = $isProfiling;
        return $this;
    }

    public function setEscapingTool(EscapingToolInterface $escapingTool): static
    {
        $this->escapingTool = $escapingTool;
        return $this;
    }

    public function getEscapingTool(): EscapingToolInterface
    {
        if ($this->escapingTool === null) {
            $this->escapingTool = EscapingTool::new()
                ->enableProfiling($this->isProfiling());
        }
        return $this->escapingTool;
    }

    public function enablePreBuilding(bool $isPreBuilding): static
    {
        $this->isPreBuilding = $isPreBuilding;
        return $this;
    }
}
