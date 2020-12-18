<?php

declare(strict_types=1);

namespace Samyan;

class CoinbaseException extends \Exception
{
    protected $code;
    protected $message;

    /**
     * Constructor
     *
     * @param string $message
     * @param integer $code
     * @param \Exception $previous
     */
    public function __construct(string $message, int $code = 0, \Exception $previous = null)
    {
        $this->decodeException($message, $code);

        parent::__construct($this->message, $this->code, $previous);
    }

    /**
     * String representation of the exception
     *
     * @return string
     */
    public function toString(): string
    {
        return __CLASS__ . ': [' . $this->code . ']: ' . $this->message;
    }

    /**
     * Decodes received exception message
     *
     * @param string $message
     * @param integer $code
     * @return void
     */
    private function decodeException(string $message, int $code): void
    {
        $msg = json_decode($message, true);

        $this->code = $code;
        $this->message = isset($msg['message']) ? $msg['message'] : '';
    }
}
