<div class="col-12">
    <form method="post"
          action="{{ route('admin.meta.store-static') }}">
        @csrf
        <div class="form-row align-items-center">
            <div class="col-auto">
                <label for="page" class="sr-only">Page</label>
                <input type="text"
                       id="page"
                       name="page"
                       value="{{ old('page') }}"
                       required
                       placeholder="Page"
                       class="form-control mb-2{{ $errors->has('page') ? ' is-invalid' : '' }}">
                @if ($errors->has('page'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('page') }}</strong>
                    </span>
                @endif
            </div>

            <div class="col-auto">
                <label for="name" class="sr-only">Name</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       placeholder="Name"
                       class="form-control mb-2{{ $errors->has('name') ? ' is-invalid' : '' }}">
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="col-auto">
                <label for="content" class="sr-only">Content</label>
                <input type="text"
                       id="content"
                       name="content"
                       value="{{ old('content') }}"
                       required
                       placeholder="Content"
                       class="form-control mb-2{{ $errors->has('content') ? ' is-invalid' : '' }}">
                @if ($errors->has('content'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('content') }}</strong>
                    </span>
                @endif
            </div>

            <div class="col-auto">
                <button type="submit" class="btn btn-success mb-2">Создать</button>
            </div>
        </div>
    </form>
</div>