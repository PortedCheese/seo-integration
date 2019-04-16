<table class="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Render</th>
        <th>Separated</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach($metas as $meta)
        @include('seo-integration::admin.meta.meta-item', ['meta' => $meta])
    @endforeach
    </tbody>
</table>