<ul>
    @foreach($groups as $subgroupKey => $subgroupValue)
        <li>
            <a href="{{ route('view', ['type' => request()->route('type'), 'group' => $subgroupKey]) }}" class="toggle" data-group="{{ $subgroupKey }}">
                {{ $subgroupKey }} ({{ $subgroupValue['total_counts'] }})
            </a>
            @if(!empty($subgroupValue['subgroups']) && $subgroupKey == request()->route('group'))
                @include('groups.undergroups', ['subgroups' => $subgroupValue['subgroups'], 'subgroupKey' => $subgroupKey])
            @endif
        </li>
    @endforeach
</ul>
