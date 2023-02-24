<?php
/**
 * SAM-5740 : RTBD connection test
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           03 March, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\SocketBase\Common\Origin;

use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Application\Url\Domain\AccountDomainDetectorCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * It is used to validate origin headers.
 * It prevents cross-origin resource sharing vulnerabilities from ajax request.
 * The allowed ORIGIN header should be the current domain or account related domain like account subdomain or url domain.
 *
 * Class OriginChecker
 * @package Rtb
 */
class OriginChecker extends CustomizableClass
{
    use AccountDomainDetectorCreateTrait;
    use AccountLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;

    /**
     * @var array|null
     */
    protected ?array $allowedDomains = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $originDomain
     * @param int|null $accountId null means account id is absent. It leads to fail validation
     * @return bool
     */
    public function isValidOrigin(string $originDomain, ?int $accountId): bool
    {
        if (!$accountId) {
            return false;
        }
        $allowedDomains = $this->getAllowedDomains($accountId);
        $originDomain = parse_url($originDomain, PHP_URL_HOST);
        return in_array($originDomain, $allowedDomains, true);
    }

    /**
     * Collects allowed domains
     * @param int $accountId
     * @return array
     */
    protected function getAllowedDomains(int $accountId): array
    {
        if ($this->allowedDomains === null) {
            $account = $this->getAccountLoader()->load($accountId);
            $this->allowedDomains[] = $this->cfg()->get('core->app->httpHost');
            if ($account) {
                $this->allowedDomains[] = $this->createAccountDomainDetector()->detectByAccount($account);
            }
        }
        return array_unique($this->allowedDomains);
    }
}
