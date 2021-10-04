<?php

namespace Icanhazstring\RandomIssuePicker\VersionControlAdapter;

use GuzzleHttp\Client;
use Icanhazstring\RandomIssuePicker\VersionControlAdapter\Github\Model\SearchIssueModel;
use Icanhazstring\RandomIssuePicker\VersionControlAdapter\Github\Model\SearchRepositoryModel;
use Icanhazstring\RandomIssuePicker\VersionControlAdapter\Github\Request\IssueSearchRequest;
use Icanhazstring\RandomIssuePicker\VersionControlAdapter\Github\Request\RepositorySearchRequest;
use JMS\Serializer\SerializerBuilder;

class Github implements VersionControlAdapterInterface
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string[] $topics
     */
    public function findRandomRepository(string $language, array $topics): ?RepositoryModelInterface
    {
        $repositorySearchRequest = new RepositorySearchRequest(
            $this->getRandomPageIndex(),
            100,
            $language,
            $topics
        );

        $rawResponse = $this->client->request(
            $repositorySearchRequest->getMethod(),
            $repositorySearchRequest->getUrl(),
            $repositorySearchRequest->getQueryParameters()
        );

        $serializer = SerializerBuilder::create()->build();

        /** @var SearchRepositoryModel $searchRepositoryModel */
        $searchRepositoryModel = $serializer->deserialize(
            (string)$rawResponse->getBody(),
            SearchRepositoryModel::class,
            'json'
        );

        return $searchRepositoryModel->findFirstWithOpenIssues();
    }

    public function findRandomIssueFromRepository(RepositoryModelInterface $repository, string $label): ?IssueModelInterface
    {
        $issueSearchRequest = new IssueSearchRequest(
            $this->getRandomPageIndex(1),
            100,
            $label,
            $repository->getIdentifier()
        );

        $rawResponse = $this->client->request(
            $issueSearchRequest->getMethod(),
            $issueSearchRequest->getUrl(),
            $issueSearchRequest->getQueryParameters()
        );

        $serializer = SerializerBuilder::create()->build();

        $searchIssueModel = $serializer->deserialize(
            (string) $rawResponse->getBody(),
            SearchIssueModel::class,
            'json'
        );

        return $searchIssueModel->getRandom();
    }

    private function getRandomPageIndex(int $max = 10): int
    {
        return random_int(1, $max);
    }
}
