<?php
namespace deka6pb\autoparser\components\Abstraction;

interface IItemStatus {
    const STATUS_NEW = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_STOPPED = 2;

    public function setNew();
    public function setPublished();
}