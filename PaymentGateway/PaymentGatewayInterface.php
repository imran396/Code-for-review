<?php
/**
 * Payment gateway interface
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           July 9, 2020
 */

namespace Sam\PaymentGateway;

use User;
use UserBilling;

/**
 * Interface PaymentGatewayInterface
 * @package Sam\PaymentGateway
 */
interface PaymentGatewayInterface
{
    /**
     * @param bool $isTranslated
     * @return string
     */
    public function getErrorSummary(bool $isTranslated = false): string;

    /**
     * @return bool
     */
    public function isDeclined(): bool;

    /**
     * @return bool
     */
    public function isError(): bool;

    /**
     * @param array $params
     * @param User $user
     * @param UserBilling $userBilling
     * @param bool $wasCcModified
     * @param int $editorUserId
     * @return array|null
     */
    public function save(
        array $params,
        User $user,
        UserBilling $userBilling,
        bool $wasCcModified,
        int $editorUserId
    ): ?array;

    /**
     * @param array $params
     * @return self
     */
    public function validate(array $params): self;

    public function voidLastTransaction(): void;

    /**
     * @return mixed|string
     */
    public function getResponseCode();

    /**
     * @return mixed|string
     */
    public function getResponseText();
}
