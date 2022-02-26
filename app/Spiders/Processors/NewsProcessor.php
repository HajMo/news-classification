<?php

namespace App\Spiders\Processors;

use App\Models\Post;
use RoachPHP\Support\Configurable;
use RoachPHP\ItemPipeline\ItemInterface;
use RoachPHP\ItemPipeline\Processors\ItemProcessorInterface;

class NewsProcessor implements ItemProcessorInterface
{
    use Configurable;

    public function processItem(ItemInterface $item): ItemInterface
    {
        $post = Post::create($item->all());

        return $item->set('id', $post->id);
    }
}
