
{{--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////--}}
<ul>
    @foreach($group as $groupName => $groups)
        <li>
            <a href="/{{$groupName}}"><strong>{{ $groupName }} ({{ $groups['total_counts'] }})</strong></a>
            @if($groupName == request()->route('type'))
                @include('groups.subgroups', ['groups' => $groups['subgroups'], 'groupName' => $groupName])
            @endif
        </li>
    @endforeach
</ul>
