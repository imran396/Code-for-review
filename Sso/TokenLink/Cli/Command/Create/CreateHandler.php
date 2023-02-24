<?php
/**
 * SAM-5397: Token Link
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Cli\Command\Create;

use Sam\Core\Cli\HandlerBase;
use Sam\Core\Constants;
use Sam\Sso\TokenLink\Build\TokenLinkBuilderAwareTrait;
use Sam\Sso\TokenLink\Build\TokenLinkEncrypterCreateTrait;
use Sam\Sso\TokenLink\Config\TokenLinkConfiguratorAwareTrait;
use Sam\Sso\TokenLink\Load\TokenLinkDataLoaderCreateTrait;

/**
 * Class CreateHandler
 * @package
 */
class CreateHandler extends HandlerBase
{
    use TokenLinkConfiguratorAwareTrait;
    use TokenLinkBuilderAwareTrait;
    use TokenLinkDataLoaderCreateTrait;
    use TokenLinkEncrypterCreateTrait;

    /** @var int */
    protected int $resultCode = Constants\Cli::EXIT_SUCCESS;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return void
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function handle(): void
    {
        $builder = $this->getTokenLinkBuilder();
        if (!$builder->validate()) {
            $this->output(implode(PHP_EOL, $builder->errorMessages()));
            $this->resultCode = Constants\Cli::EXIT_GENERAL_ERROR;
            return;
        }

        $token = $this->getTokenLinkBuilder()->buildToken();
        $username = $this->getTokenLinkBuilder()->getUsername();
        $message = 'Add to url to login as ' . $username . ': ' . PHP_EOL
            . '?' . $this->getTokenLinkConfigurator()->getTokenParameterName()
            . '=' . rawurlencode($token);
        $this->output($message);
        log_debug($message);
        $this->resultCode = Constants\Cli::EXIT_SUCCESS;
    }

    /**
     * @return int
     */
    public function getResultCode(): int
    {
        return $this->resultCode;
    }

    /**
     * @param string $username
     * @return static
     */
    public function setUsername(string $username): static
    {
        $this->getTokenLinkBuilder()->setUsername($username);
        return $this;
    }

    /**
     * @param int $userId
     * @return static
     */
    public function setUserId(int $userId): static
    {
        $this->getTokenLinkBuilder()->setUserId($userId);
        return $this;
    }
}
