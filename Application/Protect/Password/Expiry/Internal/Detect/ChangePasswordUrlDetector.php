<?php
/**
 * Pure domain logic for detecting redirect url to Change Password page.
 *
 * SAM-7615: Unit test for password expiry protector
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Password\Expiry\Internal\Detect;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Service\Optional\OptionalsTrait;

/**
 * Class ChangePasswordUrlDetector
 * @package Sam\Application\Protect\Password\Internal\Detect
 */
class ChangePasswordUrlDetector extends CustomizableClass
{
    use OptionalsTrait;
    use ResultStatusCollectorAwareTrait;

    // --- Incoming values ---

    public const OP_SKIP_CONTROLLERS = 'skipControllers';

    // --- Outgoing values ---

    public const INFO_PASSWORD_CHANGE_NOT_REQUIRED = 1;
    public const INFO_SKIP_CONTROLLER = 2;

    /** @var string[] */
    protected const INFO_MESSAGES = [
        self::INFO_PASSWORD_CHANGE_NOT_REQUIRED => 'Password change is not required',
        self::INFO_SKIP_CONTROLLER => 'Skip controller passed',
    ];

    public const OK_FOUND_RESPONSIVE_URL = 11;
    public const OK_FOUND_ADMIN_URL = 12;

    protected const SUCCESS_MESSAGES = [
        self::OK_FOUND_RESPONSIVE_URL => 'Found redirect url for responsive side',
        self::OK_FOUND_ADMIN_URL => 'Found redirect url for admin side',
    ];

    // --- Internal values ---

    protected const SKIP_CONTROLLERS_DEF = [
        Constants\ResponsiveRoute::C_CHANGE_PASSWORD,
        Constants\ResponsiveRoute::C_LOGOUT,
        Constants\AdminRoute::C_MANAGE_CHANGE_PASSWORD,
        Constants\AdminRoute::C_MANAGE_LOGOUT,
    ];

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    // --- Main methods ---

    /**
     * @param Ui $ui
     * @param string $controllerName
     * @param bool $isPasswordChangeRequired
     * @return AbstractUrlConfig
     */
    public function detectUrlConfig(
        Ui $ui,
        string $controllerName,
        bool $isPasswordChangeRequired
    ): ?AbstractUrlConfig {
        $collector = $this->getResultStatusCollector()->construct()
            ->initAllSuccesses(static::SUCCESS_MESSAGES)
            ->initAllInfos(static::INFO_MESSAGES);

        if (!$isPasswordChangeRequired) {
            $collector->addInfo(self::INFO_PASSWORD_CHANGE_NOT_REQUIRED);
            return null;
        }

        $skipControllers = $this->fetchOptional(self::OP_SKIP_CONTROLLERS);
        if (in_array($controllerName, $skipControllers, true)) {
            $collector->addInfo(self::INFO_SKIP_CONTROLLER);
            return null;
        }

        if ($ui->isWebResponsive()) {
            $collector->addSuccess(self::OK_FOUND_RESPONSIVE_URL);
            return ZeroParamUrlConfig::new()->forRedirect(Constants\Url::P_CHANGE_PASSWORD);
        }

        $collector->addSuccess(self::OK_FOUND_ADMIN_URL);
        return ZeroParamUrlConfig::new()->forRedirect(Constants\Url::A_CHANGE_PASSWORD);
    }

    // --- Outgoing results ---

    /**
     * @return int[]
     */
    public function infoCodes(): array
    {
        return $this->getResultStatusCollector()->getInfoCodes();
    }

    /**
     * @return int[]
     */
    public function successCodes(): array
    {
        return $this->getResultStatusCollector()->getSuccessCodes();
    }

    // --- Internal logic ---

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_SKIP_CONTROLLERS] = $optionals[self::OP_SKIP_CONTROLLERS]
            ?? self::SKIP_CONTROLLERS_DEF;
        $this->setOptionals($optionals);
    }
}
