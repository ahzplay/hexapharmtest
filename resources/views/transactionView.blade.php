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
                            Add New Transaction
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
                                        <select class="form-control" id="trans-outlet" >
                                            <option value="">--Select Outlet--</option>
                                            @foreach($outlets as $val)
                                                <option id="{{$val->kode_outlet}}"> {{$val->kode_outlet}} - {{$val->nama_outlet}} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group" id="product-select" style="display: none;">
                                        <label >Select Product</label>
                                        <select class="form-control" id="trans-product" >
                                            <option value="">--Select Product--</option>
                                            @foreach($products as $val)
                                                <option id="{{$val->kode_produk}}"> {{$val->kode_produk}} - {{$val->nama_produk}} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label >Outlet Discount Availability</label>
                                                <input type="text" class="form-control" id="trans-outlet-availability" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label >Price</label>
                                                <input type="text" class="form-control" id="trans-price" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label >Discount</label>
                                                <input type="text" class="form-control" id="trans-discount" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label >Quantity</label>
                                                <input type="text" class="form-control" id="trans-quantity">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label >Total Price</label>
                                                <input type="text" class="form-control" id="trans-total-price" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label >Total Price After Discount</label>
                                                <input type="text" class="form-control" id="trans-total-price-after" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" id="add-btn" class="btn btn-primary" onclick="addDiscount()">Add</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('js-add-on')
    <script>
        $(document).ready(function() {

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

        $('#trans-outlet').change(function() {
            $('#product-select').show();
            $.ajax({
                type: "GET",
                url: "{{url('api/get-discount-avail')}}",
                timeout: 150000,
                data: 'kode_outlet='+$('#trans-outlet option:selected').attr('id'),
                success: function(response){
                    if(response.avail == 1) {
                        $('#trans-outlet-availability').val("Available - Discount begin from " + response.from + " until " + response.to );
                    } else {
                        $('#trans-outlet-availability').val("Discount not available");
                    }
                },
                error: function(){
                    $.alert({
                        title: 'Something wrong !',
                        content: 'Save failed, please make sure your internet connection is stable'
                    });
                },
            });
        });

        $('#trans-product').change(function() {
            if($('#trans-outlet-availability').val() == "Discount not available" || $('#trans-outlet-availability').val() == '' ) {
                $('#trans-discount').val('-');
            } else {
                $('#trans-discount').val('ss');
                $.ajax({
                    type: "GET",
                    url: "{{url('api/get-discount-product')}}",
                    timeout: 150000,
                    data: 'kode_outlet='+$('#trans-outlet option:selected').attr('id'),
                    success: function(response){
                        if(response.avail == 1) {
                            $('#trans-outlet-availability').val("Available - Discount begin from " + response.from + " until " + response.to );
                        } else {
                            $('#trans-outlet-availability').val("Discount not available");
                        }
                    },
                    error: function(){
                        $.alert({
                            title: 'Something wrong !',
                            content: 'Save failed, please make sure your internet connection is stable'
                        });
                    },
                });
            }

        });

    </script>
@endsection
