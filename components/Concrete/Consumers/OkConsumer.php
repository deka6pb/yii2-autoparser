<?php
namespace deka6pb\autoparser\components\Concrete\Consumers;

use deka6pb\autoparser\components\PostingException;
use deka6pb\autoparser\components\PostingService\Abstraction\APostDataConsumerBase;
use deka6pb\autoparser\models\Posts;
use ErrorException;
use yii\authclient\clients\Facebook;
use yii\authclient\OAuthToken;


class OkConsumer extends APostDataConsumerBase {

    public $APP_ID;
    public $APP_SECRET;
    public $GROUP_ID;
    public $ACCESS_TOKEN;
    public $CODE;

    private $client;

    public function init() {
        $sign = md5("application_key={$this->APP_SECRET}format=jsonmethod=users.getCurrentUser" . md5("{$this->ACCESS_TOKEN}{$this->CODE}"));

        $params = array(
            'method'          => 'users.getCurrentUser',
            'access_token'    => $this->ACCESS_TOKEN,
            'application_key' => $this->APP_SECRET,
            'format'          => 'json',
            'sig'             => $sign
        );
        $p = [
            "place_id" => "53585216798833",
            "media" => [
                "type" => "text",
                "text" => "Text1"
            ]
        ];
        $attachment = json_encode($p);
        $sign = md5("st.attachment=" . $attachment . $this->APP_SECRET);
        $params = array(
            'st.app'          => $this->APP_ID,
            'st.attachment'   => $attachment,
            'st.signature'    => $sign,
        );

        $url = "http://connect.ok.ru/dk?st.cmd=WidgetMediatopicPost&st.app={$params['st.app']}&st.attachment='{$params['st.attachment']}'&st.signature={$params['st.signature']}";
        $userInfo = json_decode(file_get_contents($url), true);
        if (isset($userInfo['uid'])) {
            $result = true;
        }
    }

    //region SendTypes
    public function SendText($post) {
        $attachment = "";
        if (!empty($post->url))
            $attachment = $post->url;

        $this->client->api('/me', 'GET');
        $data = $this->client->api("/me/accounts");

        /*$this->client->api('/318031398386432/feed', 'POST', [
            'message'    => 'dfsgh',
        ]);*/
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