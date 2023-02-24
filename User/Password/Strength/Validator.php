<?php
/**
 * Password strength checker
 *
 * SAM-1238: Increased password security
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan, Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           4 May, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Password\Strength;

use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;
use Sam\User\Password\History;
use UserAuthentication;

/**
 * Class Validator
 */
class Validator extends CustomizableClass
{
    use OptionsAwareTrait;
    use TranslatorAwareTrait;

    // Error codes
    public const ERR_MIN_LENGTH = 1;
    public const ERR_MIN_LETTER = 2;
    public const ERR_MIN_NUM = 3;
    public const ERR_MIN_SPECIAL = 4;
    public const ERR_REQ_MIXED_CASE = 5;
    public const ERR_MAX_SEQ_LETTER = 6;
    public const ERR_MAX_CONSEQ_LETTER = 7;
    public const ERR_MAX_SEQ_NUM = 8;
    public const ERR_MAX_CONSEQ_NUM = 9;
    public const ERR_SPACE_EXIST = 10;

    protected array $errorMessages = [
        self::ERR_MIN_LENGTH => ['min length %d characters', 'USER_PASSWORD_ERR_MIN_LENGTH'],
        self::ERR_MIN_LETTER => ['%d letter(s)', 'USER_PASSWORD_ERR_MIN_LETTER'],
        self::ERR_MIN_NUM => ['%d number(s)', 'USER_PASSWORD_ERR_MIN_NUMBER'],
        self::ERR_MIN_SPECIAL => ['%d non-alphanumeric character(s) like +-!#@$%% etc', 'USER_PASSWORD_ERR_MIN_SPECIAL'],
        self::ERR_REQ_MIXED_CASE => ['mixed cases', 'USER_PASSWORD_ERR_REQ_MIXED_CASE'],
        self::ERR_MAX_SEQ_LETTER => ['not more than %d sequential letters', 'USER_PASSWORD_ERR_MAX_SEQ_LETTER'],
        self::ERR_MAX_CONSEQ_LETTER => ['not more than %d consecutive letters without a number or symbol', 'USER_PASSWORD_ERR_MAX_CONSEQ_LETTER'],
        self::ERR_MAX_SEQ_NUM => ['not more than %d sequential numbers', 'USER_PASSWORD_ERR_MAX_SEQ_NUM'],
        self::ERR_MAX_CONSEQ_NUM => ['not more than %d consecutive numbers without a letter or symbol', 'USER_PASSWORD_ERR_MAX_CONSEQ_NUM'],
        self::ERR_SPACE_EXIST => ['no spaces allowed at starting or trailing position', 'USER_PASSWORD_ERR_SPACE_EXISTS'],
    ];

