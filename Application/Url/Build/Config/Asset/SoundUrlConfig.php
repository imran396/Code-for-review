<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Asset;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Constants;

/**
 * Class SoundUrlConfig
 * @package Sam\Application\Url\Build\Config
 */
class SoundUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::P_AUDIO_AUCTION_SOUNDS;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Constructors ---

    /**
     * @param int|null $soundId
     * @param int|null $accountId
     * @param array $options
     * @return $this
     */
    public function construct(?int $soundId, ?int $accountId, array $options = []): static
    {
        $options[UrlConfigConstants::PARAMS] = [$soundId, $accountId];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int|null $soundId
     * @param int|null $accountId
     * @param array $options
     * @return $this
     */
    public function forWeb(?int $soundId, ?int $accountId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($soundId, $accountId, $options);
    }

    /**
     * @param int|null $soundId
     * @param int|null $accountId
     * @param array $options
     * @return $this
     */
    public function forRedirect(?int $soundId, ?int $accountId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($soundId, $accountId, $options);
    }

    /**
     * @param int|null $soundId
     * @param int|null $accountId
     * @param array $options
     * @return $this
     */
    public function forDomainRule(?int $soundId, ?int $accountId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($soundId, $accountId, $options);
    }

    /**
     * @param int|null $soundId
     * @param int|null $accountId
     * @param array $options
     * @return $this
     */
    public function forBackPage(?int $soundId, ?int $accountId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($soundId, $accountId, $options);
    }

    // --- Local query methods ---

    /**
     * @return int|null
     */
    public function soundId(): ?int
    {
        return $this->readIntParam(0);
    }

    /**
     * @return int|null
     */
    public function accountId(): ?int
    {
        return $this->readIntParam(1);
    }
}
