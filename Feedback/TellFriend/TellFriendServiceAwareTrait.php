<?php
/**
 * SAM-4712 : Refactor Tell Friend service
 * https://bidpath.atlassian.net/browse/SAM-4712
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/9/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feedback\TellFriend;

/**
 * Trait TellFriendServiceAwareTrait
 * @package Sam\Feedback\TellFriend
 */
trait TellFriendServiceAwareTrait
{
    /**
     * @var TellFriendService|null
     */
    protected ?TellFriendService $tellFriendService = null;

    /**
     * @return TellFriendService
     */
    protected function getTellFriendService(): TellFriendService
    {
        if ($this->tellFriendService === null) {
            $this->tellFriendService = TellFriendService::new();
        }
        return $this->tellFriendService;
    }

    /**
     * @param TellFriendService $tellFriendService
     * @return static
     * @internal
     */
    public function setTellFriendService(TellFriendService $tellFriendService): static
    {
        $this->tellFriendService = $tellFriendService;
        return $this;
    }
}
