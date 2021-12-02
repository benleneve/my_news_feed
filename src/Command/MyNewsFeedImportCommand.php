<?php

namespace App\Command;

use DateTimeImmutable;
use App\Entity\News;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MyNewsFeedImportCommand extends Command
{
    private const ARGUMENT_SEARCH_TYPE = 'searchType';
    private const ARGUMENT_NUMBER_MAX_OF_NEWS = 'numberMaxOfNews';
    private const OPTION_DRY_RUN = 'dry-run';

    private const NUMBER_NEWS_DEFAULT = 5;
    private const NUMBER_MAX_NEWS = 20;

    private HttpClientInterface $client;
    private EntityManagerInterface $entityManager;
    private string $apiUrl;
    private string $apiKey;

    protected static $defaultName = 'app:my_news_feed:import';
    protected static $defaultDescription = 'Imports a predefined number of articles in the news table';

    public function __construct(
        $apiUrl,
        $apikey,
        HttpClientInterface $client,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct();

        $this->apiUrl = $apiUrl;
        $this->apiKey = $apikey;
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                self::ARGUMENT_SEARCH_TYPE,
                InputArgument::REQUIRED,
                'Indicates the type of article to search'
            )
            ->addArgument(
                self::ARGUMENT_NUMBER_MAX_OF_NEWS,
                InputArgument::OPTIONAL,
                'Indicates the number of articles to import'
            )
            ->addOption(
                self::OPTION_DRY_RUN,
                null,
                InputOption::VALUE_NONE,
                'Invokes command without executing the database queries.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        if ($input->getOption(self::OPTION_DRY_RUN)) {
            $io->note('Start news creation -- dry run mode');
        } else {
            $output->writeln('Start news creation');
        }

        $numberNewsCreated = 0;
        $searchType = $input->getArgument(self::ARGUMENT_SEARCH_TYPE);
        $numberMaxOfNews = $input->getArgument(self::ARGUMENT_NUMBER_MAX_OF_NEWS);

        if ($searchType) {
            $io->note(sprintf('You passed an searchType argument: %s', $searchType));
        }

        if ($numberMaxOfNews) {
            $io->note(sprintf('You passed an numberMaxOfNews argument: %s', $numberMaxOfNews));
            if ($numberMaxOfNews > self::NUMBER_MAX_NEWS) {
                $numberMaxOfNews = self::NUMBER_MAX_NEWS;
                $io->note(
                    sprintf(
                        'The number of news items entered exceeds the maximum limit. %s items will be imported',
                        $numberMaxOfNews
                    )
                );
            }
        } else {
            $numberMaxOfNews = self::NUMBER_NEWS_DEFAULT;
        }

        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl.'?q='.$searchType.'&apiKey='.$this->apiKey
            );
            if ($response->getStatusCode() === Response::HTTP_OK) {
                $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

                // retrieves the total number of news to be created in the database
                $numberOfArticle = $content['totalResults'];
                $numberOfNews = $numberOfArticle < (int)$numberMaxOfNews ? $numberOfArticle : (int)$numberMaxOfNews;

                $repository = $this->entityManager->getRepository(News::class);


                foreach ($content['articles'] as $article) {
                    $articleSlug = md5($article['title']);
                    // checks if the news has not already been registered in the database
                    $newsAlreadyExist = $repository->findBy(['slug' => $articleSlug]);
                    if (empty($newsAlreadyExist)) {
                        $numberNewsCreated++;
                        // register a new in the database
                        $news = new News();
                        $news->setSlug(md5($article['title']))
                            ->setTitle($article['title'])
                            ->setAuthor($article['author'])
                            ->setDescription($article['description'])
                            ->setContent($article['content'])
                            ->setUrl($article['url'])
                            ->setImageUrl($article['urlToImage'])
                            ->setPublishedAt(new DateTimeImmutable($article['publishedAt']));
                        if (!$input->getOption(self::OPTION_DRY_RUN)) {
                            $this->entityManager->persist($news);
                        }
                    }
                    if ($numberNewsCreated === $numberOfNews) {
                        break;
                    }
                }
                if (!$input->getOption(self::OPTION_DRY_RUN)) {
                    $this->entityManager->flush();
                }
            }
        } catch (\Throwable $t) {
            $io->error($t->getMessage().' in '.$t->getFile().' on line '.$t->getLine());

            return Command::FAILURE;
        }

        $io->success(sprintf('%s news have just been created successfully', $numberNewsCreated));

        if ($input->getOption(self::OPTION_DRY_RUN)) {
            $io->note('Finish news creation -- dry run mode');
        } else {
            $output->writeln('Finish news creation');
        }

        return Command::SUCCESS;
    }
}
