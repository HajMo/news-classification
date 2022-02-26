<?php

namespace App\Spiders;

use Generator;
use DOMElement;
use App\Models\Post;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;
use RoachPHP\Extensions\LoggerExtension;
use App\Spiders\Processors\NewsProcessor;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;

class SunaSpider extends BasicSpider
{
    public array $startUrls = [
        'https://www.suna-sd.net/search/gold'
    ];

    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
    ];

    public array $spiderMiddleware = [
        //
    ];

    public array $itemProcessors = [
        NewsProcessor::class,
    ];

    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];

    public int $concurrency = 2;

    public int $requestDelay = 1;

    public function parse(Response $response): Generator
    {
        $pages = $response->filter('ul.pagination li.page-item a.page-link')->links();

        foreach ($pages as $page) {
            yield $this->request('GET', $page->getUri(), 'parseBlogPages');
        }
    }

    public function parseBlogPages(Response $response): Generator
    {
        $links = $response->filter('.articles-list .post-info-2 h4 a')->links();

        foreach ($links as $link) {
            yield $this->request('GET', $link->getUri(), 'parseSingleBlogPage');
        }
    }

    public function parseSingleBlogPage(Response $response): Generator
    {
        $title = $response->filter('h1')->text();

        $content = collect();

        $paragraph = '';

        $response->filter('div.post_details_block  > p')->each(fn ($node) => $content->push($node->text()));

        foreach ($content->filter() as $value) {
            $paragraph = $paragraph."\n".$value."\n";
        }

        yield $this->item([
            'title' => $title,
            'content' => $paragraph,
        ]);
    }
}
