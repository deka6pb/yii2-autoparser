<?php
namespace deka6pb\autoparser\components\Concrete\Consumers;

use deka6pb\autoparser\components\PostingService\Abstraction\APostDataConsumerBase;
use yii\authclient\clients\Twitter;
use yii\authclient\OAuthToken;


class TwConsumer extends APostDataConsumerBase {

    public $APP_ID;
    public $APP_SECRET;
    public $GROUP_ID;
    public $ACCESS_TOKEN;
    public $CODE;

    private $client;

    public $commands = [
        "statuses/update" => 'https://api.twitter.com/1.1/statuses/update.json',
        "media/upload"    => 'https://upload.twitter.com/1.1/media/upload.json',
    ];

    public function init() {
        $token = new OAuthToken([
            'token'       => $this->ACCESS_TOKEN,
            'tokenSecret' => $this->CODE
        ]);
        $this->client = new Twitter([
            'accessToken'    => $token,
            'consumerKey'    => $this->APP_ID,
            'consumerSecret' => $this->APP_SECRET
        ]);
    }

    //region SendTypes
    public function SendText($post) {
        if (!empty($post->url))
            $post->text .= $post->url;

        $this->client->api($this->commands["statuses/update"], 'POST', [
            'status' => 'Hello World!'
        ]);
    }

    public function SendImg($post) {
        $medias = "";
        if (!empty($post->url))
            $post->text .= $post->url;

        foreach ($post->files AS $file) {
            $filePath = $file->filePath;

            $result = $this->client->api($this->commands["media/upload"], "POST",
                [
                    'media' => base64_encode(file_get_contents($filePath)),
                ]
            );
            $medias .= "," . $result["media_id_string"];
        }

        $code = $this->client->api($this->commands["statuses/update"], "POST",
            [
                'media_ids' => trim($medias, ','),
                'status'    => $post->text,
            ]
        );


        if (empty($code["id_str"])) {
            return false;
        }

        return $code["id_str"];
    }

    public function SendGif($post) {

    }
}