<?php

class VTodo {

    private $header, $footer;
    public $err;

    function __construct() {
        $this->header = 'tem/header.php';
        $this->footer = 'tem/footer.php';
    }

    function viewAll(\ITodo $Itodo) {//dashboard
        include $this->header;
        ?>
        <div class="panel panel-default container-fluid login-panel">
            <label>Sortiranje:</label>
            <a href="index.php?ctrl=todo&action=viewAll&sortby=naziv" class="btn btn-info" >Po nazivu</a>
            <a href="index.php?ctrl=todo&action=viewAll&sortby=datum_kreiranja" class="btn btn-info" >Po datumu</a>
            <div class="pull-right">
                <a href="index.php?ctrl=todo&action=create" class="btn btn-primary" >Nova to-do lista</a> 
            </div>
        </div>
        <div class="panel panel-default login-panel container-fluid" >   
            <?php foreach ($Itodo->todo as $key) { ?>
                <div class="panel panel-todo" >
                    <?= $key->id ?><br/>
                    <label>Naziv:</label>
                    <?= $key->naziv ?><br/>
                    <label>Datum:</label><br/>
                    <?= $key->datum_kreiranja ?><br/>
                    <label>Taskovi:</label>
                    <?= $key->broj_taskova ?><br/>
                    <label>Nezavrseni:</label>
                    <?= $key->nezavrsenih ?><br/>
                    <a href="index.php?ctrl=todo&action=view&id=<?= $key->id ?>" class="btn btn-primary pull-left" >View</a> 
                    <form method="POST">
                        <button class="btn btn-danger pull-right" type="submit"  name="deleteToDo" value="<?= $key->id ?>">Delete</button> 
                    </form>
                </div>
                <?php
            }
            ?>

        </div>
        <?php
        include $this->footer;
    }

    function view(\Todo $todo) {
        include $this->header;
        $this->viewPodaciLista($todo);
        ?>
        <div class="panel panel-default login-panel">   
            <div class="row" style="margin-bottom: 10px">
                <div class="col-md-2">
                    <label>Naziv</label><?= $this->postaviSortLink($todo->id, 'naziv'); ?>
                </div>
                <div class="col-md-2">
                    <label>Prioritet</label><?= $this->postaviSortLink($todo->id, 'prioritet'); ?>
                </div>
                <div class="col-md-2">
                    <label> Rok</label><?= $this->postaviSortLink($todo->id, 'rok'); ?>
                </div>
                <div class="col-md-2">
                    <label>Status</label><?= $this->postaviSortLink($todo->id, 'status'); ?>
                </div>
                <div class="col-md-2">
                    <label> Preostalo dana</label>
                </div>
            </div>
            <?php foreach ($todo->ITasks->task as $key) { ?>
                <form id="<?= $key->id ?>" name="task">
                    <div class="row row-mbotom-5">
                        <div class="col-md-2">
                            <input class="form-control" name="naziv" value="<?= $key->naziv ?>" placeholder="naziv" required/> 
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" name="prioritet">
                                <option <?= ($key->prioritet == 'low') ? 'selected' : ''; ?> >low</option>
                                <option <?= ($key->prioritet == 'normal') ? 'selected' : ''; ?> >normal</option>
                                <option <?= ($key->prioritet == 'high') ? 'selected' : ''; ?> >high</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input class="form-control" type="date" id="rok<?= $key->id ?>"  name="rok" value=" <?= $key->rok ?>" placeholder="rok 2016-08-20" onchange="provjeriDatum(<?= $key->id ?>)" required/>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" name="status">
                                <option <?= ($key->status == 'zavrseno') ? 'selected' : ''; ?> >zavrseno</option>
                                <option <?= ($key->status == 'nije zavrseno') ? 'selected' : ''; ?> >nije zavrseno</option>
                            </select>
                        </div>
                        <div class="col-md-2" id="preostalo<?= $key->id ?>">
                            <?= $key->preostaloDana() ?>
                        </div>
                        <div class="col-md-2 ">
                            <button class="btn btn-primary" type="submit" value="save" name="save" id="save<?= $key->id ?>" style="display: none;">Save</button>
                            <button class="btn btn-danger pull-right"  type="button" value="delete" name="delete" id="delete<?= $key->id ?>" onclick="taskDelete(<?= $key->id ?>)" >Del</button>
                        </div>
                    </div>
                </form>
            <?php } ?>
            <span id="addNewTaskPositon"></span>
        </div>
        <div class="panel panel-default login-panel"> 
            <div class="row">
                <form id="newTask">
                    <input name="id_to-do" value="<?= $todo->id ?>" hidden/> 
                    <div class="col-md-2 form-group">
                        <input class="form-control" name="naziv" value="" placeholder="naziv" required/>  
                    </div>
                    <div class="col-md-2 form-group">
                        <select class="form-control" name="prioritet">
                            <option>low</option>
                            <option>normal</option>
                            <option>high</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <input class="form-control" type="date" id="roknovi"  name="rok" value="" placeholder="rok 2016-08-20" onchange="provjeriDatum('novi')" required/>
                    </div>
                    <div class="col-md-2 form-group ">
                        <select class="form-control" name="status">
                            <option>zavrseno</option>
                            <option>nije zavrseno</option>
                        </select>
                    </div>
                    <div class="col-md-2 pull-right">
                        <button class="btn btn-success" id="newTaskAdd" type="submit">Add</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
        include $this->footer;
    }

