<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {

            $('#create_product').on('click', function () {
                $('#modal_form')[0].reset();
                $('#modal_heading').text('Create Product');
                $('#form_btn').text('Submit');
                $('#id').val('');
                $('#my_modal').modal('show');
            });

            $('#modal_form').on('submit', function (event) {
                event.preventDefault(); //form wont submit automatically, but throght our defined ways

                var form = $("#modal_form")[0];
                var data = new FormData(form);

                // var data = $('#modal_form').serialize();
                // alert(data.get('name'));

                $.ajax({
                    url: "/products/createorupdate",
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        // alert(response.success);
                        $('#modal_form')[0].reset();
                        $('#id').val('');
                        $('#my_modal').modal('hide');
                        fetchrecords();
                    },
                    error: function (response) {
                        console.log(response.responseText);
                    }
                });
            });

            $(document).on('click', '.edit_btn', function (e) {
                e.preventDefault();
                var id = $(this).attr('value');
                // alert(id);

                $.ajax({
                    url: '/getdataformodal',
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: id
                    },
                    success: function (response) {
                        // alert(response.id);
                        $('#modal_form')[0].reset();
                        $('#modal_heading').text('Edit values');
                        $('#form_btn').text('Update');
                        $('#name').val(response.name);
                        $('#id').val(response.id);
                        $('#description').val(response.description);
                        $('#my_modal').modal('show');
                        // alert(response.id);
                    }
                });
            });
            $(document).on('click', '.delete_btn', function (e) {
                e.preventDefault();
                var id = $(this).attr('value');
                // alert(id);

                $.ajax({
                    url: '/delete',
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: id
                    },
                    success: function (response) {
                        // alert(response.success);
                        fetchrecords();
                    }
                });
            });

            function fetchrecords() {
                $.ajax(
                    {
                        url: '/getdata',
                        type: 'GET',
                        success: function (response) {
                            console.log(response);
                            var trow = '';
                            for (var i = 0; i < response.length; i++) {
                                var id = response[i].id;
                                var name = response[i].name;
                                var description = response[i].description;
                                var imagePath = 'products/' + response[i].image;

                                trow += '<tr>';
                                trow += '<td>' + id + '</td>';
                                trow += '<td><img src="' + imagePath + '" height="40" width="40"></td>';
                                // trow += '<td>' + id + '</td>';
                                trow += '<td>' + name + '</td>';
                                trow += '<td>' + description + '</td>';
                                trow += '<td>'
                                    + '<a class="edit_btn btn btn-dark btn-small" value = "' + id + '">Edit</a>'
                                    + '<a class="delete_btn btn btn-danger btn-small" value = "' + id + '">Delete</a>'
                                    + '</td > ';
                                trow += '</tr>';

                                $("#table_body").html(trow);
                            }
                        }
                    });
            }

            fetchrecords();
        });
    </script>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" id="create_product">
                            Create Product
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            @if (session()->has('user_name'))
                                {{session()->get('user_name')}}
                            @else
                                Guest
                            @endif
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container mt-5">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="table_body">
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="my_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal_heading">Create Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="modal_form" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input name="id" id="id" type="hidden">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>

                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" name="description" required>

                        </div>
                        <div class="mb-3">
                            <label class="form-control" for="image">Upload image</label>
                            <input type="file" class="form-label" id="image" name="image" required>

                        </div>
                        <button type="submit" id="form_btn" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
</body>

</html>