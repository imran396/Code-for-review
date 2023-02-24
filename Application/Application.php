<?php
/**
 * SAM-4508: Application class adjustments
 * https://bidpath.atlassian.net/browse/SAM-4508
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/24/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application;

use RuntimeException;
use Sam\Application\Index\Base\Concrete\SystemAccountInitializer;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\Singleton;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class Application
 * @package Sam\Application
 */
class Application extends Singleton
{
    use SystemAccountAwareTrait;

    protected Ui $ui;
    protected string $subDomain;

    /**
     * Get instance of Application
     * @return static
     */
    public static function getInstance(): static
    {
        return parent::_getInstance(self::class);
    }

    /**
     * Initialize the Application instance
     * @param Ui $ui
     * @return static
     */
    public function construct(Ui $ui): static
    {
        $this->ui = $ui;
        $accountInitializer = SystemAccountInitializer::new()->construct($ui);
        $this->subDomain = $accountInitializer->getSubDomain();
        $systemAccountId = $accountInitializer->getSystemAccountId();
        if (!$systemAccountId) {
            throw new RuntimeException('System account cannot be found');
        }
        $this->setSystemAccountId($systemAccountId);

        return $this;
    }

    /**
     * @return Ui
     */
    public function ui(): Ui
    {
        return $this->ui;
    }

    /**
     * @return string
     */
    public function getSubDomain(): string
    {
        return $this->subDomain;
    }
}
