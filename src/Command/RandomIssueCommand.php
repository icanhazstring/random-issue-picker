<?php
declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\Command;

use GuzzleHttp\Client;
use Icanhazstring\RandomIssuePicker\Model\SearchIssueModel;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RandomIssueCommand extends Command
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        parent::__construct('random:issue');
        $this->client = new Client();
    }

    protected function configure()
    {
        $this->setDescription('Select a random issue and show link and description.');
    }

    /**
     * Executes the current command.
     *
     * @return int|null null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $randomPageIndex = random_int(1, 10);
        $randomIssueIndex = random_int(0, 99);

        $res = $this->client->request('GET', 'https://api.github.com/search/issues', [
            'query' => [
                'q' => 'is:open is:issue language:php label:"good first issue" sort:created-desc',
                'per_page' => 100,
                'page' => $randomPageIndex
            ]
        ]);

        $serializer = SerializerBuilder::create()->build();
        /** @var SearchIssueModel $searchIssueModel */
        $searchIssueModel = $serializer->deserialize((string)$res->getBody(), SearchIssueModel::class, 'json');

        $randomIssue = $searchIssueModel->getItems()[$randomIssueIndex];

        $io->section($randomIssue->getTitle());
        $io->writeln($randomIssue->getUrl());
        $io->newLine(2);

        $randomIssueBody = $randomIssue->getBody();

        if (strlen($randomIssue->getBody()) > 300) {
            $randomIssueBody = substr($randomIssue->getBody(), 0, 100) . '...';
        }

        $io->writeln($randomIssueBody);

        return 0;
    }
}
