<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\ResultStorage\Session;

use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Storage\PhpSessionStorageCreateTrait;

/**
 * @package Sam\Billing\Gate\ResultStorage\Session
 */
class PaymentResultStorage extends CustomizableClass
{
    use PhpSessionStorageCreateTrait;

    private const AUTH_CAPTURE_RESULT_PAYLOAD = 'AuthCaptureResultPayload';
    private const PAID_AMOUNT = 'paid_amount';
    private const PAYMENT_FAILED = 'payment_failed';
    private const PAYMENT_SUCCESS = 'payment_success';
    private const TRACK_CODE = 'TRACK_CODE';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

    public function hasPaymentSuccess(): bool
    {
        return $this->has(self::PAYMENT_SUCCESS);
    }

    public function getPaymentSuccess(): string
    {
        return $this->get(self::PAYMENT_SUCCESS);
    }

    public function setPaymentSuccess(string $message): void
    {
        $this->set(self::PAYMENT_SUCCESS, $message);
    }

    public function deletePaymentSuccess(): void
    {
        $this->delete(self::PAYMENT_SUCCESS);
    }

    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

    public function hasPaymentFailed(): bool
    {
        return $this->has(self::PAYMENT_FAILED);
    }

    public function getPaymentFailed(): string
    {
        return $this->get(self::PAYMENT_FAILED);
    }

    public function setPaymentFailed(string $message): void
    {
        $this->set(self::PAYMENT_FAILED, $message);
    }

    public function deletePaymentFailed(): void
    {
        $this->delete(self::PAYMENT_FAILED);
    }

    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

    public function hasPaidAmount(): bool
    {
        return $this->has(self::PAID_AMOUNT);
    }

    public function getPaidAmount(): float
    {
        return (float)$this->get(self::PAID_AMOUNT);
    }

    public function setPaidAmount(?float $amount): void
    {
        $this->set(self::PAID_AMOUNT, $amount);
    }

    public function deletePaidAmount(): void
    {
        $this->delete(self::PAID_AMOUNT);
    }

    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

    public function hasTrackCode(): bool
    {
        return $this->has(self::TRACK_CODE);
    }

    public function getTrackCode(): string
    {
        return $this->get(self::TRACK_CODE);
    }

    public function setTrackCode(string $trackCode): void
    {
        $this->set(self::TRACK_CODE, $trackCode);
    }

    public function deleteTrackCode(): void
    {
        $this->delete(self::TRACK_CODE);
    }

    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

    public function hasAuthCaptureResultPayload(): bool
    {
        return $this->has(self::AUTH_CAPTURE_RESULT_PAYLOAD);
    }

    public function getAuthCaptureResultPayload(): array
    {
        return (array)$this->get(self::AUTH_CAPTURE_RESULT_PAYLOAD);
    }

    public function setAuthCaptureResultPayload(array $authSuccess): void
    {
        $this->set(self::AUTH_CAPTURE_RESULT_PAYLOAD, $authSuccess);
    }

    public function deleteAuthCaptureResultPayload(): void
    {
        $this->delete(self::AUTH_CAPTURE_RESULT_PAYLOAD);
    }

    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

    protected function has(string $key): bool
    {
        return $this->createPhpSessionStorage()->has($key);
    }

    protected function get(string $key): mixed
    {
        return $this->createPhpSessionStorage()->get($key);
    }

    protected function set(string $key, mixed $value): void
    {
        $this->createPhpSessionStorage()->set($key, $value);
    }

    protected function delete(string $key): void
    {
        $this->createPhpSessionStorage()->delete($key);
    }
}
