<?php
namespace deka6pb\autoparser\components\PostingService\Concrete;

use deka6pb\autoparser\components\PostingService\Abstraction\APostingService;
use Yii;

class PostingService extends APostingService {
    public function __construct() {
        $this->setConsumers();
        $this->init();
        $this->run();
    }
}