@if ($project["is_visible"])
<tr>
    <td></td>
    <td style="padding-left:{{$indent}}px" class='text-bold' colspan='5'>
        &nbsp;{{ $project['charging_name'] }} 
        @if (!$project["accounts"])
            <small style="float:right">{{ $project['amount'] ? number_format($project['amount'],2) : "" }}</small>
        @endif
    </td>
    <td class='text-bold' align='center'>{{ $project['code'] }}</td>
    <td class='text-smaller text-center'>{{ $project['accounts'] }}</td>
    <td align='right'>
        @if ($project["accounts"])
            {{ $project['amount'] ? number_format($project['amount'],2) : "" }}
        @endif
    </td>
</tr>
@endif




@if (count($project['children']) > 0)
@php
$indent = $indent + 10;
@endphp
@foreach($project['children'] as $projectNew)
    @include('partials.charging', [
        "project" => $projectNew,
        "indent" => $indent,
    ])
@endforeach
@endif