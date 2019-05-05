var table = $('#albums_table').DataTable({
    "serverSide": false,
    "processing": false,
    "ajax": "/index/data",
    "columns": [
        { "data": "title" },
        { "data": "artist" },
        { "data": "category" },
    ],
    "columnDefs": [
        {
            "aTargets": [3],
            "mRender": function (data, type, row) {
                if (row.image_url) {
                    return '<img src="' + row.image_url + '" width="100" alt="image" height="100"></img>';
                } else {
                    return '<p> No image </P>';
                }
            }
        },
        {
            "aTargets": [4],
            "mRender": function (data, type, row) {
                return '<button class="btn edit_btn btn-warning" data-id="' + row.id + '">EDIT</button> ' +
                    ' <button class="del_btn btn btn-danger" data-id="' + row.id + '">DELETE</button>';
            }
        }
    ]
});

var vlidator = $("form").validate({
    rules: {
        title: { minlength: 2 },
    }
});

$('#select_artist').select2({
    theme: "bootstrap"
});
$('#select_category').select2({
    theme: "bootstrap"
});

$("body").on("click", ".add_btn", function () {
    vlidator.resetForm();
    document.getElementById("albumsForm").reset();
    $("form").attr("action", "/index/add");

    $('#select_artist').html("");
    $('#select_category').html("");
    $('#select_artist').html('<option value=""> -- select an artist -- </option>');

    fillLists();

    $("#imgToUpload").hide();
    $(".progress").hide();
    $("#browse").show();
});

$("body").on("click", ".edit_btn", function () {
    vlidator.resetForm();
    $("form").attr("action", "/index/edit");
    var id = $(this).attr('data-id');

    $('#select_artist').html("");
    $('#select_category').html("");

    fillLists();

    $.get("/index/edit", { id: id }, function (result) {
        var data = JSON.parse(result);
        var album = data.album;
        var album_category = data.album_category;

        $("#id").val(album.id)
        $("#title").val(album.title)

        if (album.image_url) {
            $(".img-thumbnail").attr("src", album.image_url);
            $("#image_url").val(album.image_url);
            $("#imgToUpload").show();
        } else {
            $("#imgToUpload").hide();
            $("#browse").show();
        }

        $('#select_artist').val(album.artist_id);
        $('#select_artist').trigger('change');

        var categories = [];
        album_category.forEach(element => {
            categories.push(element.category_id);
        });
        $('#select_category').val(categories);
        $('#select_category').trigger('change');

        $('#modal').modal('show')
    });

    $("#browse").hide();
    $(".progress").hide();
});

$("body").on("click", ".btn-save", function () {
    $("form").ajaxForm(function () {
        table.ajax.reload();
        $('#modal').modal('hide')
    });
});

$("body").on("click", ".del_btn", function () {
    var id = $(this).attr('data-id');

    bootbox.confirm({
        message: "Are you sure that you want to delete?",
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: '/index/delete',
                    data: { id: id },
                    success: function () {
                        table.ajax.reload();
                    }
                });
            }
        }
    });
});

function fillLists() {

    $.get("/artist/data", function (data) {
        var data = JSON.parse(data);
        var artists_array = data.data;

        for (let index = 0; index < artists_array.length; index++) {
            const element = artists_array[index];
            opt = document.createElement('option');
            opt.value = element.id;
            opt.innerHTML = element.name;
            $('#select_artist').append(opt);
        }
    });

    $.get("/category/data", function (data) {
        var data = JSON.parse(data);
        var categories_array = data.data;

        for (let index = 0; index < categories_array.length; index++) {
            const element = categories_array[index];
            opt = document.createElement('option');
            opt.value = element.id;
            opt.innerHTML = element.name;
            $('#select_category').append(opt);
        }
    });
}

var uploader = new plupload.Uploader({
    browse_button: 'browse',
    url: '/index/upload'
});

uploader.init();

uploader.bind('UploadProgress', function (up, file) {
    document.getElementsByClassName('progress').innerHTML = '<div class="progress-bar" role="progressbar" aria-valuenow="' +
        file.percent + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + file.percent + '%"> </div>';
});

uploader.bind('FilesAdded', function (up, files) {
    uploader.start();
    $(".progress").show();
});

uploader.bind('FileUploaded', function (up, file, result) {
    var obj = JSON.parse(result.response);
    var url = obj.result.url;
    $(".img-thumbnail").attr("src", url);
    $("#imgToUpload").show();
    $("#image_url").val(url)
    $("#browse").hide();
    $(".progress").hide();
})

$("body").on("click", "#remove_image", function () {
    $("#image_url").val(null);
    $("#imgToUpload").hide();
    $("#browse").show();
});