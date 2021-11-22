@extends('layout.base')
@section('css-add-on')
@endsection

@section('content')
    <div class="container" style="padding-top: 2%">
        <div class="accordion" id="accordionExample">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Add New Product
                        </button>
                    </h2>
                </div>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="product-form">
                                    <div class="form-group">
                                        <label >Product Code</label>
                                        <input type="text" class="form-control" id="product-id"  style="display: none;">
                                        <input type="text" class="form-control" id="product-code">
                                    </div>
                                    <div class="form-group">
                                        <label >Product Name</label>
                                        <input type="text" class="form-control" id="product-name" >
                                    </div>
                                    <div class="form-group">
                                        <label >Product Price</label>
                                        <input type="text" class="form-control" id="product-price" >
                                    </div>

                                    <button type="button" id="add-btn" class="btn btn-primary" onclick="addProduct()">Add</button>
                                    <button type="button" id="update-btn" class="btn btn-warning" onclick="updateProduct()" style="display: none;">Update</button>
                                    <button type="button" id="cancel-btn" class="btn btn-default" onclick="cancelUpdate()" style="display: none;">Cancel</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Products
                        </button>
                    </h2>
                </div>
                <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample" style="padding: 2%">
                    <table id="products-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js-add-on')
    <script>
        $(document).ready(function() {
            table = $('#products-table').DataTable({
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    "url"  : "{{url('api/fetch-product')}}",
                    "data" : {
                        "responseWish" : 'datatables',
                    }
                },
                columns: [
                    {"data":"id"},
                    {"data":"kode_produk"},
                    {"data":"nama_produk"},
                    {"data":"harga"},
                    {
                        "render": function (data, type, row) {
                            return "<button onclick='editProduct("+row.id+", "+'"'+row.nama_produk+'"'+", "+'"'+row.kode_produk+'"'+", "+'"'+row.harga+'"'+")' class='btn-primary'>Update</button> <button onclick='deleteProduct("+row.id+")' class='btn-danger'>Delete</button>";
                        },
                    }
                ],
            });
        });

        function addProduct() {
            if($('#product-name').val() == '' || $('#product-code').val() == '') {
                alert("Category Name cannot be empty !");
            } else {
                $.confirm({
                    title: 'Are you sure ?',
                    content: 'New product will be added',
                    buttons: {
                        confirm: function () {
                            /*$('body').loadingModal({
                                position: 'auto',
                                text: 'Please Wait...',
                                color: '#FFC108',
                                opacity: '0.7',
                                backgroundColor: 'rgb(0,0,0)',
                                animation: 'wanderingCubes'
                            });*/
                            $.ajax({
                                type: "POST",
                                url: "{{url('api/create-product')}}",
                                timeout: 150000,
                                data: 'kodeProduk='+$('#product-code').val()+'&namaProduk='+$('#product-name').val()+'&hargaProduk='+$('#product-price').val(),
                                success: function(response){
                                    console.log(response);

                                    if(response.status == 'success') {
                                        $.confirm({
                                            title: 'Succeded !!',
                                            content: response.message,
                                            buttons: {
                                                confirm: function() {
                                                    table.draw();
                                                    $('#product-code').val('');
                                                    $('#product-name').val('');
                                                    $('#product-price').val('');
                                                }
                                            }
                                        });
                                    } else {
                                        $.alert({
                                            title: "Something wrong !",
                                            content: response.message
                                        })
                                    }
                                },
                                error: function(){
                                    $.alert({
                                        title: 'Something wrong !',
                                        content: 'Save failed, please make sure your internet connection is stable'
                                    });
                                },
                            });
                        },
                        cancel: function () {},
                    }
                })
            }
        }

        function deleteProduct(id) {
            $.confirm({
                title: 'Are you sure ?',
                content: 'Product will be deleted',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            type: "GET",
                            url: "{{url('api/delete-product')}}",
                            timeout: 150000,
                            data: 'id='+id,
                            success: function(response){
                                console.log(response);

                                if(response.status == 'success') {
                                    $.confirm({
                                        title: 'Succeded !!',
                                        content: response.message,
                                        buttons: {
                                            confirm: function() {
                                                table.draw();
                                            }
                                        }
                                    });
                                } else {
                                    $.alert({
                                        title: "Something wrong !",
                                        content: response.message
                                    })
                                }
                            },
                            error: function(){
                                $.alert({
                                    title: 'Something wrong !',
                                    content: 'Save failed, please make sure your internet connection is stable'
                                });
                            },
                        });
                    },
                    cancel: function () {},
                }
            })
        }

        function editProduct(id, productName, productCode, price) {
            $('#product-id').val(id);
            $('#product-code').val(productCode);
            $('#product-name').val(productName);
            $('#product-price').val(price);
            $('#add-btn').hide();
            $('#update-btn').show();
            $('#cancel-btn').show();
            $('#collapseOne').collapse('show');
            $('#collapseTwo').collapse('hide');
        }

        function updateProduct() {
            $.confirm({
                title: 'Are you sure ?',
                content: 'Product will be updated',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            type: "POST",
                            url: "{{url('api/update-product')}}",
                            timeout: 150000,
                            data: 'kodeProduk='+$('#product-code').val()+'&namaProduk='+$('#product-name').val()+'&id='+$('#product-id').val()+'&harga='+$('#product-price').val(),
                            success: function(response){
                                console.log(response);
                                if(response.status == 'success') {
                                    $.confirm({
                                        title: 'Succeded !!',
                                        content: response.message,
                                        buttons: {
                                            confirm: function() {
                                                table.draw();
                                                cancelUpdate();
                                            }
                                        }
                                    });
                                } else {
                                    $.alert({
                                        title: "Something wrong !",
                                        content: response.message
                                    })
                                }
                            },
                            error: function(){
                                $.alert({
                                    title: 'Something wrong !',
                                    content: 'Save failed, please make sure your internet connection is stable'
                                });
                            },
                        });
                    },
                    cancel: function () {},
                }
            })
        }

        function cancelUpdate() {
            $('#add-btn').show();
            $('#update-btn').hide();
            $('#cancel-btn').hide();
            $('#collapseTwo').collapse('show');
            $('#product-code').val('');
            $('#product-name').val('');
            $('#product-price').val('');
        }
    </script>
@endsection
