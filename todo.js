$(function () {

    $("#newTask").submit(function (evt) {
        evt.preventDefault();
        $form = $(this);

        $data = $form.serializeArray();
        $.ajax({
            type: "POST",
            url: "index.php?ctrl=taskAPI&action=create",
            data: $data
        }).done(function (response) {
            $("#addNewTaskPositon").append(taskHTML($data, response));
            $("#newTaskAdd").text("Add new");
            updateToDo();//updetat podatke o listi
        }).fail(function (response) {
            $("#newTaskAdd").text("try again");
        });
    });

    $(document).delegate("form[name='task']", 'submit', function (evt) {//submit update
        $form = $(this);
        var formID = $form.attr('id');

        evt.preventDefault();
        console.log("prevent default");
        var data = $form.serializeArray();
        data.push({name: 'id', value: formID});
        taskUpdate(data);
    });

    $(document).delegate("form[name='task']", 'change', function (evt) {//promjena prikazi button save
        var form = $(this);
        var formID = form.attr('id');

        $("#save" + formID).show();
        console.log(formID);

    });

});

function taskDelete(id) {

    $.ajax({
        type: "POST",
        url: "index.php?ctrl=taskAPI&action=delete",
        data: "id=" + id
    }).done(function (response) {
        form = $('form[id="' + id + '"]');
        form.remove();
        updateToDo();
    }).fail(function (response) {
        $('form[id="' + id + '"]').find('button[name="delete"]').text("try again");
    });
}

function taskUpdate(data) {
    $.ajax({
        type: "POST",
        url: "index.php?ctrl=taskAPI&action=update",
        data: data
    }).done(function (response) {
        $("#save" + data[4].value).hide();
        $("#save" + data[4].value).text("Save");
        $("#preostalo" + data[4].value).text(response[0].preostalo);
        console.log(response[0].preostalo);
        updateToDo();
    }).fail(function (response) {
        $("#save" + data[4].value).text("try again");
    });
}

function updateToDo() {
    var brojTaskova = 0;
    var brojNedovrseno = 0;
    $("form[name='task']").each(function (i) {
        brojTaskova++;
        var status = $(this).find("select[name='status']");
        brojNedovrseno += (status.val() === 'nije zavrseno' ? 1 : 0);
        //console.log(status.val());
    });

    $("#todoBr").attr('value', brojTaskova);
    $("#todoNedoveseno").attr('value', brojNedovrseno);

    var postotak = (((brojTaskova - brojNedovrseno) / brojTaskova) * 100).toFixed(2);
    var elPosto = $("#todoPosto");
    elPosto.text(postotak + "%");
    elPosto.attr('aria-valuenow', postotak);
    elPosto.width(postotak + "%");

    console.log(brojTaskova);
}

function provjeriDatum(id) {
    var datum = document.getElementById("rok" + id);
    var vrjednost = datum.value.split(/[-]/);
    var y = parseInt(vrjednost[0], 10);
    var m = parseInt(vrjednost[1], 10);
    var d = parseInt(vrjednost[2], 10);
    var date = new Date(y, m - 1, d, 0, 0, 0, 0);
    if (m === (date.getMonth() + 1) &&
            d === date.getDate() &&
            y === date.getFullYear()) {
        console.log("tocan unos datuma!");
        console.log(date.toString());
        datum.setCustomValidity("");
        return true;
    } else {
        console.log("krivi unos datuma!");
        datum.setCustomValidity("0000-12-30");
        return false;
    }
}
;

function taskHTML(task, response) {
    $html = "<form id='" + response[0].idTask + "' name='task'>" +
            " <div class='row row-mbotom-5'>" +
            "<div class='col-md-2'>" +
            "   <input class='form-control' name='naziv' value='" + task[1].value + "' placeholder='naziv' required/> " +
            "  </div>" +
            " <div class='col-md-2'>" +
            "<select class='form-control' name='prioritet' selected='" + task[2].value + "'>" +
            " <option" + (task[2].value === "low" ? "selected" : "") + " >low</option>" +
            " <option " + (task[2].value === "normal" ? "selected" : "") + " >normal</option>" +
            " <option " + (task[2].value === "high" ? "selected" : "") + " >high</option>" +
            " </select>" +
            "</div>" +
            "<div class='col-md-2'>" +
            "   <input class='form-control' type='date' id='rok" + response[0].idTask + "'  name='rok' value='" + task[3].value + "' placeholder='rok 2016-08-20' onchange='provjeriDatum(" + response[0].idTask + ")' required/>" +
            "</div>" +
            "<div class='col-md-2'>" +
            "<select class='form-control' name='status'>" +
            "  <option " + (task[4].value === "zavrseno" ? "selected" : "") + " >zavrseno</option>" +
            "  <option " + (task[4].value === "nije zavrseno" ? "selected" : "") + " >nije zavrseno</option>" +
            "</select>" +
            "</div>" +
            "<div class='col-md-2' id='preostalo"+ response[0].idTask +"'>" + response[0].preostalo +
            "</div>" +
            "<div class='col-md-2'>" +
            "   <button class='btn btn-primary' type='submit' value='save' name='save' id='save" + response[0].idTask + "' style='display: none;' >Save</button>" +
            "  <button class='btn btn-danger pull-right' type='button' value='delete' name='delete' id='delete" + response[0].idTask + "' onclick='taskDelete(" + response[0].idTask + ")' >Del</button>" +
            " </div>" +
            "</div>" +
            "</form>";
    return $html;
}
;