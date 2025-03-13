<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row align-items-start">
        @if(isset($group))
            <div class="col-md-3">
                @include('groups.view', ['group' => $group])
            </div>
            <div class="col-md-3"> </div>
            <div class="col-md-6">
                @include('products.view')
            </div>
        @elseif(isset($groups))
            <div class="col-md-3">
                @include('groups.index', ['groups' => $groups])
            </div>
            <div class="col-md-3"> </div>
            <div class="col-md-6">
                @include('products.index')
            </div>
        @endif
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>
