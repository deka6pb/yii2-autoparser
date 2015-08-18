<?php
namespace deka6pb\autoparser\components\Concrete\Consumers;

use deka6pb\autoparser\components\PostingException;
use deka6pb\autoparser\components\PostingService\Abstraction\APostDataConsumerBase;
use deka6pb\autoparser\models\Posts;
use ErrorException;
use yii\authclient\clients\Facebook;
use yii\authclient\OAuthToken;


class FbConsumer extends APostDataConsumerBase {

    public $APP_ID;
    public $APP_SECRET;
    public $GROUP_ID;
    public $ACCESS_TOKEN;

    private $client;

    public function init() {
        $token = new OAuthToken([
            'token' => $this->ACCESS_TOKEN
        ]);
        $this->client = new Facebook([
            'accessToken'  => $token,
            'clientId'     => $this->APP_ID,
            'clientSecret' => $this->APP_SECRET
        ]);
    }

    //region SendTypes
    public function SendText($post) {
        $attachment = "";
        if (!empty($post->url))
            $attachment = $post->url;

        $this->client->api('/318031398386432/feed', 'POST', [
            'message'    => 'dfsgh',
        ]);
    }

    public function SendGif($post) {
        $uploadServer = $this->client->api('docs.getUploadServer', "GET", [
            'gid' => $this->GROUP_ID
        ]);
        $result = [];
        foreach ($post->files AS $file) {
            $post_params['file'] = new CurlFile(FileFileSystem::getFilePath($file->name), 'text/html');
            $upload = $this->saveFile($uploadServer, $post_params);

            $result[] = $this->client->api('docs.save', "POST", [
                'file' => $upload->file,
                'gid'  => $this->GROUP_ID,
            ]);
        }

        $text = $post->text;

        if ($post->tags) {
            $text .= "\n\n";

            foreach ($post->tags as $tag) {

                $text .= ' #' . str_replace(' ', '_', $tag);
            }
        }

        $attache_array = [];
        foreach ($result AS $v) {
            $attache_array[] = "doc{$v['response'][0]['owner_id']}_{$v['response'][0]['did']}";
        }

        if (!empty($post->url))
            $attache_array[] = $post->url;

        $attachments = implode(",", $attache_array);

        $response = $this->client->api('wall.post', "POST",
            [
                'owner_id'    => -$this->GROUP_ID,
                'from_group'  => 1,
                'message'     => $text,
                'attachments' => "$attachments",
            ]);

        return isset($response['response']['post_id']);
    }

    public function SendImg($post) {
        $uploadServer = $this->client->api('photos.getWallUploadServer', "GET", [
            'gid' => $this->GROUP_ID
        ]);

        $result = [];
        foreach ($post->files AS $file) {
            $post_params['photo'] = new CurlFile(FileFileSystem::getFilePath($file->name), 'text/html');
            $upload = $this->saveFile($uploadServer, $post_params);

            $result[] = $this->client->api('photos.saveWallPhoto', "POST", [
                'server' => $upload->server,
                'photo'  => $upload->photo,
                'hash'   => $upload->hash,
                'gid'    => $this->GROUP_ID,
            ]);
        }

        $text = $post->text;

        if ($post->tags) {
            $text .= "\n\n";

            foreach ($post->tags as $tag) {

                $text .= ' #' . str_replace(' ', '_', $tag);
            }
        }

        $attache_array = [];
        foreach ($result AS $v) {
            $attache_array[] = $v['response'][0]['id'];
        }

        if (!empty($post->url))
            $attache_array[] = $post->url;

        $attachments = implode(",", $attache_array);

        $response = $this->client->api('wall.post', "POST",
            [
                'owner_id'    => -$this->GROUP_ID,
                'from_group'  => 1,
                'message'     => $text,
                'attachments' => "$attachments", // uploaded image is passed as attachment

            ]);

        return isset($response['response']['post_id']);
    }
}