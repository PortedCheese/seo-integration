@if ($meta->name == 'title' && !$meta->property)
        <title>{{ $meta->content }} - {{ config('app.name', 'Laravel') }}</title>
@else
        <meta
                @if ($meta->content)
                content="{{ $meta->content }}"
                @endif
                @if ($meta->property)
                property="{{ $meta->property }}"
                @elseif ($meta->name)
                name="{{ $meta->name }}"
                @endif

        >
@endif