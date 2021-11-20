@extends('layout.base')
@section('css-add-on')
@endsection

@section('content')

    <div id="myCarousel" class="carousel slide" data-ride="carousel"">
        {{--<ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>--}}
        <div class="carousel-inner">
            {{--<div class="carousel-item active">
                <img src="{{asset('img/slider1.jpg')}}" alt="RockAroma Slider" style="width:100%; max-height:670px;">
            </div>--}}
            <div class="carousel-item active">
                <a href="#" target="_blank"><img src="{{url('img/pharmacy.jpg')}}" alt="RockAroma Slider" style="width:100%; max-height:670px;"></a>
            </div>
        </div>
        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="container"  style="padding-bottom: 2%; padding-top: 2%;">
        <div class="row">
            <div class="col-md-4">
                <div class="card" style="width: 17.5rem;">
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-card">
                            <img src="{{url('img/products/daneuron.png')}}" class="image-card" width="120">
                        </p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-add-on')
    <script>
        $(document).ready(function() {

            $('#myCarousel.slide').carousel({
                interval: 4000,
                pause: "none"
            });
        })
    </script>
@endsection
