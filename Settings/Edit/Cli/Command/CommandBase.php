<?php
/**
 * SAM-5843: System Parameters management by CLI script
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Edit\Cli\Command;

use Account;
use RuntimeException;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Symfony\Component\Console\Command\Command;

/**
 * Class CommandBase
 * @package Sam\Settings\Edit\Cli
 */
abstract class CommandBase extends Command
{
    use AccountLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;

    public const NAME = null;

    public function __construct(?string $name = null)
    {
        if ($name === null) {
            $name = static::NAME;
        }
        parent::__construct($name);
    }

    /**
     * @param string $accountInput
     * @return Account[]|array
     * @throws RuntimeException
     */
    protected function retrieveAccounts(string $accountInput): array
    {
        if ($accountInput === 'all') {
            return $this->getAccountLoader()->loadAll(true);
        }
        if ($accountInput === 'main') {
            $accountInput = $this->cfg()->get('core->portal->mainAccountId');
        }
        $account = $this->getAccountLoader()->load((int)$accountInput);
        if (!$account) {
            throw new RuntimeException(sprintf('Account with ID "%s" does not exist', $accountInput));
        }
        return [$account];
    }

    /**
     * Normalize to pascal-case key
     * @param string $key
     * @return string
     */
    protected function normalizeKey(string $key): string
    {
        if (str_contains($key, '_')) {
            // underscore key to pascal-case key
            $key = str_replace('_', '', ucwords(strtolower($key), '_'));
        } else {
            // camel-case to pascal-case
            $key = ucfirst($key);
        }
        return $key;
    }
}
