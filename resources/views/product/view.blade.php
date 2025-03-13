<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
<div>
@foreach($breath as $k => $v)
    @php
    if($k < $count - 1){
            $array[]= $v;
    @endphp
    <a href="/@php echo implode('/', $array) @endphp"> {{$v}} </a>  →
    @php
        }else{
            $array = implode('/', $array);
            $array = $array . '?products=' . $v;
            @endphp
                <a href="/@php echo $array @endphp"> {{$v}} </a>
            @php
        }
    @endphp
@endforeach
</div>
<div>
@foreach($product as $key => $value)
        <span class="font-weight-bold display-4">{{ $value['name'] }}</span><br>
        <span class="font-weight-bold display-4"> Цена: {{ $value['prices']['price'] }}</span>
@endforeach
</div>
</body>
