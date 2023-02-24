<?php
/**
 * Immutable value object to store rendering data with some statistics of the config area
 * We should make it simpler, because we don't use its logic.
 * I keep its logic for immutable VO example purpose.
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           2/3/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\WebData\Area\Statistic;

/**
 * Class AreaStatistic
 * @package Sam\Installation\Config
 */
final class AreaStatisticItemWebData
{
    private int $presence;
    private int $absence;
    private string $cssClass;
    private string $title;

    /**
     * @param string $title
     * @param string $cssClass
     * @param int $presence
     * @param int $absence
     */
    public function __construct(string $title, string $cssClass, int $presence, int $absence)
    {
        $this->title = $title;
        $this->cssClass = $cssClass;
        $this->presence = $presence;
        $this->absence = $absence;
    }

    /**
     * @return int
     */
    public function getPresence(): int
    {
        return $this->presence;
    }

    /**
     * @param int $presence
     * @return self
     * @noinspection PhpUnused
     */
    public function withPresence(int $presence): self
    {
        return new self($this->getTitle(), $this->getCssClass(), $presence, $this->getAbsence());
    }

    /**
     * @param int $increment
     * @return self
     */
    public function incrementPresence(int $increment = 1): self
    {
        return new self($this->getTitle(), $this->getCssClass(), $this->getPresence() + $increment, $this->getAbsence());
    }

    /**
     * @return int
     */
    public function getAbsence(): int
    {
        return $this->absence;
    }

    /**
     * @param int $absence
     * @return self
     * @noinspection PhpUnused
     */
    public function withAbsence(int $absence): self
    {
        return new self($this->getTitle(), $this->getCssClass(), $this->getAbsence(), $absence);
    }

    /**
     * @param int $increment
     * @return self
     */
    public function incrementAbsence(int $increment = 1): self
    {
        return new self($this->getTitle(), $this->getCssClass(), $this->getPresence(), $this->getAbsence() + $increment);
    }

    /**
     * @return string
     */
    public function getCssClass(): string
    {
        return $this->cssClass;
    }

    /**
     * @param string $cssClass
     * @return $this
     * @noinspection PhpUnused
     */
    public function withCssClass(string $cssClass): self
    {
        return new self($this->getTitle(), $cssClass, $this->getPresence(), $this->getAbsence());
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     * @noinspection PhpUnused
     */
    public function withTitle(string $title): self
    {
        return new self($title, $this->getCssClass(), $this->getPresence(), $this->getAbsence());
    }
}
