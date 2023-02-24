<?php
/**
 * SAM-6278: Refactor Mailing Lists Report page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\MailingListsReportForm\Load\Dto;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class MailingListReportDto
 * @package Sam\View\Admin\Form\MailingListsReportForm\Load\Dto
 */
class MailingListReportDto extends CustomizableClass
{
    /** @var ?int */
    public ?int $id = null;
    /** @var string */
    public string $saleNo = '';
    /** @var string */
    public string $auctionName = '';
    /** @var string */
    public string $moneySpentFrom = '';
    /** @var string */
    public string $moneySpentTo = '';
    /** @var int */
    public int $userType;
    /** @var string */
    public string $periodStart = '';
    /** @var string */
    public string $periodEnd = '';
    /** @var string */
    public string $name = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * @param array $row
     * @return static
     */
    public function loadDbRow(array $row): static
    {
        $this->id = (int)$row['id'];
        $this->saleNo = (string)$row['sale_num'];
        $this->auctionName = (string)$row['auction_name'];
        $this->moneySpentFrom = (string)$row['money_spent_from'];
        $this->moneySpentTo = (string)$row['money_spent_to'];
        $this->userType = (int)$row['user_type'];
        $this->periodStart = (string)$row['period_start'];
        $this->periodEnd = (string)$row['period_end'];
        $this->name = (string)$row['name'];
        return $this;
    }

    /**
     * @return int
     */
    public function renderId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function renderAuctionInfo(): string
    {
        return $this->saleNo . ' - ' . $this->auctionName;
    }

    /**
     * @return string
     */
    public function renderMoneySpentFrom(): string
    {
        return $this->moneySpentFrom;
    }

    /**
     * @return string
     */
    public function renderMoneySpentTo(): string
    {
        return $this->moneySpentTo;
    }

    /**
     * @return string
     */
    public function renderUserType(): string
    {
        return Constants\MailingListTemplate::$userTypeNames[$this->userType] ?? '';
    }

    /**
     * @return string
     */
    public function renderPeriodStart(): string
    {
        return $this->periodStart;
    }

    /**
     * @return string
     */
    public function renderPeriodEnd(): string
    {
        return $this->periodEnd;
    }

    /**
     * @return string
     */
    public function renderName(): string
    {
        return ee($this->name);
    }
}
