<?php

namespace ToneflixCode\CuttlyPhp\Builders;

use stdClass;

final class ShortenResponse extends BaseResponse
{
    /**
     * Date of shortening the link
     *
     * @var string
     */
    public string $date;
    /**
     * Website title
     *
     * @var string
     */
    public string $title;
    /**
     * Original link
     *
     * @var string
     */
    public string $fullLink;
    /**
     * Shortened link
     *
     * @var string
     */
    public string $shortLink;

    public function __construct(protected stdClass $data)
    {
        if (isset($data->date)) {
            $this->date = $data->date;
        }

        if (isset($data->title)) {
            $this->title = $data->title;
        }

        if (isset($data->status)) {
            $this->status = $data->status;
        }

        if (isset($data->fullLink)) {
            $this->fullLink = $data->fullLink;
        }

        if (isset($data->shortLink)) {
            $this->shortLink = $data->shortLink;
        }
    }
}