    /**
     * @var int[]
     */
    protected array $errors = [];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculate score for password
     *
     * @param string $password
     * @return float
     */
    public function calcScoreForPassword(string $password): float
    {
        $reqLength = 0;
        $reqLetter = 0;
        $reqNumber = 0;
        $reqSpecial = 0;
        $reqMixedCase = 0;
        $reqNoSequentialLetter = 0;
        $reqNoConsecutiveLetter = 0;
        $reqNoSequentialNumber = 0;
        $reqNoConsecutiveNumber = 0;

        [
            $minLength,
            $minLetter,
            $minNumber,
            $minSpecialCharacter,
            $isMixedCase,
            $maxSequentialLetter,
            $maxConsecutiveLetter,
            $maxSequentialNumber,
            $maxConsecutiveNumber
        ] = $this->getOptions()->getAll();

        if ($minLength) {
            $passwordLength = strlen($password);
            $reqLength = ($passwordLength > $minLength ? 10
                : ($passwordLength * $passwordLength) / 10);
        }
        if ($minLetter) {
            preg_match_all('/[a-z]/i', $password, $matches);
            if (count($matches[0]) > $minLetter) {
                $reqLetter = 1;
            }
        }
        if ($minNumber) {
            preg_match_all('/\d/', $password, $matches);
            if (count($matches[0]) > $minNumber) {
                $reqNumber = 1;
            }
        }
        if ($minSpecialCharacter) {
            preg_match_all('/\W/', $password, $matches);
            if (count($matches[0]) > $minSpecialCharacter) {
                $reqSpecial = 1;
            }
        }
        if ($isMixedCase) {
            if (
                preg_match('/[a-z]/', $password)
                && preg_match('/[A-Z]/', $password)
            ) {
                $reqMixedCase = 1;
            }
        }

        if ($maxSequentialLetter) {
            $passwordArray = str_split($password);
            $sequentialLetterArray[] = $passwordArray[0];
            for ($i = 1, $iMax = count($passwordArray); $i < $iMax; $i++) {
                $lastChar = end($sequentialLetterArray);
                $lastChar++;
                if ($password[$i] === $lastChar) {
                    $sequentialLetterArray[] = $password[$i];
                    if (count($sequentialLetterArray) > $maxSequentialLetter) {
                        $reqNoSequentialLetter = 1;
                        break;
                    }
                } else {
                    $sequentialLetterArray = [];
                    $sequentialLetterArray[] = $password[$i];
                }
            }
        }

        if ($maxConsecutiveLetter) {
            preg_match_all('/[a-zA-Z]+/', $password, $matches);
            foreach ($matches[0] as $match) {
                if (strlen($match) > $maxConsecutiveLetter) {
                    $reqNoConsecutiveLetter = 1;
                    break;
                }
            }
        }

        if ($maxSequentialNumber) {
            $passwordArray = str_split($password);
            $sequentialNumberArray[] = $passwordArray[0];
            for ($i = 1, $iMax = count($passwordArray); $i < $iMax; $i++) {
                $lastChar = end($sequentialNumberArray);
                $lastChar++;
                if ($password[$i] === (string)$lastChar) {
                    $sequentialNumberArray[] = $password[$i];
                    if (count($sequentialNumberArray) > $maxSequentialNumber) {
                        $reqNoSequentialNumber = 1;
                        break;
                    }
                } else {
                    $sequentialNumberArray = [];
                    $sequentialNumberArray[] = $password[$i];
                }
            }
        }

        if ($maxConsecutiveNumber) {
            preg_match_all('!\d+!', $password, $matches);
            foreach ($matches[0] as $match) {
                if (strlen($match) > $maxConsecutiveNumber) {
                    $reqNoConsecutiveNumber = 1;
                    break;
                }
            }
        }

        $lenScore = $reqLength * 4;
        $reqLetterScore = $reqLetter * ($reqNumber + $reqSpecial) * ($lenScore / 2);
        $reqMixedScore = $reqLetter * $reqMixedCase * ($lenScore / 2);
        $noSeqLetterScore = $reqNoSequentialLetter * ($lenScore / 4);
        $noConsLetterScore = $reqNoConsecutiveLetter * ($lenScore / 4);
        $reqNumScore = $reqNumber * ($reqLetter + $reqSpecial) * ($lenScore / 2);
        $noSeqNumScore = $reqNoSequentialNumber * ($lenScore / 4);
        $noConsNumScore = $reqNoConsecutiveNumber * ($lenScore / 4);
        $reqSymScore = $reqSpecial * (1 + $reqLetter + $reqNumber) * ($lenScore / 2);

        $score = ($lenScore + $reqLetterScore + $reqMixedScore + $noSeqLetterScore
                + $noConsLetterScore + $reqNumScore + $noSeqNumScore + $noConsNumScore
                + $reqSymScore) / 240;

        return $score;
    }

