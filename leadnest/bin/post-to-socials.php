#!/usr/bin/env php
<?php
require __DIR__ . '/../config/config.php';
$socialConfig = require __DIR__ . '/../config/social.php';

use App\Models\SocialPost;
use App\Services\SocialMediaService;

$pdo = new PDO(DSN, DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
SocialPost::init($pdo);

$svc   = new SocialMediaService($socialConfig);
$posts = SocialPost::due();

foreach ($posts as $post) {
    try {
        $out = [
            'twitter'  => $svc->postToTwitter($post->content, $post->image_url),
            'facebook' => $svc->postToFacebook($post->content, $post->image_url),
        ];
        $post->status = 'sent';
        $post->result = json_encode($out);
    } catch (\Throwable $e) {
        $post->status = 'error';
        $post->result = $e->getMessage();
    }
    $post->save();
}
