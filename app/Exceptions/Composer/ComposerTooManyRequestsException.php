<?php

namespace App\Exceptions\Composer;

use Illuminate\Http\Client\Response;
use RuntimeException;
use Throwable;

class ComposerTooManyRequestsException extends RuntimeException
{
    /**
     * Create a new HTTP response exception instance.
     */
    public function __construct(public Response $response, ?Throwable $previous = null)
    {
        parent::__construct($previous?->getMessage() ?? '', $previous?->getCode() ?? 0, $previous);
    }
}