    /**
     * Calculate score for settings
     *
     * @return float
     */
    public function calcScoreForSettings(): float
    {
        [
            $minLength,
            $minLetter,
            $minNumber,
            $minSpecialCharacter,
            $isMixedCase,
            $maxSequentialLetter,
            $maxConsecutiveLetter,
            $maxSequentialNumber,
            $maxConsecutiveNumber,
            $renewPassword,
            $historyRepeat
        ] = $this->getOptions()->getAll();

        if ($minLength) {
            $lenScore = ($minLength > 10 ? 10 : ($minLength * $minLength) / 10) * 4;
        } else {
            $lenScore = 0;
        }

        if ($minLetter) {
            $reqLetter = $minLetter > 4 ? 4 : $minLetter;
        } else {
            $reqLetter = 0;
        }

        if ($minNumber) {
            $reqNumber = $minNumber > 2 ? 2 : $minNumber;
        } else {
            $reqNumber = 0;
        }

        if ($minSpecialCharacter) {
            $reqSpecial = $minSpecialCharacter > 2 ? 2 : $minSpecialCharacter;
        } else {
            $reqSpecial = 0;
        }

        if ($maxSequentialLetter) {
            $reqNoSequentialLetter = $maxSequentialLetter > 2 ? 2 : $maxSequentialLetter;
        } else {
            $reqNoSequentialLetter = 0;
        }

        if ($maxConsecutiveLetter) {
            $reqNoConsecutiveLetter = $maxConsecutiveLetter > 2 ? 2 : $maxConsecutiveLetter;
        } else {
            $reqNoConsecutiveLetter = 0;
        }

        if ($maxSequentialNumber) {
            $reqNoSequentialNumber = $maxSequentialNumber > 2 ? 2 : $maxSequentialNumber;
        } else {
            $reqNoSequentialNumber = 0;
        }

        if ($maxConsecutiveNumber) {
            $reqNoConsecutiveNumber = $maxConsecutiveNumber > 2 ? 2 : $maxConsecutiveNumber;
        } else {
            $reqNoConsecutiveNumber = 0;
        }

        if ($renewPassword) {
            $renewPasswordScore = round((365 - $renewPassword) / 90) + 1;
        } else {
            $renewPasswordScore = 0;
        }

        $historyRepeatScore = $historyRepeat ?: 0;

        $reqLetterScore = $reqLetter * ($reqNumber + $reqSpecial) * ($lenScore / 2);
        $reqMixedScore = $reqLetter * $isMixedCase * ($lenScore / 4);
        $noSeqLetterScore = $reqNoSequentialLetter * ($lenScore / 4);
        $noConsLetterScore = $reqNoConsecutiveLetter * ($lenScore / 4);
        $reqNumScore = $reqNumber * ($reqLetter + $reqSpecial) * ($lenScore / 2);
        $noSeqNumScore = $reqNoSequentialNumber * ($lenScore / 4);
        $noConsNumScore = $reqNoConsecutiveNumber * ($lenScore / 4);
        $reqSymScore = $reqSpecial * (1 + $reqLetter + $reqNumber) * ($lenScore / 2);
        $pwExpScore = $renewPasswordScore * ($lenScore / 8);
        $pwRepScore = $historyRepeatScore * ($lenScore / 8);

        $score = ($lenScore + $reqLetterScore + $reqMixedScore + $noSeqLetterScore
                + $noConsLetterScore + $reqNumScore + $noSeqNumScore + $noConsNumScore
                + $reqSymScore + $pwExpScore + $pwRepScore) / 253;

        return $score;
    }

