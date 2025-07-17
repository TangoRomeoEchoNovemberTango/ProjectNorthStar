<?php
namespace App\Services;

// include TwitterOAuth library
require_once __DIR__ . '/../Libraries/twitteroauth/src/TwitterOAuth.php';

use Abraham\TwitterOAuth\TwitterOAuth;

class SocialMediaService
{
    private array $cfg;

    public function __construct(array $socialConfig)
    {
        $this->cfg = $socialConfig;
    }

    public function postToTwitter(string $text, ?string $imageUrl = null): array
    {
        $t = new TwitterOAuth(
            $this->cfg['twitter']['consumer_key'],
            $this->cfg['twitter']['consumer_secret'],
            $this->cfg['twitter']['access_token'],
            $this->cfg['twitter']['access_secret']
        );

        if ($imageUrl) {
            $tmp = sys_get_temp_dir() . '/twimg_' . uniqid() . '.jpg';
            file_put_contents($tmp, file_get_contents($imageUrl));
            $media = $t->upload('media/upload', ['media' => $tmp]);
            unlink($tmp);
            $params = [
              'status'    => $text,
              'media_ids' => $media->media_id_string,
            ];
        } else {
            $params = ['status' => $text];
        }

        $resp = $t->post('statuses/update', $params);
        return is_object($resp) ? (array)$resp : (array)$resp;
    }

    public function postToFacebook(string $message, ?string $imageUrl = null): array
    {
        $endpoint = "https://graph.facebook.com/v16.0/{$this->cfg['facebook']['page_id']}/feed";
        $params   = [
          'message'      => $message,
          'access_token' => $this->cfg['facebook']['page_access_token'],
        ];

        if ($imageUrl) {
            $endpoint = "https://graph.facebook.com/v16.0/{$this->cfg['facebook']['page_id']}/photos";
            $params['url']     = $imageUrl;
            $params['caption'] = $message;
        }

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return ['error' => $err];
        }
        return json_decode($json, true);
    }
}
