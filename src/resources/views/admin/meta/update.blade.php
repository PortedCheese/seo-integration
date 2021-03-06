@extends('admin.layout')

@section('page-title', 'Update - ')
@section('header-title', 'Meta update')

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post"
                      action="{{ route('admin.meta.update', ['meta' => $meta]) }}">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="model" value="{{ $hasModel ? 'on' : 'off' }}">
                    <input type="hidden" name="back" value="{{ $back }}">
                    @if (!$hasModel)
                        <div class="form-group">
                            <label for="page">Page</label>
                            <input type="text"
                                   id="page"
                                   name="page"
                                   value="{{ old('page') ? old('page') : $meta->page }}"
                                   {{--required--}}
                                   placeholder="Page"
                                   class="form-control mb-2{{ $errors->has('page') ? ' is-invalid' : '' }}">
                            @if ($errors->has('page'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('page') }}</strong>
                            </span>
                            @endif
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') ? old('name') : $meta->name }}"
                               required
                               placeholder="Name"
                               class="form-control mb-2{{ $errors->has('name') ? ' is-invalid' : '' }}">
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="content">Content</label>
                        <input type="text"
                               id="content"
                               name="content"
                               value="{{ old('content') ? old('content') : $meta->content }}"
                               required
                               placeholder="Content"
                               class="form-control mb-2{{ $errors->has('content') ? ' is-invalid' : '' }}">
                        @if ($errors->has('content'))
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('content') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="property">Property</label>
                        <input type="text"
                               id="property"
                               name="property"
                               value="{{ old('property') ? old('property') : $meta->property }}"
                               placeholder="Property"
                               class="form-control mb-2{{ $errors->has('property') ? ' is-invalid' : '' }}">
                        @if ($errors->has('property'))
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('property') }}</strong>
                        </span>
                        @endif
                    </div>

                    @if ($meta->parent)
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       @if (old('separated') && !$meta->separated)
                                       checked
                                       @elseif ($meta->separated)
                                       checked
                                       @endif
                                       id="separated"
                                       name="separated">
                                <label class="form-check-label" for="separated">
                                    Отдельно
                                </label>
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <button type="submit" class="btn btn-success mb-2">Обновить</button>
                        <a href="{{ $back }}" class="btn btn-dark mb-2">Назад</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection