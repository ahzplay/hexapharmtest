<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">HexapharmApp -Ari-</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">

            <li class="nav-item">
                <a class="nav-link" href="{{url('transaction-page')}}">Transaction</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Master
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{url('outlet-page')}}">Outlet</a>
                    <a class="dropdown-item" href="{{url('product-page')}}">Product</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{url('discount-outlet-page')}}">Outlet Discount</a>
                    <a class="dropdown-item" href="{{url('discount-product-page')}}">Outlet Product</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
