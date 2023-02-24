<?php
/**
 * This trait for handling optional values related to account of entity that is covered by url
 *
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Base;

use Account;

/**
 * Trait OptionalsAccountAwareTrait
 * @package
 */
trait OptionalAccountAwareTrait
{
    private ?int $accountId = null;
    private ?Account $account = null;

    /**
     * @return int|null
     */
    public function getOptionalAccountId(): ?int
    {
        return $this->accountId;
    }

    /**
     * @return Account|null
     */
    public function getOptionalAccount(): ?Account
    {
        return $this->account;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionalAccount(array $optionals): void
    {
        $this->account = $optionals[UrlConfigConstants::OP_ACCOUNT] ?? null;
        if ($this->account instanceof Account) {
            $this->accountId = $this->account->Id;
        } else {
            $this->accountId = $optionals[UrlConfigConstants::OP_ACCOUNT_ID] ?? null;
        }
    }

    /**
     * @return array
     */
    protected function toArrayOptionalAccount(): array
    {
        return [
            UrlConfigConstants::OP_ACCOUNT => $this->getOptionalAccount(),
            UrlConfigConstants::OP_ACCOUNT_ID => $this->getOptionalAccountId(),
        ];
    }

    /**
     * @param array $options
     */
    protected function applyOptionalAccountOptions(array $options): void
    {
        $this->account = $options[UrlConfigConstants::OP_ACCOUNT] ?? $this->account;
        $this->accountId = $options[UrlConfigConstants::OP_ACCOUNT_ID] ?? $this->accountId;
    }
}
