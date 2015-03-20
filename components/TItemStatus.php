<?php
namespace deka6pb\autoparser\components;

trait TItemStatus {
    public function setNew() {
        $this->setScenario($this::SCENARIO_INSERT);
        $this->status = self::STATUS_NEW;
        return $this->save();
    }

    public function setPublished() {
        $this->setScenario($this::SCENARIO_UPDATE);
        $this->status = self::STATUS_PUBLISHED;
        return $this->update();
    }

    public function setStopped() {
        $this->setScenario($this::SCENARIO_UPDATE);
        $this->status = self::STATUS_STOPPED;
        return $this->update();
    }
}