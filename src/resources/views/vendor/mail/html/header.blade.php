@props(['url'])
<tr>
<td class="header" align="center">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{url('storage/linkmarks-logo-gray.png')}}" class="logo" alt="linkmarks Logo">
{!! $slot !!}
</a>
</td>
</tr>
