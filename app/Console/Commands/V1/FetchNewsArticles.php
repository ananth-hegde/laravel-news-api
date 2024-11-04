<?php

namespace App\Console\Commands\V1;

use App\Models\NewsArticle;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
class FetchNewsArticles extends Command
{

    private $newsApiEndpoints = [
        'https://newsapi.org/v2/top-headlines?category=general',
        'https://newsapi.org/v2/top-headlines?category=entertainment',
        'https://newsapi.org/v2/top-headlines?category=business',
        'https://newsapi.org/v2/top-headlines?category=health',
        'https://newsapi.org/v2/top-headlines?category=science',
        'https://newsapi.org/v2/top-headlines?category=sports',
        'https://newsapi.org/v2/top-headlines?category=technology',

    ];
    private $nyTimesApiEndpoints = [
        'https://api.nytimes.com/svc/topstories/v2/home.json',
        'https://api.nytimes.com/svc/topstories/v2/business.json',
        'https://api.nytimes.com/svc/topstories/v2/health.json',
        'https://api.nytimes.com/svc/topstories/v2/science.json',
        'https://api.nytimes.com/svc/topstories/v2/sports.json',
        'https://api.nytimes.com/svc/topstories/v2/technology.json',
    ];
    private $guardianApiEnpoint = 'https://content.guardianapis.com/search';

    private $newsApiToken = '04acd37a387f4e399bd5266397dafd19';
    private $nyTimesApiToken = 'CaxFuyx4JptpBKR3SiyNdFQOp7vKGdn3';

    private $guardianApiToken = 'e03e3894-da1d-4ee8-9972-ea6ed2f46736';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Client();
        $newsArticles = [];
        $sanitizedArticles = [];
        //Fetch news articles from newsapi.org
        foreach ($this->newsApiEndpoints as $endpoint) {
            $category = explode('=', $endpoint)[1];
            $newEndpoint = $endpoint . '&apiKey=' . $this->newsApiToken;
            $response = $client->request('GET', $newEndpoint);

            $newsArticles = json_decode($response->getBody(), true);
            
            foreach ($newsArticles['articles'] as $article) {
                $sanitizedArticle = [
                    'title' => $article['title'],
                    'url' => $article['url'],
                    'source' => $article['source']['name'],
                    'category' => $category,
                    'author' => $article['author'],
                    'published_at' => date('Y-m-d h:i:s', strtotime($article['publishedAt'])),
                ];
                NewsArticle::firstOrCreate(['url' => $article['url']], $sanitizedArticle);
            }

        }
        //Fetch news articles from NY Times
        foreach ($this->nyTimesApiEndpoints as $endpoint) {
            $category = explode(".", explode('/', $endpoint)[6])[0];
            $newEndpoint = $endpoint . '?api-key=' . $this->nyTimesApiToken;
            $response = $client->request('GET', $newEndpoint);
            $newsArticles = json_decode($response->getBody(), true);
            foreach ($newsArticles['results'] as $article) {
                $sanitizedArticle = [
                    'title' => $article['title'],
                    'url' => $article['url'],
                    'source' => 'NY Times',
                    'category' => $category,
                    'author' => $article['byline'],
                    'published_at' => date('Y-m-d h:i:s', strtotime($article['published_date'])),
                ];
                NewsArticle::firstOrCreate(['url' => $article['url']], $sanitizedArticle);
            }
            sleep(12); //sleep for 12 seconds to avoid rate limiting
        }

        //Fetch news articles from Guardian
        $guardianEndpoint = $this->guardianApiEnpoint . '?api-key=' . $this->guardianApiToken;
        $response = $client->request('GET', $guardianEndpoint);
        $newsArticles = json_decode($response->getBody(), true);
        foreach ($newsArticles['response']['results'] as $article) {
            $sanitizedArticle = [
                'title' => $article['webTitle'],
                'url' => $article['webUrl'],
                'source' => 'The Guardian',
                'category' => strtolower($article['sectionName']),
                'author' => null,
                'published_at' => date('Y-m-d h:i:s', strtotime($article['webPublicationDate'])),
            ];
            NewsArticle::firstOrCreate(['url' => $article['webUrl']], $sanitizedArticle);

        }
    }
}