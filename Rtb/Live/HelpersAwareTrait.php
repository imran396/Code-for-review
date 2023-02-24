<?php

namespace Sam\Rtb\Live;

use Sam\Rtb\Messenger;
use Sam\Rtb\Log\Logger;

/**
 * Trait HelpersAwareTrait
 * @package Sam\Rtb\Live
 */
trait HelpersAwareTrait
{
    protected ?ResponseHelper $responseHelper = null;
    protected ?Messenger $messenger = null;
    protected ?Logger $rtbLogger = null;

    /**
     * @return ResponseHelper
     */
    public function getResponseHelper(): ResponseHelper
    {
        if ($this->responseHelper === null) {
            $this->responseHelper = ResponseHelper::new();
        }
        return $this->responseHelper;
    }

    /**
     * @param ResponseHelper $responseHelper
     * @return static
     */
    public function setResponseHelper($responseHelper): static
    {
        $this->responseHelper = $responseHelper;
        return $this;
    }

    /**
     * @return Messenger
     */
    public function getMessenger(): Messenger
    {
        if ($this->messenger === null) {
            $this->messenger = Messenger::new();
        }
        return $this->messenger;
    }

    /**
     * @param Messenger $messenger
     * @return static
     */
    public function setMessenger($messenger): static
    {
        $this->messenger = $messenger;
        return $this;
    }

    /**
     * @return Logger
     */
    public function getLogger(): Logger
    {
        return $this->rtbLogger;
    }

    /**
     * @param Logger $logger
     * @return static
     */
    public function setLogger($logger): static
    {
        $this->rtbLogger = $logger;
        return $this;
    }
}
