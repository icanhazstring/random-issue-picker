<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\Request;

use Icanhazstring\RandomIssuePicker\Model\SearchRepositoryModel;

class RepositorySearchRequest implements RequestInterface
{
    /** @var int */
    private $page;

    /** @var int */
    private $resultsPerPage;

    /** @var string */
    private $language;

    /** @var string[] */
    private $topics;

    /**
     * @param string[] $topics
     */
    public function __construct(int $page = 1, int $resultsPerPage = 100, string $language = 'php', array $topics = [])
    {
        $this->page = $page;
        $this->resultsPerPage = $resultsPerPage;
        $this->language = $language;
        $this->topics = $topics;
    }

    public function getMethod(): string
    {
        return RequestInterface::METHOD_GET;
    }

    public function getUrl(): string
    {
        return 'https://api.github.com/search/repositories';
    }

    /**
     * @return array<string, array<string, int|string>>
     */
    public function getQueryParameters(): array
    {
        $queryString = '';
        foreach ($this->topics as $topic) {
            $queryString .= sprintf('topic:%s ', $topic);
        }
        $queryString .= 'language:' . $this->language . ' sort:updated';

        return [
            'query' => [
                'q' => $queryString,
                'per_page' => $this->resultsPerPage,
                'page' => $this->page
            ]
        ];
    }
}
