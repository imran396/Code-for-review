<?php
/**
 * Store date and supply different accessors with converting functionality
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/8/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Date;

use Sam\Core\Service\CustomizableClass;
use DateTime;
use Sam\Core\Constants;

/**
 * Class DateAdapter
 * @package Sam\Core\Date
 */
class DateAdapter extends CustomizableClass
{
    protected ?DateTime $dateUtc = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return DateTime|null
     */
    public function getDateUtc(): ?DateTime
    {
        return $this->dateUtc;
    }

    /**
     * @param DateTime|null $dateUtc
     * @return static
     */
    public function setDateUtc(?DateTime $dateUtc): static
    {
        $this->dateUtc = $dateUtc;
        return $this;
    }

    /**
     * @return int
     */
    public function getDateUtcTimestamp(): int
    {
        return $this->dateUtc ? $this->dateUtc->getTimestamp() : 0;
    }

    /**
     * @return string|null
     */
    public function getDateUtcIso(): ?string
    {
        $dateUtcIso = $this->getDateUtc()?->format(Constants\Date::ISO);
        return $dateUtcIso;
    }

    /**
     * @param string|null $dateUtcIso
     * @return static
     */
    public function setDateUtcIso(?string $dateUtcIso): static
    {
        $dateUtcIso = trim((string)$dateUtcIso);
        $dateUtc = $dateUtcIso ? new DateTime($dateUtcIso) : null;
        $this->setDateUtc($dateUtc);
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDateSys(): ?DateTime
    {
        $dateSys = $this->getDateUtc() ? DateHelper::new()->convertUtcToSys($this->getDateUtc()) : null;
        return $dateSys;
    }

    /**
     * @return int
     */
    public function getDateSysTimestamp(): int
    {
        $dateSys = $this->getDateSys();
        return $dateSys ? $dateSys->getTimestamp() : 0;
    }

    /**
     * @return string|null
     */
    public function getDateNoTimeSysIso(): ?string
    {
        $dateSysIso = $this->getDateSys() ? $this->getDateSys()->format('Y-m-d') : null;
        return $dateSysIso;
    }

    /**
     * @param DateTime|null $dateSys
     * @return static
     */
    public function setDateSys(?DateTime $dateSys): static
    {
        $dateUtc = $dateSys ? DateHelper::new()->convertSysToUtc($dateSys) : null;
        $this->setDateUtc($dateUtc);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDateSysIso(): ?string
    {
        $dateSysIso = $this->getDateSys() ? $this->getDateSys()->format(Constants\Date::ISO) : null;
        return $dateSysIso;
    }

    /**
     * @param string|null $dateSysIso
     * @return static
     */
    public function setDateSysIso(?string $dateSysIso): static
    {
        $dateSysIso = trim((string)$dateSysIso);
        $dateSys = $dateSysIso ? new DateTime($dateSysIso) : null;
        $this->setDateSys($dateSys);
        return $this;
    }
}
