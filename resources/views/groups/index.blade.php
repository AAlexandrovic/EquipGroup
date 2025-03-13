
    <div class="container">
{{--        <h1>Список групп товаров</h1>--}}
        <ul id="group-list">
{{--           @dump($groups)--}}
            @foreach($groups as $key => $value)
                <li>
                    <a href="{{  route('view', ['type'=>$key])  }}"><strong>{{ $key }}</strong> ({{ $value }})</a>
                </li>
            @endforeach
        </ul>
    </div>

