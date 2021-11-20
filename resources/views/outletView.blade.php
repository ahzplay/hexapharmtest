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
                            Add New Outlet
                        </button>
                    </h2>
                </div>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="product-form">
                                    <div class="form-group">
                                        <label >Outlet Code</label>
                                        <input type="text" class="form-control" id="outlet-id"  style="display: none;">
                                        <input type="text" class="form-control" id="outlet-code">
                                    </div>
                                    <div class="form-group">
                                        <label >Outlet Name</label>
                                        <input type="text" class="form-control" id="outlet-name" >
                                    </div>
                                    <div class="form-group">
                                        <label >Address</label>
                                        <input type="text" class="form-control" id="outlet-address" >
                                    </div>
                                    <div class="form-group" id="outlet-select">
                                        <label >Active</label>
                                        <select class="form-control" id="outlet-active" >
                                            <option value="0">Not Active</option>
                                            <option value="1">Active</option>
                                        </select>
                                    </div>

                                    <button type="button" id="add-btn" class="btn btn-primary" onclick="addOutlet()">Add</button>
                                    <button type="button" id="update-btn" class="btn btn-warning" onclick="updateOutlet()" style="display: none;">Update</button>
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
                    <table id="outlets-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Alamat</th>
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
            table = $('#outlets-table').DataTable({
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    "url"  : "{{url('api/fetch-outlet')}}",
                    "data" : {
                        "responseWish" : 'datatables',
                    }
                },
                columns: [
                    {"data":"id"},
                    {"data":"kode_outlet"},
                    {"data":"nama_outlet"},
                    {"data":"alamat"},
                    {
                        "render": function (data, type, row) {
                            return "<button onclick='editProduct("+row.id+", "+'"'+row.kode_outlet+'"'+", "+'"'+row.nama_outlet+'"'+", "+'"'+row.alamat+'"'+", "+row.aktif+")' class='btn-primary'>Update</button> <button onclick='deleteOutlet("+row.id+")' class='btn-danger'>Delete</button>";
                        },
                    }
                ],
            });
        });

        function addOutlet() {
            if($('#outlet-name').val() == '' || $('#outlet-code').val() == '') {
                alert("Outlet name and code cannot be empty !");
            } else {
                $.confirm({
                    title: 'Are you sure ?',
                    content: 'New outlet will be added',
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
                                url: "{{url('api/create-outlet')}}",
                                timeout: 150000,
                                data: 'kodeOutlet='+$('#outlet-code').val()+'&namaOutlet='+$('#outlet-name').val()+'&alamat='+$('#outlet-address').val()+'&aktif='+$('#outlet-active').val(),
                                success: function(response){
                                    console.log(response);

                                    if(response.status == 'success') {
                                        $.confirm({
                                            title: 'Succeded !!',
                                            content: response.message,
                                            buttons: {
                                                confirm: function() {
                                                    table.draw();
                                                    $('#outlet-code').val('');
                                                    $('#outlet-name').val('');
                                                    $('#outlet-name').val('');
                                                    $('#outlet-address').val('');
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

        function deleteOutlet(id) {
            $.confirm({
                title: 'Are you sure ?',
                content: 'Product will be deleted',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            type: "GET",
                            url: "{{url('api/delete-outlet')}}",
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

        function editProduct(id, outletCode, outletName, outletAddress, outletActive) {
            console.log(outletActive);
            $('#outlet-id').val(id);
            $('#outlet-code').val(outletCode);
            $('#outlet-name').val(outletName);
            $('#outlet-address').val(outletAddress);
            $(`#outlet-select option[value='${outletActive}']`).prop('selected', true);
            $('#add-btn').hide();
            $('#update-btn').show();
            $('#cancel-btn').show();
            $('#collapseOne').collapse('show');
            $('#collapseTwo').collapse('hide');
        }

        function updateOutlet() {
            $.confirm({
                title: 'Are you sure ?',
                content: 'Product will be updated',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            type: "POST",
                            url: "{{url('api/update-outlet')}}",
                            timeout: 150000,
                            data: 'kodeOutlet='+$('#outlet-code').val()+'&namaOutlet='+$('#outlet-name').val()+'&alamat='+$('#outlet-address').val()+'&aktif='+$('#outlet-active').val()+'&id='+$('#outlet-id').val(),
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
            $('#outlet-id').val('');
            $('#outlet-code').val('');
            $('#outlet-name').val('');
            $('#outlet-address').val('');
            $(`#outlet-select option[value='0']`).prop('selected', true);
        }
    </script>
@endsection
