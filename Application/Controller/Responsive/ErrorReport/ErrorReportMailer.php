<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\ErrorReport;

use Exception;
use Sam\Application\Controller\Responsive\ErrorReport\Internal\EmailContentRendererCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Email\Email;
use Sam\Email\Queue\ActionQueueAwareTrait;
use Sam\Email\Queue\ActionQueueDto;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class ErrorReportMailer
 * @package Sam\Application\Controller\Responsive\ErrorReport
 */
class ErrorReportMailer extends CustomizableClass
{
    use ActionQueueAwareTrait;
    use ConfigRepositoryAwareTrait;
    use EmailContentRendererCreateTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function send(ReportData $reportData): bool
    {
        try {
            $mailFrom = (string)$this->getSettingsManager()->getForSystem(Constants\Setting::SUPPORT_EMAIL);
            $mailTo = $this->cfg()->get('core->app->errorFriendlyPage->email');
            if (!$mailFrom || !$mailTo) {
                return false;
            }

            $message = $this->createEmailContentRenderer()->render($reportData);
            $email = Email::new()
                ->setFrom($mailFrom)
                ->setTo($mailTo)
                ->setSubject('SAM2 Crash user feedback')
                ->setHtmlBody($message);
            $dto = ActionQueueDto::new()
                ->setEmail($email)
                ->setAccountId($this->getSystemAccountId());
            $this->getActionQueue()->add(
                $dto,
                Constants\ActionQueue::MEDIUM,
                $this->getUserLoader()->loadSystemUserId()
            );
            return true;
        } catch (Exception $e) {
            log_error('sending mail failed: ' . $e->getMessage());
            return false;
        }
    }
}
