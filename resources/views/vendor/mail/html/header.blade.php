<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">

            <img src="{{asset('img/logo_email.png') }}?t={{time()}}" class="logo" alt="logo">

            {{ $slot }}

        </a>
    </td>
</tr>
