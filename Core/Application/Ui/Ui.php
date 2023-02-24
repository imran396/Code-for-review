<?php
/**
 * Value-object for UI type
 *
 * SAM-9546: Application layer adjustment for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Application\Ui;

use InvalidArgumentException;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class Ui
 * @package Sam\Core\Application\Ui
 */
class Ui extends CustomizableClass
{
    /** @var int */
    protected int $type;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $type
     * @return $this
     */
    public function construct(int $type): static
    {
        if (!in_array($type, Constants\Application::$uis, true)) {
            throw new InvalidArgumentException("Unknown UI type \"{$type}\"");
        }
        $this->type = $type;
        return $this;
    }

    public function constructWebAdmin(): static
    {
        return $this->construct(Constants\Application::UI_ADMIN);
    }

    public function constructWebResponsive(): static
    {
        return $this->construct(Constants\Application::UI_RESPONSIVE);
    }

    public function constructCli(): static
    {
        return $this->construct(Constants\Application::UI_CLI);
    }

    public function constructByDir(string $dir): static
    {
        $type = array_search($dir, Constants\Application::$uiDirs);
        if (!$type) {
            throw new InvalidArgumentException("Unknown \"dir\" argument value" . composeSuffix(['dir' => $dir]));
        }
        return $this->construct($type);
    }

    public function __toString(): string
    {
        return (string)$this->value();
    }

    public function isWebAdmin(): bool
    {
        return $this->isUiType(Constants\Application::UI_ADMIN);
    }

    public function isWebResponsive(): bool
    {
        return $this->isUiType(Constants\Application::UI_RESPONSIVE);
    }

    public function isCli(): bool
    {
        return $this->isUiType(Constants\Application::UI_CLI);
    }

    public function isWeb(): bool
    {
        return $this->isWebResponsive()
            || $this->isWebAdmin();
    }

    public function dir(): string
    {
        return Constants\Application::$uiDirs[$this->value()];
    }

    public function value(): int
    {
        return $this->type;
    }

    protected function isUiType(int $uiType): bool
    {
        return $this->value() === $uiType;
    }
}
