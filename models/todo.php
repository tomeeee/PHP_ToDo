<?php

class Todo {
    public $id;
    public $naziv;
    public $datum_kreiranja;
    public $broj_taskova;
    public $nezavrsenih;
    
    public $ITasks;
    function __construct() {
        
    }
    function postoZavrseno(){
        return round((($this->broj_taskova - $this->nezavrsenih)/$this->broj_taskova)*100,2);
    }
}

class ITodo implements \Iterator {
    public $todo;
    private $index = 0;

    function __construct($todoall) {
        $this->todo=$todoall;
    }

    public function next() {
        $this->index++;
    }

    public function current() {
        return $this->todo[$this->index];
    }

    public function key() {
        return $this->index;
    }

    public function rewind() {
        $this->index = 0;
    }

    public function valid() {
        return 0 <= $this->index && $this->index < count($this->todo);
    }

}
