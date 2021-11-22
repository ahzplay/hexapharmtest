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
                                        <input type="hidden" id="trans-min">
                                        <input type="hidden" id="trans-max">
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
                                    <button type="button" id="add-btn" class="btn btn-primary" onclick="addTransaction()">Add</button>
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
                            <th>Outlet</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Diskon</th>
                            <th>Final Price</th>
                        </tr>
                        </thead>
                    </table>
                </div>
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
                    "url"  : "{{url('api/fetch-transaction')}}",
                    "data" : {
                        "responseWish" : 'datatables',
                    }
                },
                columns: [
                    {"data":"id"},
                    {"data":"nama_outlet"},
                    {"data":"nama_produk"},
                    {"data":"jumlah"},
                    {"data":"diskon"},
                    {"data":"harga"},

                ],
            });
        });

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
                $('#trans-discount').val(0);
            } else {
                $('#trans-discount').val(0);
                $.ajax({
                    type: "GET",
                    url: "{{url('api/get-product-discount')}}",
                    timeout: 150000,
                    data: 'kode_produk='+$('#trans-product option:selected').attr('id'),
                    success: function(response){
                        console.log(response);
                        console.log(response.harga);
                        console.log(response.discount_product);
                        if(response.discount_product == null) {
                            console.log('discount is null');
                            $('#trans-min').val(1);
                            $('#trans-max').val(999999999999999);
                            $('#trans-discount').val(0);
                            $('#trans-price').val(response.harga);
                        } else {
                            console.log('discount is available');
                            $('#trans-min').val(response.discount_product.min);
                            $('#trans-max').val(response.discount_product.max);
                            $('#trans-discount').val(response.discount_product.discount);
                            $('#trans-price').val(response.harga);
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

        $('#trans-quantity').blur(function() {
            var min = $('#trans-min').val();
            var max = $('#trans-max').val();
            var quantity = $('#trans-quantity').val();

            if(quantity >= min && quantity <= max) {
                var price = $('#trans-price').val();
                var discount = $('#trans-discount').val();
                var totalPrice = quantity*price;
                var totalDiscount = totalPrice * (discount/100);
                var priceAfterDiscount = totalPrice - totalDiscount;
                $('#trans-total-price').val(totalPrice);
                $('#trans-total-price-after').val(priceAfterDiscount);
            } else {
                alert('Your order doesnt meet the conditions')
            }
        });

        function addTransaction() {
            $.confirm({
                title: 'Are you sure ?',
                content: 'New transaction will be added',
                buttons: {
                    confirm: function () {
                        $.ajax({
                            type: "POST",
                            url: "{{url('api/create-transaction')}}",
                            timeout: 150000,
                            data: 'kodeOutlet='+$('#trans-outlet option:selected').attr('id')+'&kodeProduk='+$('#trans-product option:selected').attr('id')+'&jumlah='+$('#trans-quantity').val()+'&diskon='+$('#trans-discount').val()+'&total='+$('#trans-total-price-after').val(),
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

    </script>
@endsection
