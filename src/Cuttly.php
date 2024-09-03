<?php

namespace ToneflixCode\Cuttly;

use Dotenv\Dotenv;
use Dotenv\Repository\RepositoryBuilder;
use ToneflixCode\Cuttly\Apis\CuttlyRegular;
use ToneflixCode\Cuttly\Apis\CuttlyTeam;
use ToneflixCode\Cuttly\Exceptions\InvalidApiKeyException;

class Cuttly
{
    protected Router $router;
    protected string $baseUrl;

    public function __construct(
        public ?string $apiKey = null,
        public ?string $teamApiKey = null
    ) {}

    public function init(): self
    {
        $this->apiKey ??= static::env('CUTTLY_API_KEY');
        $this->teamApiKey ??= static::env('CUTTLY_TEAM_API_KEY');

        if (!$this->apiKey) {
            throw new InvalidApiKeyException();
        }

        $this->router = new Router();

        return $this;
    }

    private static function env($key): ?string
    {
        $repository = RepositoryBuilder::createWithDefaultAdapters()->make();

        $dotenv = Dotenv::create($repository, __DIR__ . '/../');

        $env = $dotenv->safeLoad();

        return $env[$key] ?? null;
    }

    public function regular(): CuttlyRegular
    {
        $apiKey = $this->init()->apiKey;

        $builder = new CuttlyRegular($apiKey);
        return $builder;
    }

    /**
     * Call the team API endpoint available only to registered users with minimum Team subscription plan
     *
     * @return CuttlyTeam
     */
    public function team(): CuttlyTeam
    {
        $apiKey = $this->init()->teamApiKey;

        $builder = new CuttlyTeam($apiKey);
        return $builder;
    }

    /**
     * Validates a JSON string.
     *
     * @param string $json The JSON string to validate.
     * @param int $depth Maximum depth. Must be greater than zero.
     * @param int $flags Bitmask of JSON decode options.
     * @return bool Returns true if the string is a valid JSON, otherwise false.
     */
    public static function jsonValidate($json, $depth = 512, $flags = 0)
    {
        if (function_exists('json_validate')) {
            return json_validate($json, $depth, $flags);
        }

        if (!is_string($json)) {
            return false;
        }

        try {
            json_decode($json, false, $depth, $flags | JSON_THROW_ON_ERROR);
            return true;
        } catch (\JsonException $e) {
            return false;
        }
    }
}