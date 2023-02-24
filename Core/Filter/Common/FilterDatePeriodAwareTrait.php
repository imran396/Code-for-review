<?php
/**
 * SAM-4616: Reports code refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/28/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\Common;

use DateTime;
use Sam\Date\DateAdapter;

/**
 * Trait FilterDatePeriodAwareTrait
 * @package Sam\Core\Filter\Common
 */
trait FilterDatePeriodAwareTrait
{
    protected ?DateAdapter $filterStartDateAdapter = null;
    protected ?DateAdapter $filterEndDateAdapter = null;
    protected bool $isFilterDatePeriod = false;

    // --- Start date methods ---

    /**
     * @return DateTime|null
     */
    public function getFilterStartDateUtc(): ?DateTime
    {
        return $this->getFilterStartDateAdapter()->getDateUtc();
    }

    /**
     * @param DateTime|null $startDateUtc
     * @return static
     */
    public function filterStartDateUtc(?DateTime $startDateUtc): static
    {
        $this->getFilterStartDateAdapter()->setDateUtc($startDateUtc);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterStartDateUtcIso(): ?string
    {
        return $this->getFilterStartDateAdapter()->getDateUtcIso();
    }

    /**
     * @param string|null $dateUtcIso
     * @return static
     */
    public function filterStartDateUtcIso(?string $dateUtcIso): static
    {
        $this->getFilterStartDateAdapter()->setDateUtcIso($dateUtcIso);
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getFilterStartDateSys(): ?DateTime
    {
        $dateSys = $this->getFilterStartDateAdapter()->getDateSys();
        return $dateSys;
    }

    /**
     * @return string|null
     */
    public function getFilterStartDateNoTimeSysIso(): ?string
    {
        $dateSysIso = $this->getFilterStartDateAdapter()->getDateNoTimeSysIso();
        return $dateSysIso;
    }

    /**
     * @param DateTime|null $dateSys
     * @return static
     */
    public function filterStartDateSys(?DateTime $dateSys): static
    {
        $this->getFilterStartDateAdapter()->setDateSys($dateSys);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterStartDateSysIso(): ?string
    {
        $dateSysIso = $this->getFilterStartDateAdapter()->getDateSysIso();
        return $dateSysIso;
    }

    /**
     * @param string|null $dateSysIso
     * @return static
     */
    public function filterStartDateSysIso(?string $dateSysIso): static
    {
        $this->getFilterStartDateAdapter()->setDateSysIso($dateSysIso);
        return $this;
    }

    /**
     * @return DateAdapter
     */
    private function getFilterStartDateAdapter(): DateAdapter
    {
        if ($this->filterStartDateAdapter === null) {
            $this->filterStartDateAdapter = DateAdapter::new();
        }
        return $this->filterStartDateAdapter;
    }

    // --- End date methods ---

    /**
     * @return DateTime|null
     */
    public function getFilterEndDateUtc(): ?DateTime
    {
        return $this->getFilterEndDateAdapter()->getDateUtc();
    }

    /**
     * @param DateTime|null $dateUtc
     * @return static
     */
    public function filterEndDateUtc(?DateTime $dateUtc): static
    {
        $this->getFilterEndDateAdapter()->setDateUtc($dateUtc);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterEndDateUtcIso(): ?string
    {
        $dateUtcIso = $this->getFilterEndDateAdapter()->getDateUtcIso();
        return $dateUtcIso;
    }

    /**
     * @param string|null $dateUtcIso
     * @return static
     */
    public function filterEndDateUtcIso(?string $dateUtcIso): static
    {
        $this->getFilterEndDateAdapter()->setDateUtcIso($dateUtcIso);
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getFilterEndDateSys(): ?DateTime
    {
        return $this->getFilterEndDateAdapter()->getDateSys();
    }

    /**
     * @param DateTime|null $dateSys
     * @return static
     */
    public function filterEndDateSys(?DateTime $dateSys): static
    {
        $this->getFilterEndDateAdapter()->setDateSys($dateSys);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterEndDateSysIso(): ?string
    {
        return $this->getFilterEndDateAdapter()->getDateSysIso();
    }

    /**
     * @return string|null
     */
    public function getFilterEndDateNoTimeSysIso(): ?string
    {
        return $this->getFilterEndDateAdapter()->getDateNoTimeSysIso();
    }

    /**
     * @param string|null $dateSysIso
     * @return static
     */
    public function filterEndDateSysIso(?string $dateSysIso): static
    {
        $this->getFilterEndDateAdapter()->setDateSysIso($dateSysIso);
        return $this;
    }

    /**
     * @return DateAdapter
     */
    private function getFilterEndDateAdapter(): DateAdapter
    {
        if ($this->filterEndDateAdapter === null) {
            $this->filterEndDateAdapter = DateAdapter::new();
        }
        return $this->filterEndDateAdapter;
    }

    // --- isFilterDatePeriod ---

    /**
     * @param bool $isFilterDatePeriod
     * @return static
     */
    public function enableFilterDatePeriod(bool $isFilterDatePeriod): static
    {
        $this->isFilterDatePeriod = $isFilterDatePeriod;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFilterDatePeriod(): bool
    {
        return $this->isFilterDatePeriod;
    }
}
