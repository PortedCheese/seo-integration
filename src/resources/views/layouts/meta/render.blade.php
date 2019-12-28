@if ($meta->name == 'title' && !$meta->property)
<title>{{ $meta->content }}</title>
@elseif ($meta->name == "image")
<meta @if ($meta->content) content="{{ route("imagecache", ["template" => "medium", "filename" => $meta->content]) }}" @endif @if ($meta->property) property="{{ $meta->property }}"  @elseif ($meta->name) name="{{ $meta->name }}" @endif>
@else
<meta @if ($meta->content) content="{{ $meta->content }}" @endif @if ($meta->property) property="{{ $meta->property }}"  @elseif ($meta->name) name="{{ $meta->name }}" @endif>
@endif