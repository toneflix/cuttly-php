<?php

namespace ToneflixCode\CuttlyPhp\Builders;

use stdClass;

class BaseResponse
{
    /**
     * The status of the request
     *
     * @var integer
     */
    public int $status = 0;
    /**
     * An error with the request
     *
     * @var string|null
     */
    public ?string $error = null;

    public function __construct(protected stdClass $data)
    {
        if (isset($data->status)) {
            $this->status = $data->status;
        }

        if (isset($data->error)) {
            $this->error = $data->error;
        }
    }
}
