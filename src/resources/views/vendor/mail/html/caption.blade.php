@props([
    'color' => 'default',
])

<table class="caption caption-{{$color}}" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="caption-content">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="caption-item">
{{ Illuminate\Mail\Markdown::parse($slot) }}
</td>
</tr>
</table>
</td>
</tr>
</table>

