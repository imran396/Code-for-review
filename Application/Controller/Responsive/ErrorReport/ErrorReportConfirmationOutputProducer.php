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

use Sam\Core\Service\CustomizableClass;

/**
 * Class ErrorReportConfirmationOutputProducer
 * @package Sam\Application\Controller\Responsive\ErrorReport
 */
class ErrorReportConfirmationOutputProducer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produce(): void
    {
        echo <<<HTML
<html>
    <head>
        <title>Thank you for helping us</title>
        <style>
            *{margin:0;padding:0}
            html,body {margin:0px; padding:0px; font-family:Arial;font-size:12px;background-color: #fff;}
            form{   margin-left: 20px; margin-right: 20px;}
            .main {margin:20px auto;padding: 0px; width:800px; min-height: 400px; text-align: center;}
        </style>
    </head>
    <body>
      <div class="main">
          <h1>Thank you for helping us!</h1>
          <p><strong>Your email was sent.</strong></p>
          <p>We appreciate your feedback.</p>
          <form action="/">
                <input type="submit" value="Back to home page">
          </form>
      </div>
    </body>
</html>
HTML;
    }
}
