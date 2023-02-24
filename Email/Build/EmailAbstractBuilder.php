<?php
/** ---- OLD IMPLEMENTATION
 * SAM-5018 : Refactor Email_Template to sub classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Apr 1, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Build;

use AuctionEmailTemplate;
use EmailTemplate;
use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Abstract class that generates Email entity. We inherit from this class on each specific case.
 *
 * Class EmailAbstractBuilder
 * @package Sam\Email
 */
abstract class EmailAbstractBuilder extends CustomizableClass implements EmailBuilderInterface
{
    use SettingsManagerAwareTrait;

    protected int $accountId;
    protected ?string $supportEmail = null;
    protected EmailTemplate|AuctionEmailTemplate $emailTemplate;
    protected ?DataConverterAbstract $dataConverter = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     * @return static
     */
    public function setAccountId(int $accountId): static
    {
        $this->accountId = $accountId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSupportEmail(): string
    {
        if ($this->supportEmail === null) {
            $this->supportEmail = (string)$this->getSettingsManager()->get(Constants\Setting::SUPPORT_EMAIL, $this->accountId);
        }
        return $this->supportEmail;
    }

    /**
     * @param string $supportEmail
     * @return static
     */
    public function setSupportEmail(string $supportEmail): static
    {
        $this->supportEmail = $supportEmail;
        return $this;
    }

    /**
     * @return EmailTemplate|AuctionEmailTemplate
     */
    public function getEmailTemplate(): EmailTemplate|AuctionEmailTemplate
    {
        return $this->emailTemplate;
    }

    /**
     * @param EmailTemplate|AuctionEmailTemplate $emailTemplate
     * @return static
     */
    public function setEmailTemplate(EmailTemplate|AuctionEmailTemplate $emailTemplate): static
    {
        $this->emailTemplate = $emailTemplate;
        return $this;
    }

    /**
     * @return DataConverterAbstract
     */
    public function getDataConverter(): DataConverterAbstract
    {
        if ($this->dataConverter === null) {
            throw new InvalidArgumentException("Please, provide Data Converter");
        }
        return $this->dataConverter;
    }

    /**
     * @param DataConverterAbstract $dataConverter
     * @return static
     */
    public function setDataConverter(DataConverterAbstract $dataConverter): static
    {
        $this->dataConverter = $dataConverter;
        return $this;
    }
}
