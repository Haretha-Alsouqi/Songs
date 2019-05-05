var table = $('#categories_table').DataTable({
    "serverSide": false,
    "processing": false,
    "ajax": "/category/data",
    "columns": [
        { "data": "name" },
    ],
    "aoColumnDefs": [
        /* {
             "aTargets": [1],
             "mRender": function (data, type, row) {
                 return '<img src="' + row[1] + '" width="100" height="100"></img>';
             }
         },*/
        {
            "aTargets": [1],
            "mRender": function (data, type, row) {
                return '<button class="btn edit_btn btn-warning" data-id="' + row.id + '" data-toggle="modal" data-target="#modal">EDIT</button> ' +
                    ' <button class="del_btn btn btn-danger" data-id="' + row.id + '">DELETE</button>';
            }
        }
    ]
});

var vlidator = $("form").validate({
    rules: {
        name: { minlength: 2 },
    }
});

$("body").on("click", ".add_btn", function () {
    vlidator.resetForm();
    document.getElementById("categoriesForm").reset();
    $("form").attr("action", "/category/add");
});

$("body").on("click", ".edit_btn", function () {
    vlidator.resetForm();
    $("form").attr("action", "/category/edit");
    var id = $(this).attr('data-id');

    $.get("/category/edit", { id: id }, function (data) {
        var category = JSON.parse(data);
        $("#id").val(category.id)
        $("#name").val(category.name)
    });
});


$("body").on("click", ".btn-save", function () {
    $("form").ajaxForm(function () {
        table.ajax.reload();
        $('#modal').modal('hide')
    });
});

$("body").on("click", ".del_btn", function () {
    var id = $(this).attr('data-id');
    if (confirm("Confirm to delete")) {

        $.ajax({
            type: 'POST',
            url: '/category/delete',
            data: { id: id },
            success: function () {
                table.ajax.reload();
            }
        });
    }
});