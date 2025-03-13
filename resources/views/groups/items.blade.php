<ul>
    @foreach($items as $item => $itemsCount)
        <li>
            <a href="{{ route('view', ['type' => request()->route('type'), 'group' => $subgroupKey, 'undergroup' => $k, 'products' => $item]) }}" class="toggle" data-group="{{ $subgroupKey }}">
                {{ $item }} ({{ $itemsCount['total_counts'] }})
            </a>
        </li>
    @endforeach
</ul>
