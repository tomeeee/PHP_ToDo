<?php

class Task {

    public $id;
    public $naziv;
    public $prioritet;
    public $rok;
    public $status;

    function __construct() {
        
    }

    function popuni($naziv, $prioritet, $rok, $status) {
        $this->naziv = $naziv;
        $this->prioritet = $prioritet;
        $this->rok = $rok;
        $this->status = $status;
    }

    function preostaloDana() {
        if (is_int($this->rok)) {
            $rok = $this->rok;
        } else {
            $rok = strtotime($this->rok);
        }
        return round(($rok - time()) / 60 / 60 / 24, 2);
    }

}

class ITask implements \Iterator {

    public $task;
    private $index = 0;

    function __construct($alltask) {
        $this->task = $alltask;
    }

    public function next() {
        $this->index++;
    }

    public function current() {
        return $this->task[$this->index];
    }

    public function key() {
        return $this->index;
    }

    public function rewind() {
        $this->index = 0;
    }

    public function valid() {
        return 0 <= $this->index && $this->index < count($this->task);
    }

}
