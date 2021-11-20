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
                            Add New Outlet Discount
                        </button>
                    </h2>
                </div>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="product-form">
                                    <div class="form-group" id="outlet-select">
                                        <label >Select Outlet</label>
                                        <select class="form-control" id="discount-outlet" >
                                            @foreach($outlets as $val)
                                                <option id="{{$val->kode_outlet}}"> {{$val->kode_outlet}} - {{$val->nama_outlet}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label >Number</label>
                                        <input type="text" class="form-control" id="discount-id"  style="display: none;">
                                        <input type="text" class="form-control" id="discount-number">
                                    </div>
                                    <div class="form-group">
                                        <label >From</label>
                                        <input type="text" id="discount-from">

                                        <label >To</label>
                                        <input type="text" id="discount-to">
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
                    <table id="discount-outlet-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nomor Surat</th>
                            <th>Kode Outlet</th>
                            <th>Nama Outlet</th>
                            <th>Awal</th>
                            <th>Akhir</th>
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
            table = $('#discount-outlet-table').DataTable({
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: {
                    "url"  : "{{url('api/fetch-discount-outlets')}}",
                    "data" : {
                        "responseWish" : 'datatables',
                    }
                },
                columns: [
                    {"data":"id"},
                    {"data":"no_surat"},
                    {"data":"kode_outlet"},
                    {"data":"nama_outlet"},
                    {"data":"awal"},
                    {"data":"akhir"},
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
            console.log($('#discount-outlet').val());
            console.log($('#discount-from').val());
            console.log($('#discount-to').val());

            if($('#discount-from').val() == '' || $('#discount-from').val() == '') {
                alert("Discount period cannot be empty !");
            } else {
                $.confirm({
                    title: 'Are you sure ?',
                    content: 'New outlet will be added',
                    buttons: {
                        confirm: function () {

                            $.ajax({
                                type: "POST",
                                url: "{{url('api/create-outlet-discount')}}",
                                timeout: 150000,
                                data: 'noSurat='+$('#discount-number').val()+'&kodeOutlet='+$('#discount-outlet option:selected').attr('id')+'&awal='+$('#discount-from').val()+'&akhir='+$('#discount-to').val(),
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

        $( function() {
            $( "#discount-from" ).datepicker();
            $( "#discount-to" ).datepicker();
        } );
    </script>
@endsection
