<?php
/**
 * Run admin web application after initialization
 *
 * SAM-5677: Extract logic from web entry points index.php
 * SAM-5171: Application layer
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/2/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Index\Admin;

use Sam\Application\Index\Base\Exception\BadRequest;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Index\Base\Concrete\LegacyFrontController;
use Sam\Application\Index\Base\Concrete\DbProfilingLogger;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Infrastructure\Profiling\Web\WebProfilingLoggerCreateTrait;
use Zend_Controller_Exception;

/**
 * Class AdminEntryPointRunner
 * @package
 */
class AdminEntryPointRunner extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use ServerRequestReaderAwareTrait;
    use WebProfilingLoggerCreateTrait;

    protected float $excStartTs = 0.;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function run(): void
    {
        try {
            LegacyFrontController::new()
                ->construct(Ui::new()->constructWebAdmin())
                ->run();
        } catch (Zend_Controller_Exception $e) {
            // TODO: why do we suppress exception output, when it was happened during zf related initializations?
            // at least now we log this info
            log_error($e->getMessage() . composeSuffix(['code' => $e->getCode()]));
            $this->createApplicationRedirector()->processPageNotFound();
        } catch (BadRequest) {
            $this->createApplicationRedirector()->badRequest();
        }

        $this->log();
    }

    /**
     * @param float $excStartTs
     * @return static
     */
    public function setExcStartTs(float $excStartTs): static
    {
        $this->excStartTs = $excStartTs;
        return $this;
    }

    /**
     * Log to file and to stats server
     */
    protected function log(): void
    {
        DbProfilingLogger::new()->log();
        $this->createWebProfilingLogger()->construct()->log($this->excStartTs, 'Profiling admin web');
    }
}
