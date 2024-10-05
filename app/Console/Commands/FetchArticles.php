<?php

namespace App\Console\Commands;

use App\Services\FetchArticleService;
use Illuminate\Console\Command;

class FetchArticles extends Command
{
    // The name and signature of the console command
    protected $signature = 'articles:fetch';

    // The console command description
    protected $description = 'Fetch articles from external news APIs and store them in the database.';

    protected $fetchArticlesService;

    // Inject the FetchArticleService into the command
    public function __construct(FetchArticleService $fetchArticlesService)
    {
        parent::__construct();
        $this->fetchArticlesService = $fetchArticlesService;
    }

    // Execute the console command
    public function handle()
    {
        // Call the fetchArticles method from the service
        $this->fetchArticlesService->fetchArticles();

        // Output success message
        $this->info('Articles fetched and stored successfully.');
    }
}