    /**
     * Validate password
     *
     * @param string $password
     * @return bool
     */
    public function validate(string $password): bool
    {
        [
            $minLength,
            $minLetter,
            $minNumber,
            $minSpecialCharacter,
            $isMixedCase,
            $maxSequentialLetter,
            $maxConsecutiveLetter,
            $maxSequentialNumber,
            $maxConsecutiveNumber
        ] = $this->getOptions()->getAll();

        $this->errors = [];
        if (trim($password) !== $password) {
            $this->errors[] = self::ERR_SPACE_EXIST;
        }
        if ($minLength) {
            if (strlen($password) < $minLength) {
                $this->errors[] = self::ERR_MIN_LENGTH;
            }
        }
        if ($minLetter) {
            preg_match_all('/[a-z]/i', $password, $matches);
            if (count($matches[0]) < $minLetter) {
                $this->errors[] = self::ERR_MIN_LETTER;
            }
        }
        if ($minNumber) {
            preg_match_all('/\d/', $password, $matches);
            if (count($matches[0]) < $minNumber) {
                $this->errors[] = self::ERR_MIN_NUM;
            }
        }
        if ($minSpecialCharacter) {
            preg_match_all('/\W/', $password, $matches);
            if (count($matches[0]) < $minSpecialCharacter) {
                $this->errors[] = self::ERR_MIN_SPECIAL;
            }
        }
        if ($isMixedCase) {
            if (
                !preg_match('/[a-z]/', $password)
                || !preg_match('/[A-Z]/', $password)
            ) {
                $this->errors[] = self::ERR_REQ_MIXED_CASE;
            }
        }

        if ($maxSequentialLetter) {
            $maxSeqPass = strtolower($password);
            $passwordArray = str_split($maxSeqPass);
            $sequentialLetterArray[] = $passwordArray[0];
            for ($i = 1, $iMax = count($passwordArray); $i < $iMax; $i++) {
                $lastChar = strtolower(end($sequentialLetterArray));
                $lastChar++;
                if (
                    $maxSeqPass[$i] === (string)$lastChar
                    && !is_numeric($lastChar)
                ) {
                    $sequentialLetterArray[] = $maxSeqPass[$i];
                    if (count($sequentialLetterArray) > $maxSequentialLetter) {
                        $this->errors[] = self::ERR_MAX_SEQ_LETTER;
                        break;
                    }
                } else {
                    $sequentialLetterArray = [];
                    $sequentialLetterArray[] = $maxSeqPass[$i];
                }
            }
        }

        if ($maxConsecutiveLetter) {
            preg_match_all('/[a-zA-Z]+/', $password, $matches);
            foreach ($matches[0] as $match) {
                if (strlen($match) > $maxConsecutiveLetter) {
                    $this->errors[] = self::ERR_MAX_CONSEQ_LETTER;
                    break;
                }
            }
        }

        if ($maxSequentialNumber) {
            $passwordArray = str_split($password);
            $sequentialNumberArray[] = $passwordArray[0];
            for ($i = 1, $iMax = count($passwordArray); $i < $iMax; $i++) {
                $lastChar = end($sequentialNumberArray);
                $lastChar++;
                if (
                    $password[$i] === (string)$lastChar
                    && is_numeric($lastChar)
                ) {
                    $sequentialNumberArray[] = $password[$i];
                    if (count($sequentialNumberArray) > $maxSequentialNumber) {
                        $this->errors[] = self::ERR_MAX_SEQ_NUM;
                        break;
                    }
                } else {
                    $sequentialNumberArray = [];
                    $sequentialNumberArray[] = $password[$i];
                }
            }
        }

        if ($maxConsecutiveNumber) {
            preg_match_all('!\d+!', $password, $matches);
            foreach ($matches[0] as $match) {
                if (strlen($match) > $maxConsecutiveNumber) {
                    $this->errors[] = self::ERR_MAX_CONSEQ_NUM;
                    break;
                }
            }
        }
        return count($this->errors) === 0;
    }

    /**
     * Get password validation errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Return not translated full error message, that can be used at admin side.
     * E.g. "Password requires min length 6 characters, mixed cases, 2 number(s)"
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->buildErrorMessage();
    }

    /**
     * Return translated full error message, that should be used at public side.
     * E.g. "Password requires min length 6 characters, mixed cases, 2 number(s)"
     * @param int $systemAccountId
     * @return string
     */
    public function getTranslatedErrorMessage(int $systemAccountId): string
    {
        return $this->buildErrorMessage(true, $systemAccountId);
    }

