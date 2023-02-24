<?php
/**
 * Password strength options
 *
 * SAM-1238: Increased password security
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan, Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           6 Sep, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Password\Strength;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class Options
 */
class Options extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use SettingsManagerAwareTrait;

    protected ?int $minLength = null;
    protected ?int $minLetter = null;
    protected ?int $minNumber = null;
    protected ?int $minSpecialCharacter = null;
    protected bool $isMixedCase = false;
    protected ?int $maxSequentialLetter = null;
    protected ?int $maxConsecutiveLetter = null;
    protected ?int $maxSequentialNumber = null;
    protected ?int $maxConsecutiveNumber = null;
    protected ?int $renewPassword = null;
    protected ?int $historyRepeat = null;
    protected ?int $tmpTimeout = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Set password validation rules by main account settings
     * @return static
     */
    public function initBySystemParametersOfMainAccount(): static
    {
        $sm = $this->getSettingsManager();
        $this->minLength = (int)$sm->getForMain(Constants\Setting::PW_MIN_LEN);
        if ($this->minLength <= 0) {
            $this->minLength = $this->cfg()->get('core->user->credentials->generate->password->defaultLength');
        }
        $this->minLetter = (int)$sm->getForMain(Constants\Setting::PW_MIN_LETTER);
        $this->isMixedCase = (bool)$sm->getForMain(Constants\Setting::PW_REQ_MIXED_CASE);
        $this->minNumber = (int)$sm->getForMain(Constants\Setting::PW_MIN_NUM);
        $this->minSpecialCharacter = (int)$sm->getForMain(Constants\Setting::PW_MIN_SPECIAL);
        $this->maxSequentialLetter = (int)$sm->getForMain(Constants\Setting::PW_MAX_SEQ_LETTER);
        $this->maxConsecutiveLetter = (int)$sm->getForMain(Constants\Setting::PW_MAX_CONSEQ_LETTER);
        $this->maxSequentialNumber = (int)$sm->getForMain(Constants\Setting::PW_MAX_SEQ_NUM);
        $this->maxConsecutiveNumber = (int)$sm->getForMain(Constants\Setting::PW_MAX_CONSEQ_NUM);
        $this->renewPassword = (int)$sm->getForMain(Constants\Setting::PW_RENEW);
        $this->historyRepeat = (int)$sm->getForMain(Constants\Setting::PW_HISTORY_REPEAT);
        $this->tmpTimeout = (int)$sm->getForMain(Constants\Setting::PW_TMP_TIMEOUT);
        return $this;
    }

    /**
     * Return all option values in array
     * @return array
     */
    public function getAll(): array
    {
        return [
            $this->minLength,
            $this->minLetter,
            $this->minNumber,
            $this->minSpecialCharacter,
            $this->isMixedCase,
            $this->maxSequentialLetter,
            $this->maxConsecutiveLetter,
            $this->maxSequentialNumber,
            $this->maxConsecutiveNumber,
            $this->renewPassword,
            $this->historyRepeat,
            $this->tmpTimeout,
        ];
    }

    /**
     * @return int|null
     */
    public function getMinLength(): ?int
    {
        return $this->minLength;
    }

    /**
     * @param int|null $minLength null means random value from 4 to 15
     * @return static
     */
    public function setMinLength(?int $minLength): static
    {
        $this->minLength = Cast::toInt($minLength, Constants\Type::F_INT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinLetter(): ?int
    {
        return $this->minLetter;
    }

    /**
     * @param int|null $minLetter null leads to random int
     * @return static
     */
    public function setMinLetter(?int $minLetter): static
    {
        $this->minLetter = Cast::toInt($minLetter, Constants\Type::F_INT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinNumber(): ?int
    {
        return $this->minNumber;
    }

    /**
     * @param int|null $minNumber null leads to random int
     * @return static
     */
    public function setMinNumber(?int $minNumber): static
    {
        $this->minNumber = Cast::toInt($minNumber, Constants\Type::F_INT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinSpecialCharacter(): ?int
    {
        return $this->minSpecialCharacter;
    }

    /**
     * @param int|null $minSpecialCharacter null leads to random int
     * @return static
     */
    public function setMinSpecialCharacter(?int $minSpecialCharacter): static
    {
        $this->minSpecialCharacter = Cast::toInt($minSpecialCharacter, Constants\Type::F_INT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return bool
     */
    public function isMixedCase(): bool
    {
        return $this->isMixedCase;
    }

    /**
     * @param bool $mixedCase
     * @return static
     */
    public function enableMixedCase(bool $mixedCase): static
    {
        $this->isMixedCase = $mixedCase;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxSequentialLetter(): ?int
    {
        return $this->maxSequentialLetter;
    }

    /**
     * @param int $maxSequentialLetter
     * @return static
     */
    public function setMaxSequentialLetter(int $maxSequentialLetter): static
    {
        $this->maxSequentialLetter = Cast::toInt($maxSequentialLetter, Constants\Type::F_INT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxConsecutiveLetter(): ?int
    {
        return $this->maxConsecutiveLetter;
    }

    /**
     * @param int $maxConsecutiveLetter
     * @return static
     */
    public function setMaxConsecutiveLetter(int $maxConsecutiveLetter): static
    {
        $this->maxConsecutiveLetter = Cast::toInt($maxConsecutiveLetter, Constants\Type::F_INT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxSequentialNumber(): ?int
    {
        return $this->maxSequentialNumber;
    }

    /**
     * @param int $maxSequentialNumber null leads to 0
     * @return static
     */
    public function setMaxSequentialNumber(int $maxSequentialNumber): static
    {
        $this->maxSequentialNumber = Cast::toInt($maxSequentialNumber, Constants\Type::F_INT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxConsecutiveNumber(): ?int
    {
        return $this->maxConsecutiveNumber;
    }

    /**
     * @param int $maxConsecutiveNumber null leads to 0
     * @return static
     */
    public function setMaxConsecutiveNumber(int $maxConsecutiveNumber): static
    {
        $this->maxConsecutiveNumber = Cast::toInt($maxConsecutiveNumber, Constants\Type::F_INT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRenewPassword(): ?int
    {
        return $this->renewPassword;
    }

    /**
     * @param int|null $renewPassword null leads to never renew password
     * @return static
     */
    public function setRenewPassword(?int $renewPassword): static
    {
        $this->renewPassword = Cast::toInt($renewPassword, Constants\Type::F_INT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getHistoryRepeat(): ?int
    {
        return $this->historyRepeat;
    }

    /**
     * @param int|null $historyRepeat null leads to never repeat history
     * @return static
     */
    public function setHistoryRepeat(?int $historyRepeat): static
    {
        $this->historyRepeat = Cast::toInt($historyRepeat, Constants\Type::F_INT_POSITIVE_OR_ZERO);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTmpTimeout(): ?int
    {
        return $this->tmpTimeout;
    }

    /**
     * @param int|null $tmpTimeout null leads to 0
     * @return static
     */
    public function setTmpTimeout(?int $tmpTimeout): static
    {
        $this->tmpTimeout = Cast::toInt($tmpTimeout, Constants\Type::F_INT_POSITIVE_OR_ZERO);
        return $this;
    }
}
