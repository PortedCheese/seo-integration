<tr>
    <td>{{ $meta->id }}</td>
    <td>
        <code>
            {!! htmlspecialchars($meta->render) !!}
        </code>
    </td>
    <td>
        @if ($meta->separated)
            Отдельно
        @elseif ($parent = $meta->parent)
            Связанное обновление с {{ $parent->id }}
        @endif

    </td>
    <td>
        @can("update", $meta)
            <div role="toolbar" class="btn-toolbar">
                <div class="btn-group mr-1">
                    <a href="{{ route("admin.meta.edit", ["meta" => $meta]) }}" class="btn btn-primary">
                        <i class="far fa-edit"></i>
                    </a>
                    @can("delete", $meta)
                        <button type="button" class="btn btn-danger" data-confirm="{{ "delete-form-{$meta->id}" }}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    @endcan
                </div>
            </div>
            @can("delete", $meta)
                <confirm-form :id="'{{ "delete-form-{$meta->id}" }}'">
                    <template>
                        <form action="{{ route('admin.meta.destroy', ['meta' => $meta]) }}"
                              id="delete-form-{{ $meta->id }}"
                              class="btn-group"
                              method="post">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                        </form>
                    </template>
                </confirm-form>
            @endcan
        @endcan
    </td>
</tr>