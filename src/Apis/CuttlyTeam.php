<?php

namespace ToneflixCode\Cuttly\Apis;

use ToneflixCode\Cuttly\Builders\BaseResponse;
use ToneflixCode\Cuttly\Builders\ShortenResponse;
use ToneflixCode\Cuttly\Builders\StatsResponse;
use ToneflixCode\Cuttly\Cuttly;
use ToneflixCode\Cuttly\Exceptions\Thrower;

class CuttlyTeam
{
    /**
     * The base url for Cuttly regular
     *
     * @var string
     */
    public string $baseUrl = 'https://cutt.ly/team/API/index.php';

    /**
     * The query build for the reqeuest
     *
     * @var array{
     *  action:'shorten'|'stats'|'edit',
     *  tag: string,
     *  name: string,
     *  title: string,
     *  source: string,
     *  unique: int|string,
     *  domain: string,
     *  public: string,
     *  noTitle: string
     * }
     */
    public array $query = ['action' => 'shorten'];

    /**
     * Initialize the Team constructor with the API key
     *
     * @param string|null $apiKey
     */
    public function __construct(private ?string $apiKey = null) {}

    /**
     * The TAG you want to add for shortened link
     *
     * @param string $tag
     * @return CuttlyTeam
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
     * It will change the title of url of shortened link.
     *
     * @param string $title
     * @return self
     */
    public function title(string $title): self
    {
        $this->query['title'] = $title;
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
     * Your desired domain to be used (only with action shorten)
     *
     * @param string $domain
     * @return CuttlyTeam
     */
    public function domain(string $domain): self
    {
        $this->query['domain'] = $domain;
        return $this;
    }

    /**
     * Faster API response time
     * This parameter disables getting the page title from the source page meta tag which results in faster API response time
     * Available for Team Enterprise plan
     *
     * @return CuttlyTeam
     */
    public function noTitle(): self
    {
        $this->query['noTitle'] = 1;
        return $this;
    }

    /**
     * Settings public click stats for shortened link via API Available from Single plan
     *
     * @return CuttlyTeam
     */
    public function public(): self
    {
        $this->query['public'] = 1;
        return $this;
    }

    /**
     * Your desired short link - alias - if not already taken
     *
     * @param string $name
     * @return CuttlyTeam
     */
    public function name(string $name): self
    {
        $this->query['name'] = $name;
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
        $this->query['action'] = 'shorten';
        $this->query['url'] = $url;
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
        $this->query['action'] = 'edit';
        $this->query['url'] = $url;

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
        $this->query['url'] = $url;
        $this->query['action'] = 'stats';

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

            return match (true) {
                $this->query['action'] === 'edit' => Thrower::editing($body),
                $this->query['action'] === 'stats' => Thrower::stats($body),
                default => Thrower::shortener($body),
            };
        }

        $body = (string)$response->getBody();

        if (Cuttly::jsonValidate($body)) {
            $data = json_decode($body);

            return match (true) {
                $this->query['action'] === 'edit' => new BaseResponse($data),
                $this->query['action'] === 'stats' => new StatsResponse($data->stats),
                default => new ShortenResponse($data),
            };
        }

        $blank = (object)["error" => "No Data Available"];
        return new BaseResponse($blank);
    }
}