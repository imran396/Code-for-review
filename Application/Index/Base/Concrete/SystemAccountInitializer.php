<?php
/**
 * SAM-5171: Application layer
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/24/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Index\Base\Concrete;

use Account;
use Sam\Account\Validate\AccountExistenceCheckerAwareTrait;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Image\LinkPrefix\ImageLinkPrefixAnalyserCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;

/**
 * Class SystemAccountInitializer
 * @package Sam\Application
 */
class SystemAccountInitializer extends CustomizableClass
{
    use AccountExistenceCheckerAwareTrait;
    use AccountLoaderAwareTrait;
    use ApplicationRedirectorCreateTrait;
    use AuthIdentityManagerCreateTrait;
    use ConfigRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use ImageLinkPrefixAnalyserCreateTrait;
    use ServerRequestReaderAwareTrait;
    use SystemAccountAwareTrait;

    /** @var Ui */
    protected Ui $ui;
    /** @var string */
    protected string $subDomain = '';

    /**
     * Get instance of Application
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Initialize the Application instance
     * @param Ui $ui
     * @return static
     */
    public function construct(Ui $ui): static
    {
        $this->ui = $ui;
        // Assign main account by default, eg. for CLI
        $this->setSystemAccountId((int)$this->cfg()->get('core->portal->mainAccountId'));
        if ($ui->isWeb()) {
            $this->initializeDomainInfo();
            $this->initializeSystemAccount();
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getSubDomain(): string
    {
        return $this->subDomain;
    }

    /**
     * @param string $httpHost
     * @return string
     */
    protected function extractSubDomainFromHost(string $httpHost): string
    {
        $subDomain = '';
        if ($this->cfg()->get('core->portal->urlHandling') === Constants\PortalUrlHandling::SUBDOMAINS) {
            $position = strpos($httpHost, '.');
            $subDomain = substr($httpHost, 0, $position);
        }
        return $subDomain;
    }

    protected function initializeDomainInfo(): void
    {
        $baseDomain = $this->cfg()->get('core->app->httpHost');
        $httpHost = $this->getServerRequestReader()->httpHost();
        if (empty($httpHost)) {
            $this->getServerRequestReader()->writeHttpHost($baseDomain);
        }

        if ($httpHost !== $baseDomain) {
            if ($this->cfg()->get('core->portal->enabled')) {
                $this->subDomain = $this->extractSubDomainFromHost($httpHost);
            } else {
                // Allow only link prefixes for main account and for default key
                $systemAccountId = $this->detectAccountIdByImageLinkPrefix();
                if ($systemAccountId !== $this->cfg()->get('core->portal->mainAccountId')) {
                    $this->createApplicationRedirector()->redirect('http://' . $baseDomain);
                }
            }
        }
    }

    /**
     * Initialize systsem account in one of the possible ways
     * - by image link prefix
     * - by request domain
     */
    protected function initializeSystemAccount(): void
    {
        $addLogs = [];
        if ($this->initializeSystemAccountByImageLinkPrefix()) {
            $addLogs = ['system acc. found' => 'by image link prefix'];
        } elseif ($this->initializeSystemAccountByRequestDomain()) {
            $addLogs = ['system acc. found' => 'by request domain'];
        }
        $this->logAccountSettingsInitialization($addLogs);
    }

    protected function initializeSystemAccountByRequestDomain(): bool
    {
        if ($this->cfg()->get('core->portal->enabled')) {
            $httpHost = $this->getServerRequestReader()->httpHost();
            $baseDomain = $this->cfg()->get('core->app->httpHost');
            if ($httpHost !== $baseDomain) {
                $domainAccount = $this->detectAccountFromUrl();
                if (!$domainAccount) {
                    $portalName = $this->getSubDomain() !== ''
                        ? 'Portal name: ' . $this->getSubDomain()
                        : 'Portal domain: ' . $httpHost;
                    log_debug($portalName . ' not found!');
                    $this->createApplicationRedirector()->redirect('http://' . $baseDomain);
                }
                $this->setSystemAccountId($domainAccount->Id);
                return true;
            }

            if (
                $this->ui->isWebAdmin()
                && $this->createAuthIdentityManager()->isAuthorized()
            ) {
                $editorUser = $this->getEditorUser();
                if (!$editorUser) {
                    log_error("Available user not found for authenticated user id" . composeSuffix(['u' => $this->getEditorUserId()]));
                    return false;
                }

                /**
                 * Assign user's account, if he is not superadmin and he comes to admin side at main account domain.
                 */
                $hasPrivilegeForSuperadmin = $this->getEditorUserAdminPrivilegeChecker()->hasPrivilegeForSuperadmin();
                if (!$hasPrivilegeForSuperadmin) {
                    $this->setSystemAccountId($editorUser->AccountId);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return Account|null
     */
    protected function detectAccountFromUrl(): ?Account
    {
        if ($this->getSubDomain() !== '') {
            $portalName = $this->getSubDomain();
            $account = $this->getAccountLoader()->loadByName($portalName);
        } else {
            $account = $this->getAccountLoader()->loadByUrlDomain($this->getServerRequestReader()->httpHost());
        }
        return $account;
    }

    /**
     * Detect if request host and path match image link prefix,
     * if so initialize system account by respective prefix account,
     * if it request goes for default, then keep main system account (SAM-6637)
     * @return bool
     */
    protected function initializeSystemAccountByImageLinkPrefix(): bool
    {
        $accountId = $this->detectAccountIdByImageLinkPrefix();
        if ($accountId !== null) {
            $this->setSystemAccountId($accountId);
            return true;
        }
        return false;
    }

    /**
     * Determine account key from core->image->linkPrefix (SAM-6637)
     * @return int|null
     */
    protected function detectAccountIdByImageLinkPrefix(): ?int
    {
        $httpHost = $this->getServerRequestReader()->httpHost();
        $requestUri = $this->getServerRequestReader()->requestUri();
        $accountKey = $this->createImageLinkPrefixAnalyser()
            ->construct()
            ->detectAccountKey($httpHost, $requestUri);
        if ($accountKey !== null) {
            if (is_int($accountKey)) {
                $isFound = $this->getAccountExistenceChecker()->existById($accountKey, true);
                if ($isFound) {
                    return $accountKey;
                }
            } else/*if ($accountKey === Constants\Image::LP_DEFAULT)*/ {
                return $this->cfg()->get('core->portal->mainAccountId');
            }
        }
        return null;
    }

    /**
     * Log current domain settings
     * @param array $addLogs
     */
    protected function logAccountSettingsInitialization(array $addLogs): void
    {
        $baseDomain = $this->cfg()->get('core->app->httpHost');
        $subDomain = $this->getSubDomain();
        $host = ($subDomain !== '' ? $subDomain . '.' : '') . $baseDomain;
        $logData = [
                'SystemAccountId' => $this->getSystemAccountId(),
                'OnMain' => $this->isMainSystemAccount(),
                'OnPortal' => $this->isPortalSystemAccount(),
                'UiDir' => $this->ui->dir(),
                'Host' => $host,
                'BaseDomain' => $baseDomain,
                'SubDomain' => $subDomain,
                'portal->enabled' => $this->cfg()->get('core->portal->enabled'),
                'portal->urlHandling' => $this->cfg()->get('core->portal->urlHandling'),
                'portal->domainAuctionVisibility' => $this->cfg()->get('core->portal->domainAuctionVisibility'),
                session_name() => session_id(),
                'RequestUri' => $this->getServerRequestReader()->requestUri(),
            ] + $addLogs;
        log_debug("Application account settings initialized" . composeSuffix($logData, ' | '));
    }
}
