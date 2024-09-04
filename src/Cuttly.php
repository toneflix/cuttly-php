<?php

namespace ToneflixCode\CuttlyPhp;

use Dotenv\Dotenv;
use Dotenv\Repository\RepositoryBuilder;
use ToneflixCode\CuttlyPhp\Apis\CuttlyRegular;
use ToneflixCode\CuttlyPhp\Apis\CuttlyTeam;
use ToneflixCode\CuttlyPhp\Exceptions\InvalidApiKeyException;

class Cuttly
{
    protected string $baseUrl;

    public function __construct(
        public ?string $apiKey = null,
        public ?string $teamApiKey = null
    ) {}

    public function init(): self
    {
        $this->apiKey ??= static::env('CUTTLY_API_KEY');
        $this->teamApiKey ??= static::env('CUTTLY_TEAM_API_KEY');

        return $this;
    }

    private static function env($key): ?string
    {
        if (function_exists('env')) {
            return env($key);
        }

        $repository = RepositoryBuilder::createWithDefaultAdapters()->immutable()->make();

        Dotenv::create($repository, static::getEnvPath())->safeLoad();

        return $_SERVER[$key] ?? null;
    }

    /**
     * Resolve the .env file
     *
     * @return string
     */
    private static function getEnvPath(): string
    {
        $reflector = new \ReflectionClass(static::class);

        $envPath = explode('vendor/toneflix-code/cuttly-php/src/Cuttly', $reflector->getFileName());

        if (!file_exists(realpath($envPath[0])) || !is_dir(realpath($envPath[0]))) {
            $envPath = realpath(__DIR__ . '/../');
        }

        return $envPath;
    }

    /**
     * Call the Regular API endpoint available only to all users 
     * no matter the subscription plan
     *
     * @return CuttlyRegular
     * @throws InvalidApiKeyException
     */
    public function regular(): CuttlyRegular
    {
        $apiKey = $this->init()->apiKey;

        if (!$this->apiKey) {
            throw new InvalidApiKeyException();
        }

        return new CuttlyRegular($apiKey);
    }

    /**
     * Call the Team API endpoint available only to registered 
     * users with minimum Team subscription plan
     *
     * @return CuttlyTeam
     * @throws InvalidApiKeyException
     */
    public function team(): CuttlyTeam
    {
        $apiKey = $this->init()->teamApiKey;

        if (!$apiKey) {
            throw new InvalidApiKeyException();
        }

        return new CuttlyTeam($apiKey);
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