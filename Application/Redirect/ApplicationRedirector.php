<?php
/**
 * Web context request redirecting helper
 *
 * SAM-5726: Application redirector
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Feb 19, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Redirect;

use QApplication;
use QApplicationBase;
use QCallType;
use QRequestMode;
use QString;
use Sam\Application\Controller\Response\JsonResponse;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\RequestParam\ParamFetcherForPostAwareTrait;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\OutputBuffer\OutputBufferCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class ApplicationRedirector
 * @package Sam\Application\Redirect
 */
class ApplicationRedirector extends CustomizableClass
{
    use OutputBufferCreateTrait;
    use ParamFetcherForPostAwareTrait;
    use SettingsManagerAwareTrait;
    use ServerRequestReaderAwareTrait;
    use SystemAccountAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Return instance of ApplicationRedirector
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Returns a 404 Not found page and the respective http header or redirects to Redirection page for illegal access
     * @param string $redirectUrl
     */
    public function processPageNotFound(string $redirectUrl = ''): void // never
    {
        $accountId = $this->getSystemAccountId();
        $pageRedirection = $this->getSettingsManager()->get(Constants\Setting::PAGE_REDIRECTION, $accountId);
        if (
            QApplicationBase::getRequestMode() !== QRequestMode::Ajax
            && $pageRedirection
        ) {
            $this->redirect($pageRedirection);
        } elseif (
            $this->getParamFetcherForPost()->has(Constants\Qform::FORM_CALL_TYPE)
            && QApplicationBase::getRequestMode() === QRequestMode::Ajax
        ) {
            $this->pageNotFoundForQCodoAjax($redirectUrl);
        } else {
            $this->pageNotFound();
        }
        exit(Constants\Cli::EXIT_GENERAL_ERROR);
    }

    /**
     * @param string $url
     */
    public function redirect(string $url): void // never
    {
        // Clear the output buffer (if any)
        $this->createOutputBuffer()->completeEndClean();

        /**
         * Special for QCodo ajax request handler redirection way.
         * Remove, when QCodo will be obviated.
         */
        $qformFormCallType = $this->getParamFetcherForPost()->getString(Constants\Qform::FORM_CALL_TYPE);
        if (
            QApplication::$RequestMode === QRequestMode::Ajax
            || $qformFormCallType === QCallType::Ajax
        ) {
            // AJAX-based response is in XML Format
            header('Content-Type: text/xml');
            // Output it and update render state
            $js = 'document.location="' . addslashes($url) . '"';
            $js = QString::XmlEscape($js);
            print('<?xml version="1.0"?><response><controls/>'
                . '<commands><command>' . $js . '</command></commands></response>');
        } else {
            // Regular request redirection
            header(sprintf('Location: %s', $url), true, 302);
        }

        exit(Constants\Cli::EXIT_SUCCESS);
    }

    public function redirectToSelf(): void // never
    {
        $requestUri = $this->getServerRequestReader()->requestUri();
        $this->redirect($requestUri);
    }

    public function pageNotFound(string $message = ''): void // never
    {
        http_response_code(404);
        $logData['uri'] = $this->getServerRequestReader()->requestUri();
        if ($message) {
            $logData['message'] = $message;
        }
        $langNotFound = '404 Not Found';
        log_debug($langNotFound . composeSuffix($logData));
        $langPageNotFoundHeader = '404 Page Not Found';
        if (!$this->isAjax()) {
            $messageHtml = $message ? sprintf("<p>%s</p>", $message) : '';
            $langGoToHomepage = 'Go to the homepage';
            echo sprintf(
                '<html lang="en"><head><title>%s</title>'
                . '<body><h1>%s</h1>'
                . '<a href="/">%s</a>'
                . '%s</body></html>',
                $langNotFound,
                $langPageNotFoundHeader,
                $langGoToHomepage,
                $messageHtml
            );
        } else {
            header('Content-Type: application/json ');
            $response = new JsonResponse();
            $response->addErr($langPageNotFoundHeader);
            echo json_encode($response);
        }
        exit(Constants\Cli::EXIT_GENERAL_ERROR);
    }

    /**
     * Response with status 400 - Bad Request
     */
    public function badRequest(): void // never
    {
        http_response_code(400);
        $requestUri = $this->getServerRequestReader()->requestUri();
        log_warning('400 Bad Request' . ($requestUri !== '' ? ': ' . $requestUri : ''));
        if (!$this->isAjax()) {
            echo '<html lang="en"><head><title>400 Bad Request</title>'
                . '<body><h1>400 Bad Request</h1>'
                . '<a href="/">Go to the homepage</a>'
                . '</body></html>';
        } else {
            header('Content-Type: application/json ');
            $response = new JsonResponse();
            $response->addErr("400 Bad Request");
            echo json_encode($response);
        }
        exit(Constants\Cli::EXIT_SUCCESS);
    }

    /**
     * Response with status 403 - Forbidden
     */
    public function forbidden(): void // never
    {
        http_response_code(403);
        $requestUri = $this->getServerRequestReader()->requestUri();
        log_debug('403 Forbidden' . ($requestUri !== '' ? ': ' . $requestUri : ''));
        if (!$this->isAjax()) {
            echo '<html lang="en"><head><title>403 Forbidden</title>'
                . '<body><h1>403 Forbidden</h1>'
                . '<a href="/">Go to the homepage</a>'
                . '</body></html>';
        } else {
            header('Content-Type: application/json ');
            $response = new JsonResponse();
            $response->addErr("403 Bad Request");
            echo json_encode($response);
        }
        exit(Constants\Cli::EXIT_GENERAL_ERROR);
    }

    public function pageNotFoundForQCodoAjax(string $url): void
    {
        $this->createOutputBuffer()->completeEndClean();
        if (QApplicationBase::getRequestMode() === QRequestMode::Ajax) {
            header('Content-Type: text/xml');
            $url = $url ?: "/";
            $url = QString::XmlEscape('document.location="' . $url . '"');
            print('<?xml version="1.0"?><response><controls/>'
                . '<commands><command>' . $url . '</command></commands></response>');
        }
    }

    public function adminAccessDenied(): void // never
    {
        $url = $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forRedirect(Constants\Url::A_ACCESS_ERROR)
        );
        $this->redirect($url);
    }

    public function responsiveAccessDenied(): void // never
    {
        $url = $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forRedirect(Constants\Url::P_ACCESS_ERROR)
        );
        $this->redirect($url);
    }

    /**
     * it's not a 100% method as the headers can be changes on the client side.
     * @return bool
     */
    protected function isAjax(): bool
    {
        return !empty($this->getServerRequestReader()->httpXRequestedWith())
            && strtolower($this->getServerRequestReader()->httpXRequestedWith()) === 'xmlhttprequest';
    }
}
