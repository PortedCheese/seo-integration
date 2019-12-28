@if ($meta->name == 'title' && !$meta->property)
        <title>{{ $meta->content }}</title>
@elseif ($meta->name == "image")
        <mata property="og:image" content="{{ route('imagecache', ['template' => 'small', 'filename' => $meta->content]) }}">
@else
        <meta
                @if ($meta->content)
                content="{{ $meta->content }}"
                @endif
                @if ($meta->property)
                property="{{ $meta->property }}"
                @elseif ($meta->name)
                name="{{ $meta->name }}"
                @endif>
@endif