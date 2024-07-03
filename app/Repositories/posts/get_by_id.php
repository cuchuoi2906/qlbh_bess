<?php

use App\Models\Post;
use App\Transformers\PostTransformer;

$vars = null;

$pos_id = input('id') ?? 0;

if ($pos_id) {
    $post = Post::with(['category'])->findByID($pos_id);

    if ($post) {
        $vars = transformer_item($post, new PostTransformer(), ['category']);
    }
}

return [
    'vars' => $vars
];