    /**
     * @param bool $isTranslated
     * @param int|null $systemAccountId necessary only in case of $isTranslated = true
     * @return string
     */
    protected function buildErrorMessage(bool $isTranslated = false, ?int $systemAccountId = null): string
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        [
            $minLength,
            $minLetter,
            $minNumber,
            $minSpecialCharacter,
            $isMixedCase,
            $maxSequentialLetter,
            $maxConsecutiveLetter,
            $maxSequentialNumber,
            $maxConsecutiveNumber
        ] = $this->getOptions()->getAll();

        $allMessages = $this->getAllErrorMessages($isTranslated, $systemAccountId);
        $message = $isTranslated ?
            $this->getTranslator()->translate('USER_PASSWORD_ERR_GENERAL', 'user', $systemAccountId)
            : 'Password requires %s';
        $errors = [];
        if (in_array(self::ERR_SPACE_EXIST, $this->errors)) {
            $errors[] = $allMessages[self::ERR_SPACE_EXIST];
        }
        if (in_array(self::ERR_MIN_LENGTH, $this->errors)) {
            $errors[] = sprintf($allMessages[self::ERR_MIN_LENGTH], $minLength);
        }
        if (in_array(self::ERR_MIN_LETTER, $this->errors)) {
            $errors[] = sprintf($allMessages[self::ERR_MIN_LETTER], $minLetter);
        }
        if (in_array(self::ERR_REQ_MIXED_CASE, $this->errors)) {
            $errors[] = $allMessages[self::ERR_REQ_MIXED_CASE];
        }
        if (in_array(self::ERR_MIN_NUM, $this->errors)) {
            $errors[] = sprintf($allMessages[self::ERR_MIN_NUM], $minNumber);
        }
        if (in_array(self::ERR_MIN_SPECIAL, $this->errors)) {
            $errors[] = sprintf($allMessages[self::ERR_MIN_SPECIAL], $minSpecialCharacter);
        }
        if (in_array(self::ERR_MAX_SEQ_LETTER, $this->errors)) {
            $errors[] = sprintf($allMessages[self::ERR_MAX_SEQ_LETTER], $maxSequentialLetter);
        }
        if (in_array(self::ERR_MAX_SEQ_NUM, $this->errors)) {
            $errors[] = sprintf($allMessages[self::ERR_MAX_SEQ_NUM], $maxSequentialNumber);
        }
        if (in_array(self::ERR_MAX_CONSEQ_LETTER, $this->errors)) {
            $errors[] = sprintf($allMessages[self::ERR_MAX_CONSEQ_LETTER], $maxConsecutiveLetter);
        }
        if (in_array(self::ERR_MAX_CONSEQ_NUM, $this->errors)) {
            $errors[] = sprintf($allMessages[self::ERR_MAX_CONSEQ_NUM], $maxConsecutiveNumber);
        }
        return sprintf($message, implode(', ', $errors));
    }

    /**
     * @param bool $isTranslated
     * @param int|null $systemAccountId necessary only in case of $isTranslated = true
     * @return array
     */
    protected function getAllErrorMessages(bool $isTranslated = false, ?int $systemAccountId = null): array
    {
        $errorMessages = [];
        foreach ($this->errorMessages as $code => $messages) {
            if ($isTranslated) {
                $errorMessages[$code] = $this->getTranslator()->translate($messages[1], 'user', $systemAccountId);
            } else {
                $errorMessages[$code] = $messages[0];
            }
        }
        return $errorMessages;
    }

    /**
     * Check if user can re-use password
     *
     * @param string $password
     * @param UserAuthentication $userAuthentication
     * @return bool
     */
    public function canReusePassword(string $password, UserAuthentication $userAuthentication): bool
    {
        $canReuse = true;
        $historyRepeat = $this->getOptions()->getHistoryRepeat();
        if ($historyRepeat) {
            $isFound = History::new()->existInHistory($password, $userAuthentication, $historyRepeat);
            $canReuse = !$isFound;
        }
        return $canReuse;
    }
}