    private function viewPodaciLista(\Todo $todo) {
        ?>
        <div class="panel panel-default login-panel" >   
            <div class="row">
                <div class="form-group col-md-5">
                    <label>Naziv:</label>
                    <input class="form-control" type="text" value="<?= $todo->naziv ?>" disabled="true" />
                </div>
                <div class="form-group col-md-5">
                    <label>Datum kreiranja:</label>
                    <input class="form-control " type="text" value="<?= $todo->datum_kreiranja ?>" disabled="true" />
                </div>
                <div class="form-group col-md-2">
                    <form id="ToDoDelete" method="POST">
                        <button class="btn btn-danger" type="submit"  name="deleteToDo" value="<?= $todo->id ?>">Delete</button> 
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Broj Taskova:</label>
                    <input class="form-control" id="todoBr" type="text" value="<?= $todo->broj_taskova ?>" disabled="true" />
                </div>
                <div class="form-group col-md-4">
                    <label>Nedovrseni Taskovi:</label>
                    <input class="form-control" id="todoNedoveseno" type="text" value="<?= $todo->nezavrsenih ?>" disabled="true" />
                </div>
                <div class="form-group col-md-4">
                    <label>Zavrseno:</label><br/>
                    <div class="progress">
                        <div class="progress-bar" id="todoPosto" role="progressbar" aria-valuenow="<?= $todo->postoZavrseno() ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $todo->postoZavrseno() ?>%">
                            <?= $todo->postoZavrseno() ?>%
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private function postaviSortLink($id, $sortby) {
        $html = '<a class="fa fa-fw fa-sort-asc sort-icon-up-align" href="index.php?ctrl=todo&action=view&id=' . $id . '&sort=' . $sortby . '&order=asc"></a>' .
                '<a class="fa fa-fw fa-sort-desc sort-icon-down-align" href="index.php?ctrl=todo&action=view&id=' . $id . '&sort=' . $sortby . '&order=desc"></a>';

        return $html;
    }

   function create() {
        include $this->header;
        ?>
        <div class="panel panel-default login-panel" >   
            <form method="POST">
                <div class="form-group width300px">
                    <label>Naziv liste:</label>
                    <input class="form-control" type="text" name="naziv" value="" required/>
                </div>
                <button class="btn btn-success" type="submit">Add</button>
            </form>
        </div>
        <?php
        include $this->footer;
    }

}
