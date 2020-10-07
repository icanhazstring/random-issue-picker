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

    /** @var array */
    private $topics;

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

    public function getPage(): int
    {
        return $this->page;
    }

    public function getResultsPerPage(): int
    {
        return $this->resultsPerPage;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getTopics(): array
    {
        return $this->topics;
    }

    public function getQueryParameters(): array
    {
        $queryString = '';
        foreach ($this->getTopics() as $topic) {
            $queryString .= sprintf('topic:%s ', $topic);
        }
        $queryString .= ' language:' . $this->getLanguage(). 'sort:updated';

        return [
            'query' => [
                'q' => $queryString,
                'per_page' => $this->getResultsPerPage(),
                'page' => $this->getPage()
            ]
        ];
    }

    public function getResponseModel(): string
    {
        return SearchRepositoryModel::class;
    }
}