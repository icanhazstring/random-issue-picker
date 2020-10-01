<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\Command;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Icanhazstring\RandomIssuePicker\Model\SearchIssueModel;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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

    protected function configure(): void
    {
        $this->setDescription('Select a random issue and show link and description.');

        $this->addOption(
            'language',
            'l',
            InputOption::VALUE_OPTIONAL,
            'Which language do you look for e.g. php, js, java, default is php'
        );

        $this->addOption(
            'label',
            '',
            InputOption::VALUE_OPTIONAL,
            'Do you want issues for #hacktoberfest?'
        );
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $randomPageIndex = random_int(1, 10);
        $randomIssueIndex = random_int(0, 99);

        /** @var string|null $languageInput */
        $languageInput = $input->getOption('language');
        /** @var string|null  $labelInput */
        $labelInput = $input->getOption('label');

        $language = $languageInput ?? 'php';
        $label = $labelInput ?? 'good first issue';

        $res = $this->client->request('GET', 'https://api.github.com/search/issues', [
            'query' => [
                'q' => 'is:open is:issue language:' . $language . ' label:"' . $label . '" sort:created-desc',
                'per_page' => 100,
                'page' => $randomPageIndex
            ]
        ]);

        $serializer = SerializerBuilder::create()->build();
        /** @var SearchIssueModel $searchIssueModel */
        $searchIssueModel = $serializer->deserialize((string)$res->getBody(), SearchIssueModel::class, 'json');

        if (empty($searchIssueModel->getItems())) {
            $io->warning("No issue was found with your language: $language and label: $label");
            return 0;
        }

        $randomIssue = $searchIssueModel->getItems()[$randomIssueIndex];

        $io->section($randomIssue->getTitle());

        $io->horizontalTable(
            ['Date Created', 'Status', 'Url'],
            [
                [$randomIssue->getCreatedDate(), $randomIssue->getState(), $randomIssue->getUrl()],
            ]
        );

        $io->newLine(2);

        $randomIssueBody = $randomIssue->getBody();

        if (strlen($randomIssue->getBody()) > 300) {
            $randomIssueBody = substr($randomIssue->getBody(), 0, 300) . '...';
        }

        $io->writeln($randomIssueBody);



        return 0;
    }
}
