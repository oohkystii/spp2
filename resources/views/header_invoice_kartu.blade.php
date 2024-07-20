<td width="80">
    @if (request('output') == 'pdf')	
        <img src="{{ storage_path() . '/app/' . settings()->get('app_logo') }}" alt="" width="50">
    @else
        <img src="{{ \Storage::url(settings()->get('app_logo')) }}" alt="" width="70">
    @endif
</td>
<td style="text-align: left; vertical-align: middle;">
    <div style="font-size: 20px; font-weight: bold;">{{ settings()->get('app_name', 'SPP') }}</div>
    <div>{{ settings()->get('app_address') }}</div>
</td>
