<?php

namespace ToneflixCode\CuttlyPhp\Builders;

use stdClass;

final class StatsResponse
{
    /**
     * Other clicks
     *
     * @var integer
     */
    public int $rest;

    /**
     * Clicks from bots
     *
     * @var object{
     *  bots:array<int,object{name:string,clicks:int}>,
     * }
     */
    public object $bots;

    /**
     * Total number of clicks
     *
     * @var integer
     */
    public int $clicks;

    /**
     * The number of clicks from twitter
     *
     * @var integer
     */
    public int $twitter;

    /**
     * The number of clicks from Facebook
     *
     * @var integer
     */
    public int $facebook;

    /**
     * The number of clicks from linkedin
     *
     * @var integer
     */
    public int $linkedin;

    /**
     * Referrers
     *
     * @var object{
     *  ref:array<int,object{link:string,clicks:int}>,
     * }
     */
    public object $refs;

    /**
     * Date of shortening the link
     *
     * @var string
     */
    public string $date;

    /**
     * Title of the shortened link
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

    /**
     * Clicks by devices
     *
     * @var object{
     *  dev:array<int,string>,
     *  geo:array<int,object{tag:string,clicks:int}>,
     *  sys:array<int,object{tag:string,clicks:int}>,
     *  bro:array<int,object{tag:string,clicks:int}>,
     *  lang:array<int,object{tag:string,clicks:int}>,
     *  brand:array<int,object{tag:string,clicks:int}>,
     * }
     */
    public object $devices;

    public function __construct(protected stdClass $data)
    {
        if (isset($data->clicks)) {
            $this->clicks = $data->clicks;
        }

        if (isset($data->date)) {
            $this->date = $data->date;
        }

        if (isset($data->title)) {
            $this->title = $data->title;
        }

        if (isset($data->fullLink)) {
            $this->fullLink = $data->fullLink;
        }

        if (isset($data->shortLink)) {
            $this->shortLink = $data->shortLink;
        }

        if (isset($data->facebook)) {
            $this->facebook = $data->facebook;
        }

        if (isset($data->twitter)) {
            $this->twitter = $data->twitter;
        }

        if (isset($data->linkedin)) {
            $this->linkedin = $data->linkedin;
        }

        if (isset($data->rest)) {
            $this->rest = $data->rest;
        }

        if (isset($data->bots)) {
            $this->bots = $data->bots;
        }

        if (isset($data->devices)) {
            $this->devices = $data->devices;
        }
    }
}
