<?php

namespace App\Spiders;

use Generator;
use DOMElement;
use App\Models\Post;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;

class SudanTribuneSpider extends BasicSpider
{
    public array $startUrls = [
        'https://www.suna-sd.net/read?id=719877'
    ];

    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
    ];

    public array $spiderMiddleware = [
        //
    ];

    public array $itemProcessors = [
        //
    ];

    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];

    public int $concurrency = 2;

    public int $requestDelay = 1;

    /**
     * @return Generator<ParseResult>
     */
    public function parse(Response $response): Generator
    {
        $title = $response->filter('h1')->text();

        $content = collect();

        $paragraph = '';

        $response->filter('div.post_details_block  > p')->each(fn ($node) => $content->push($node->text()));

        foreach ($content->filter() as $value) {
            $paragraph = $paragraph."\n".$value."\n";
        }

        // Post::create([
        //     'title' => $title,
        //     'content' => $paragraph,
        //     'url' => $response->getRequest()->getUri(),
        // ]);

        yield $this->item([
            'title' => $title,
            'subtitle' => $content,
        ]);
    }
}
