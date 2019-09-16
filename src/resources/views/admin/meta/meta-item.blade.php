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
        <confirm-delete-model-button model-id="{{ $meta->id }}">
            <template slot="edit">
                <a href="{{ route('admin.meta.edit', ['meta' => $meta]) }}"
                   class="btn btn-primary">
                    <i class="far fa-edit"></i>
                </a>
            </template>
            <template slot="delete">
                <form action="{{ route('admin.meta.destroy', ['meta' => $meta]) }}"
                      id="delete-{{ $meta->id }}"
                      class="btn-group"
                      method="post">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                </form>
            </template>
        </confirm-delete-model-button>
    </td>
</tr>