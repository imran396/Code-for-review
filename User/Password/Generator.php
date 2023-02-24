<?php
/**
 * Password generator according to password strength rules
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

namespace Sam\User\Password;

use Sam\Core\Service\CustomizableClass;
use Sam\User\Password;
use Sam\User\Password\Strength\OptionsAwareTrait;

/**
 * Class Generator
 */
class Generator extends CustomizableClass
{
    use OptionsAwareTrait;

    protected ?Password\Strength\Validator $validator = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Generate random password
     * @return string
     */
    public function generate(): string
    {
        $minLength = $this->getOptions()->getMinLength();
        $minLetter = $this->getOptions()->getMinLetter();
        $minNumber = $this->getOptions()->getMinNumber();
        $minSpecialCharacter = $this->getOptions()->getMinSpecialCharacter();
        // $isMixedCase = $this->getOptions()->isMixedCase();
        // $maxSequentialLetter = $this->getOptions()->getMaxSequentialLetter();
        // $maxConsecutiveLetter = $this->getOptions()->getMaxConsecutiveLetter();
        // $maxSequentialNumber = $this->getOptions()->getMaxSequentialNumber();
        // $maxConsecutiveNumber = $this->getOptions()->getMaxConsecutiveNumber();

        $lowerCase = str_split('abcdefghijklmnopqrstuvwxyz');
        $upperCase = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $numbers = str_split('0123456789');
        $symbols = str_split('!@#$%^&*()');

        $lowerCaseChars = '';
        $upperCaseChars = '';
        $numbersChars = '';
        $symbolsChars = '';

        if (($minLetter + $minNumber + $minSpecialCharacter) >= $minLength) {
            $rndLetter = $minLetter;
            $rndSymbols = $minSpecialCharacter;
            $rndNumbers = $minNumber;
        } else {
            $symbolCount = ceil($minLength / 3);
            $rndLetter = $minLetter > $symbolCount ? $minLetter : $symbolCount;
            $rndNumbers = $minNumber > $symbolCount ? $minNumber : $symbolCount;
            $rndSymbols = $minSpecialCharacter > $symbolCount ? $minSpecialCharacter : $minLength - ($rndLetter + $rndNumbers);
        }

        if ($rndLetter) {
            $rndLowerCaseLetter = $rndLetter < 2 ? $rndLetter : ceil($rndLetter / 2);
            $rndUpperCaseLetter = $rndLetter - $rndLowerCaseLetter;
            $lowerCaseChars = $this->generatePasswordPart($lowerCase, $rndLowerCaseLetter);
            $upperCaseChars = $this->generatePasswordPart($upperCase, $rndUpperCaseLetter);
        }

        if ($rndNumbers) {
            $numbersChars = $this->generatePasswordPart($numbers, $rndNumbers);
        }

        if ($rndSymbols) {
            $symbolsChars = $this->generatePasswordPart($symbols, $rndSymbols);
        }

        $password = $passwordPart = $lowerCaseChars . $upperCaseChars . $numbersChars . $symbolsChars;

        if ($minLength) {
            while (strlen($password) < $minLength) {
                $password .= $passwordPart;
            }
        }

        $password = str_shuffle($password);
        while (!$this->getValidator()->validate($password)) {
            $password = str_shuffle($password);
        }

        return $password;
    }

    /**
     * @return string
     */
    public function generateEncrypted(): string
    {
        return Password\HashHelper::new()->normalizeAndEncrypt($this->generate());
    }

    /**
     * Generate password parts
     * @param array $chars
     * @param int $count
     * @return string
     */
    public function generatePasswordPart(array $chars, int $count): string
    {
        $passPart = '';
        for ($i = 0; $i < $count; $i++) {
            $passPart .= $chars[random_int(0, count($chars) - 1)];
        }
        return $passPart;
    }

    /**
     * @return Strength\Validator
     */
    public function getValidator(): Strength\Validator
    {
        if ($this->validator === null) {
            $this->validator = Password\Strength\Validator::new()
                ->setOptions($this->getOptions());
        }
        return $this->validator;
    }

    /**
     * @param Strength\Validator $validator
     * @return static
     */
    public function setValidator(Strength\Validator $validator): static
    {
        $this->validator = $validator;
        return $this;
    }
}
