<?php
/**
 * SAM-5721: Refactor lot custom field file download for web
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Type\File\Download\Internal\Render;

use Sam\Core\Service\CustomizableClass;
use Laminas\Http\Response;

/**
 * Class Renderer
 * @package ${NAMESPACE}
 */
class Renderer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    public function renderError(int $responseCode): string
    {
        if ($responseCode === Response::STATUS_CODE_500) {
            $header = 'HTTP/1.1 500 Internal Server Error';
            $message = 'HTTP/1.1 500 Internal Server Error';
        } else {
            $header = 'HTTP/1.1 403 Forbidden';
            $message = 'HTTP/1.1 403 Forbidden';
        }
        return $this->buildErrorHtmlTemplate($header, $message);
    }

    /**
     * Html Error page
     * @param string $header
     * @param string $message
     * @return string
     */
    protected function buildErrorHtmlTemplate(string $header, string $message): string
    {
        return <<<HTML
<html>
    <head>
        <title>$header</title>
    </head>
    <body>
        <h1>$message</h1>
    </body>
</html>
HTML;
    }
}
