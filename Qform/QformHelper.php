<?php
/**
 * General helper for qform web components
 *
 * SAM-9773: Register JS calls at server side by QformHelper
 * SAM-4075: Helper methods for GET parameters filtering
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           15 Apr, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * TODO: IK: Probably, we will decompose it in future
 */

namespace Sam\Qform;

use QApplication;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Qform\Messenger\AdminMessengerCreateTrait;

/**
 * Class QformHelper
 * @package Sam\Qform
 */
class QformHelper extends CustomizableClass
{
    use AdminMessengerCreateTrait;
    use ServerRequestReaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Clear warning messages for passed controls
     * @param array $controls
     */
    public function clearWarnings(array $controls): void
    {
        foreach ($controls as $control) {
            if ($control->Warning) {
                $control->Warning = null;
            }
        }
    }

    public function executeJs(string $js): static
    {
        QApplication::ExecuteJavaScript($js);
        return $this;
    }

    public function executeOnPageLoadJs(string $js): static
    {
        $js = sprintf('$(() => { %s });', $js);
        QApplication::ExecuteJavaScript($js);
        return $this;
    }

    public function executeHighPriorityJs(string $js): static
    {
        QApplication::ExecuteJavaScript($js, true);
        return $this;
    }

    public function initEntryPointJs(): static
    {
        return $this->executeJs('sam.initEntryPoint();');
    }

    public function alertJs(string $message): static
    {
        return $this->executeJs(sprintf("alert('%s');", addslashes($message)));
    }

    public function redirectJs(string $url): static
    {
        return $this->executeJs(sprintf("sam.redirect('%s');", addslashes($url)));
    }

    public function redirectToBlankJs(string $url): static
    {
        return $this->executeJs(sprintf("sam.redirect('%s', target='_blank');", addslashes($url)));
    }

    public function redirectByTimeout(string $url, int $interval): static
    {
        $redirectJs = sprintf("sam.redirect('%s');", addslashes($url));
        return $this->executeJs(sprintf("setTimeout(()=>{%s}, %d)", $redirectJs, $interval));
    }

    public function redirectToSelf(): static
    {
        return $this->redirectJs($this->getServerRequestReader()->requestUri());
    }

    /**
     * @param string|null $message
     * @param string $messageType
     * @return static
     */
    public function redirectToSelfWithAlert(?string $message = null, string $messageType = 'info'): static
    {
        if (!empty($message)) {
            $this->alertJs($message);
            $this->createAdminMessenger()->addMessage($message, $messageType);
        }
        return $this->redirectToSelf();
    }
}
