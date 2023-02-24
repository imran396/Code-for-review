<?php

namespace Sam\Rtb\Base;

use Sam\Rtb\Log\Logger;
use Sam\Rtb\Messenger;

/**
 * Interface IHelpersAware
 * @package Sam\Rtb\Base
 */
interface IHelpersAware
{
    /**
     * @return ResponseHelper
     */
    public function getResponseHelper(): ResponseHelper;

    /**
     * @param ResponseHelper $responseHelper
     * @return static
     */
    public function setResponseHelper($responseHelper): static;

    /**
     * @return Messenger
     */
    public function getMessenger(): Messenger;

    /**
     * @param Messenger $messenger
     * @return static
     */
    public function setMessenger($messenger): static;

    /**
     * @return Logger
     */
    public function getLogger(): Logger;

    /**
     * @param Logger $logger
     * @return static
     */
    public function setLogger($logger): static;
}
