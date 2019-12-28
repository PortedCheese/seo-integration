<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
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
                    @foreach($pages as $key => $page)
                        <tr>
                            <td colspan="4">
                                <h4>{{ $key }}</h4>
                            </td>
                        </tr>
                        @foreach($page as $meta)
                            @include('seo-integration::admin.meta.meta-item', ['meta' => $meta])
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>