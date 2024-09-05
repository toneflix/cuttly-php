<?php

namespace ToneflixCode\CuttlyPhp\Apis;

use ToneflixCode\CuttlyPhp\Builders\BaseResponse;
use ToneflixCode\CuttlyPhp\Builders\ShortenResponse;
use ToneflixCode\CuttlyPhp\Builders\StatsResponse;
use ToneflixCode\CuttlyPhp\Cuttly;
use ToneflixCode\CuttlyPhp\Exceptions\Thrower;

class CuttlyRegular
{
    /**
     * The base url for Cuttly regular
     *
     * @var string
     */
    public string $baseUrl = 'https://cutt.ly/api/api.php';

    /**
     * The query build for the reqeuest
     *
     * @var array{
     *  short:string,
     *  edit:string,
     *  tag:string,
     *  source:string,
     *  unique:int|string,
     *  name:string,
     *  userDomain:int,
     *  noTitle:int,
     *  public:int,
     *  delete:int
     * }
     */
    public array $query = [];

    /**
     * Initialize the Regular constructor with the API key
     *
     * @param string|null $apiKey
     */
    public function __construct(private ?string $apiKey = null) {}

    /**
     * The TAG you want to add for shortened link
     *
     * @param string $tag
     * @return CuttlyRegular
     */
    public function tag(string $tag): self
    {
        $this->query['tag'] = $tag;
        return $this;
    }

    /**
     * It will change the source url of shortened link.
     *
     * @param string $url
     * @return self
     */
    public function source(string $url): self
    {
        $this->query['source'] = $url;
        return $this;
    }

    /**
     * Sets a unique stat count for a short link.
     *
     * @param string $url
     * @return self
     */
    public function unique(int|string $unique): self
    {
        $this->query['unique'] = $unique;
        return $this;
    }

    /**
     * Your desired short link - alias - if not already taken
     *
     * @param string $name
     * @return CuttlyRegular
     */
    public function name(string $name): self
    {
        $this->query['name'] = $name;
        return $this;
    }

    /**
     * Sets userDomain to 1, it will use the domain from the user account that is approved and has the status: active. 
     * Check subscription plans for more details - available from Single plan
     *
     * @return CuttlyRegular
     */
    public function userDomain(): self
    {
        $this->query['userDomain'] = 1;
        return $this;
    }

    /**
     * Faster API response time
     * This parameter disables getting the page title from the source page meta tag which results in faster API response time
     * Available for Team Enterprise plan
     *
     * @return CuttlyRegular
     */
    public function noTitle(): self
    {
        $this->query['noTitle'] = 1;
        return $this;
    }

    /**
     * Settings public click stats for shortened link via API Available from Single plan
     *
     * @return CuttlyRegular
     */
    public function public(): self
    {
        $this->query['public'] = 1;
        return $this;
    }

    /**
     * Shorten the URL
     *
     * @param string $url  URL you want to shorten. [Required]
     * @return ShortenResponse
     */
    public function shorten(string $url): ShortenResponse
    {
        $this->query['short'] = $url;
        return $this->send();
    }

    /**
     * Edit the short link
     *
     * @param string $url   Your shortened link to be edited. [Required]
     * @return BaseResponse
     */
    public function edit(string $url): BaseResponse
    {
        $this->query['edit'] = $url;

        return $this->send();
    }

    /**
     * It will delete your shortened link.
     *
     * @param string $url         The shortened URL you want to check statistics of. [Required]
     * @param string|null $from   Limit results to the number of clicks from a given period (YYYY-MM-DD).
     * @param string|null $to     Limit results to the number of clicks to a given period (YYYY-MM-DD).
     * @return StatsResponse
     */
    public function stats(string $url, ?string $from = null, ?string $to = null): StatsResponse
    {
        $this->query['stats'] = $url;

        if (isset($from)) {
            $this->query['date_from'] = $from;
        }

        if (isset($to)) {
            $this->query['date_to'] = $to;
        }

        return $this->send();
    }

    /**
     * It will delete your shortened link.
     * 
     * @param string $url   Your shortened link to be deleted. [Required]
     *
     * @return BaseResponse
     */
    public function delete(string $url): BaseResponse
    {
        $this->query['delete'] = 1;
        return $this->edit($url);
    }

    /**
     * Finally make the request to cuttly
     *
     * @return ShortenResponse|BaseResponse|StatsResponse
     * @throws \ToneflixCode\CuttlyPhp\Exceptions\FailedRequestException
     */
    public function send(): ShortenResponse|BaseResponse|StatsResponse
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->baseUrl,
        ]);

        try {
            $response = $client->get('', [
                'query' => array_merge(["key" => $this->apiKey], $this->query)
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $body = (string)$e->getResponse()->getBody();

            return $this->buildError($body);
        }

        $body = (string)$response->getBody();

        if (Cuttly::jsonValidate($body)) {
            $data = json_decode($body);

            return match (true) {
                is_string($data) => $this->buildError($body),
                isset($this->query['edit']) => new BaseResponse($data),
                isset($this->query['stats']) => new StatsResponse($data->stats),
                default => new ShortenResponse($data->url),
            };
        }

        $blank = (object)["error" => "No Data Available"];
        return new BaseResponse($blank);
    }

    private function buildError(string $body)
    {
        return match (true) {
            isset($this->query['edit']) => Thrower::editing($body),
            isset($this->query['stats']) => Thrower::stats($body),
            default => Thrower::shortener($body),
        };
    }
}