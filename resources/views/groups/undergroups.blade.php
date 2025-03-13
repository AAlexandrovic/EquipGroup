<ul>
    @foreach($subgroups as $k => $v)
        @if(empty($v['subgroups']))
            <li>
                <a href="{{ route('view', ['type' => request()->route('type'), 'group' => $subgroupKey, 'products' => $k]) }}" class="toggle" data-group="{{ $subgroupKey }}">
                    {{ $k }} ({{ $v['total_counts'] }})
                </a>
            </li>
        @else
            <li>
                <a href="{{ route('view', ['type' => request()->route('type'), 'group' => $subgroupKey, 'undergroup' => $k]) }}" class="toggle" data-group="{{ $subgroupKey }}">
                    {{ $k }} ({{ $v['total_counts'] }})
                </a>
                @if(request('undergroup'))
                    @include('groups.items', ['items' => $v['subgroups'], 'k' => $k, 'subgroupKey' => $subgroupKey])
                @endif
            </li>
        @endif
    @endforeach
</ul>
