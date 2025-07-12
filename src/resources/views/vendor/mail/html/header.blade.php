@props(['url'])
<tr>
<td class="header" align="{{ $align }}">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{url('storage/linkmarks-logo-gray.png')}}" class="logo" alt="Logo">
{!! $slot !!}
</a>
</td>
</tr>
