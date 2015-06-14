<?php
namespace deka6pb\autoparser\components\Concrete\Consumers;

use CURLFile;
use deka6pb\autoparser\components\Abstraction\APostDataConsumerBase;
use deka6pb\autoparser\components\FileFileSystem;
use deka6pb\autoparser\components\PostingException;
use deka6pb\autoparser\models\Posts;
use yii\authclient\clients\VKontakte;
use Yii;
use yii\authclient\OAuthToken;
use yii\base\ErrorException;
use yii\base\Exception;

class VkConsumer extends APostDataConsumerBase {

    public $APP_ID;
    public $APP_SECRET;
    public $GROUP_ID;
    public $ACCESS_TOKEN;

    private $client;

    public function init() {
        $token = new OAuthToken([
            'token' => $this->ACCESS_TOKEN
        ]);
        $this->client = new VKontakte([
            'accessToken' => $token,
            'clientId' => $this->APP_ID,
            'clientSecret' => $this->APP_SECRET
        ]);
    }

    function SendPosts($data) {
        try{
            foreach($data AS $post) {
                if(!($post instanceof Posts)) {
                    throw new ErrorException('This post does not belong to the class Media', 400);
                }

                switch ($post->type) {
                    case self::TYPE_TEXT:
                        $this->SendText($post);
                        break;
                    case self::TYPE_GIF:
                        $this->SendGif($post);
                        break;
                    case self::TYPE_IMG:
                        $this->SendImg($post);
                        break;
                }
            }
        } catch(ErrorException $e) {
            throw new PostingException($e->getMessage(), $e->getCode(), $post);
        }
    }

    //region SendTypes
    private function SendText($post) {
        $attachment = "";
        if(!empty($post->url))
            $attachment = $post->url;

        $this->client->api('wall.post', 'POST', [
            'owner_id' => -$this->GROUP_ID,
            'message' => $post->text,
            'from_group' => 1,
            'attachments' => $attachment
        ]);
    }

    private function SendGif($post) {
        $uploadServer = $this->client->api('docs.getUploadServer', "GET", [
            'gid' => $this->GROUP_ID
        ]);
        $result = [];
        foreach($post->files AS $file) {
            $post_params['file'] = new CurlFile(FileFileSystem::getFilePath($file->name), 'text/html');
            $upload = $this->saveFile($uploadServer, $post_params);

            $result[] = $this->client->api('docs.save', "POST", [
                'file' => $upload->file,
                'gid' => $this->GROUP_ID,
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
        foreach($result AS $v) {
            $attache_array[] = "doc{$v['response'][0]['owner_id']}_{$v['response'][0]['did']}";
        }

        if(!empty($post->url))
            $attache_array[] = $post->url;

        $attachments = implode(",", $attache_array);

        $response = $this->client->api('wall.post', "POST",
            [
                'owner_id' => -$this->GROUP_ID,
                'from_group' => 1,
                'message' => $text,
                'attachments' => "$attachments",
            ]);

        return isset($response['response']['post_id']);
    }

    private function SendImg($post) {
        $uploadServer = $this->client->api('photos.getWallUploadServer', "GET", [
            'gid' => $this->GROUP_ID
        ]);

        $result = [];
        foreach($post->files AS $file) {
            $post_params['photo'] = new CurlFile(FileFileSystem::getFilePath($file->name), 'text/html');
            $upload = $this->saveFile($uploadServer, $post_params);

            $result[] = $this->client->api('photos.saveWallPhoto', "POST", [
                'server' => $upload->server,
                'photo' => $upload->photo,
                'hash' => $upload->hash,
                'gid' => $this->GROUP_ID,
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
        foreach($result AS $v) {
            $attache_array[] = $v['response'][0]['id'];
        }

        if(!empty($post->url))
            $attache_array[] = $post->url;

        $attachments = implode(",", $attache_array);

        $response = $this->client->api('wall.post', "POST",
            [
                'owner_id' => -$this->GROUP_ID,
                'from_group' => 1,
                'message' => $text,
                'attachments' => "$attachments", // uploaded image is passed as attachment

            ]);

        return isset($response['response']['post_id']);
    }
    //endregion

    function SendInvites() {
        $invitedUsers = $this->client->api('groups.getInvitedUsers', 'GET', [
            'group_id' => $this->GROUP_ID,
            'count' => 20,
            'fields' => 'nickname',
        ]);

        $users = $this->client->api('users.search', 'GET', [
            'q' => '',
            'fields' => 'uid',
            'count' => '200',
        ]);

        foreach($users['response'] AS $user) {
            if(!empty($user['uid'])) {
                $this->client->api('groups.invite', 'GET', [
                    'group_id' => $this->GROUP_ID,
                    'user_id' => $user['uid'],
                ]);
            }

        }
    }

    function saveFile($uploadServer, $post_params) {
        $ch = curl_init($uploadServer['response']['upload_url']);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
        $result = curl_exec($ch);

        // check cURL error
        $errorNumber = curl_errno($ch);
        $errorMessage = curl_error($ch);

        curl_close($ch);

        if(!empty($errorNumber)) {
            throw new ErrorException($errorMessage, $errorNumber);
        }

        return json_decode($result);
    }
}