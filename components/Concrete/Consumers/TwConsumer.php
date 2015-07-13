<?php
namespace deka6pb\autoparser\components\Concrete\Consumers;

use deka6pb\autoparser\components\Abstraction\APostDataConsumerBase;
use deka6pb\autoparser\components\FileFileSystem;
use deka6pb\autoparser\components\PostingException;
use deka6pb\autoparser\models\Posts;
use ErrorException;
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

    function SendPosts($data) {
        try {
            foreach ($data AS $post) {
                if (!($post instanceof Posts)) {
                    throw new ErrorException('This post does not belong to the class Media', 400);
                }

                switch ($post->type) {
                    case self::TYPE_TEXT:
                        $this->SendText($post);
                        break;
                    case self::TYPE_GIF:
                        $this->SendImg($post);
                        break;
                    case self::TYPE_IMG:
                        $this->SendImg($post);
                        break;
                }
            }
        } catch (ErrorException $e) {
            throw new PostingException($e->getMessage(), $e->getCode(), $post);
        }
    }

    //region SendTypes
    private function SendText($post) {
        if (!empty($post->url))
            $post->text .= $post->url;

        $this->client->api($this->commands["statuses/update"], 'POST', [
            'status' => 'Hello World!'
        ]);
    }

    private function SendImg($post) {
        $medias = "";
        if (!empty($post->url))
            $post->text .= $post->url;

        foreach ($post->files AS $file) {
            $filePath = FileFileSystem::getFilePath($file->name);

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
}