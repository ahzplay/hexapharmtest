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
                            Add New Product Discount
                        </button>
                    </h2>
                </div>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="product-form">
                                    <div class="form-group" id="outlet-select">
                                        <label >Select Product</label>
                                        <select class="form-control" id="discount-product" >
                                            @foreach($products as $val)
                                                <option id="{{$val->kode_produk}}"> {{$val->kode_produk}} - {{$val->nama_produk}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label >Number</label>
                                        <input type="text" class="form-control" id="discount-id"  style="display: none;">
                                        <input type="text" class="form-control" id="discount-number">
                                    </div>

                                    <div class="form-group">
                                        <label >Discount</label>
                                        <input type="text" class="form-control" id="discount-val">
                                    </div>

                                    <div class="form-group">
                                        <label >Minimum</label>
                                        <input type="text" class="form-control" id="discount-min">
                                    </div>

                                    <div class="form-group">
                                        <label >Maximum</label>
                                        <input type="text" class="form-control" id="discount-max">
                                    </div>

                                    <button type="button" id="add-btn" class="btn btn-primary" onclick="addDiscount()">Add</button>
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
                    <table id="discount-product-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nomor Surat</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Diskon</th>
                            <th>Max</th>
                            <th>Min</th>
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
            table = $('#discount-product-table').DataTable({
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    "url"  : "{{url('api/fetch-discount-product')}}",
                    "data" : {
                        "responseWish" : 'datatables',
                    }
                },
                columns: [
                    {"data":"id"},
                    {"data":"no_surat"},
                    {"data":"kode_produk"},
                    {"data":"nama_produk"},
                    {"data":"diskon_val"},
                    {"data":"min"},
                    {"data":"max"},
                    {
                        "render": function (data, type, row) {
                            return "<button onclick='deleteOutlet("+row.id+")' class='btn-danger'>Delete</button>";
                        },
                    }
                ],
            });
        });

        function addDiscount() {
            console.log($('#discount-id').val());
            console.log($('#discount-number').val());
            console.log($('#discount-product').val());
            console.log($('#discount-val').val());
            console.log($('#discount-max').val());
            console.log($('#discount-min').val());

            if($('#discount-val').val() == '' || $('#discount-max').val() == '' || $('#discount-min').val() == '') {
                alert("Discount period cannot be empty !");
            } else {
                $.confirm({
                    title: 'Are you sure ?',
                    content: 'New discount will be added',
                    buttons: {
                        confirm: function () {

                            $.ajax({
                                type: "POST",
                                url: "{{url('api/create-product-discount')}}",
                                timeout: 150000,
                                data: 'noSurat='+$('#discount-number').val()+'&kodeProduk='+$('#discount-product option:selected').attr('id')+'&diskonVal='+$('#discount-val').val()+'&min='+$('#discount-min').val()+'&max='+$('#discount-max').val(),
                                success: function(response){
                                    console.log(response);

                                    if(response.status == 'success') {
                                        $.confirm({
                                            title: 'Succeded !!',
                                            content: response.message,
                                            buttons: {
                                                confirm: function() {
                                                    table.draw();
                                                    $('#discount-id').val('');
                                                    $('#discount-number').val('');
                                                    $('#discount-outlet').val('');
                                                    $('#discount-from').val('');
                                                    $('#discount-to').val('');
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

    </script>
@endsection
