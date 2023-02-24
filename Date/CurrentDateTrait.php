<?php
/**
 * Trait that allows set and get current date
 * We use it for classes, if we want to test them and replace current date with testing value
 *
 * SAM-4180: CurrentDateTrait integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Date;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Sam\Application\Application;
use Sam\Core\Constants;

/**
 * Trait CurrentDateTrait
 * @package Sam\Core\Date
 */
trait CurrentDateTrait
{
    /**
     * @var DateTime[]
     */
    protected array $currentDateSys = [];
    protected ?DateTime $currentDateUtc = null;

    /**
     * Return Current Date in UTC
     * We use stored value only in case it was pre-defined by setCurrentDateUtc(), else we return current moment date
     * @return DateTime object with current date defined converted to UTC
     */
    public function getCurrentDateUtc(): DateTime
    {
        if ($this->currentDateUtc === null) {
            $currentDateUtc = (new DateTime())->setTimezone(new DateTimeZone('UTC'));
        } else {
            $currentDateUtc = clone $this->currentDateUtc;
        }
        return $currentDateUtc;
    }

    /**
     * Return Immutable Current Date in UTC
     * We use stored value only in case it was pre-defined by setCurrentDateUtc(), else we return current moment date
     * @return DateTimeImmutable object with current date defined converted to UTC
     */
    public function getCurrentDateUtcImmutable(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromMutable($this->getCurrentDateUtc());
    }

    /**
     * Pre-define Current Date UTC value (for unit tests)
     * @param DateTime $currentDateUtc
     * @return static
     */
    public function setCurrentDateUtc(DateTime $currentDateUtc): static
    {
        $this->currentDateUtc = $currentDateUtc;
        return $this;
    }

    /**
     * Return Current Date in account level timezone
     * We use stored value only in case it was pre-defined by setCurrentDateSys(),
     * else we return current moment date in System TZ
     * @param int|null $accountId
     * @return DateTime object with current date time defined.
     */
    public function getCurrentDateSys(?int $accountId = null): DateTime
    {
        $accountId = $this->detectSystemAccountIdForCurrentDateTrait($accountId);
        if (empty($this->currentDateSys[$accountId])) {
            $currentDateSys = DateHelper::new()->convertUtcToSys($this->getCurrentDateUtc(), $accountId);
        } else {
            $currentDateSys = clone $this->currentDateSys[$accountId];
        }
        return $currentDateSys;
    }

    /**
     * @return string
     */
    public function getCurrentDateSysIso(): string
    {
        return $this->getCurrentDateSys()->format(Constants\Date::ISO);
    }

    /**
     * @return string
     */
    public function getCurrentDateNoTimeSysIso(): string
    {
        return $this->getCurrentDateSys()->format('Y-m-d');
    }

    /**
     * Pre-define Current Date in System TZ for account
     * @param DateTime $currentDate
     * @param int|null $accountId
     * @return static
     */
    public function setCurrentDateSys(DateTime $currentDate, ?int $accountId = null): static
    {
        $accountId = $this->detectSystemAccountIdForCurrentDateTrait($accountId);
        $this->currentDateSys[$accountId] = $currentDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentDateUtcIso(): string
    {
        return $this->getCurrentDateUtc()->format(Constants\Date::ISO);
    }

    /**
     * @return string
     */
    public function getCurrentDateNoTimeUtcIso(): string
    {
        return $this->getCurrentDateUtc()->format('Y-m-d');
    }

    /**
     * @param int|null $accountId
     * @return int
     */
    private function detectSystemAccountIdForCurrentDateTrait(?int $accountId = null): int
    {
        if (!$accountId) {
            $accountId = Application::getInstance()->getSystemAccountId();
        }
        return $accountId;
    }
}
