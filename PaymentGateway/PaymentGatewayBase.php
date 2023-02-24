<?php
/**
 * Base payment class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           July 7, 2020
 */

namespace Sam\PaymentGateway;

use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use User;
use UserBilling;

/**
 * Class PaymentBase
 * @package Sam\Payment
 */
abstract class PaymentGatewayBase implements PaymentGatewayInterface
{
    use ConfigRepositoryAwareTrait;
    use TranslatorAwareTrait;

    protected bool $blnApproved = false;
    protected bool $blnDeclined = false;
    protected bool $isError = true;

    protected array $params = [];

    abstract public function getCardCodeResponse();

    abstract public function getResponseCode();

    abstract public function getResponseText();

    public function getErrorSummary(bool $isTranslated = false): string
    {
        $cardTitle = '';
        $title = 'Problem with credit card info';
        if ($isTranslated) {
            $title = $this->isDeclined()
                ? $this->getTranslator()->translate('SIGNUP_ERR_CC_DECLINED', 'user')
                : $this->getTranslator()->translate('SIGNUP_ERR_CC_PROBLEM', 'user');
            $title .= ' ' . $this->getTranslator()->translate('SIGNUP_ERR_CC_CODE', 'user');
            $cardTitle = $this->getTranslator()->translate('SIGNUP_ERR_CC_CREDITCARD', 'user');
        }

        return $title . ': ' . $this->getResponseCode() . ' ' . $this->getResponseText()
            . ($this->getCardCodeResponse() ? $cardTitle . $this->getCardCodeResponse() : '');
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->blnApproved;
    }

    /**
     * @return bool
     */
    public function isDeclined(): bool
    {
        return $this->blnDeclined;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->isError;
    }

    /**
     * @param string $param
     * @param string|mixed $value
     * @return static
     */
    public function setParameter(string $param, mixed $value): static
    {
        $param = trim($param);
        $value = trim((string)$value);
        $this->params[$param] = $value;
        return $this;
    }

    /**
     * @param array $params
     * @return static
     */
    public function setParameters(array $params): static
    {
        foreach ($params as $param => $value) {
            $this->setParameter($param, $value);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function save(
        array $params,
        User $user,
        UserBilling $userBilling,
        bool $wasCcModified,
        int $editorUserId
    ): ?array {
        return null;
    }
}
