@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Etiquetas</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTagModal">
            <i class="bi bi-plus-circle"></i> Nueva Etiqueta
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tareas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tags as $tag)
                    <tr>
                        <td>{{ $tag->id }}</td>
                        <td><span class="badge bg-secondary">{{ $tag->name }}</span></td>
                        <td><span class="badge bg-primary">{{ $tag->tasks_count }}</span></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info" 
                                    data-bs-toggle="modal" data-bs-target="#showTagModal{{ $tag->id }}">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning" 
                                    data-bs-toggle="modal" data-bs-target="#editTagModal{{ $tag->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" data-bs-target="#deleteTagModal{{ $tag->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Ver Tag -->
                    <div class="modal fade" id="showTagModal{{ $tag->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detalle de Etiqueta</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <h6>Nombre</h6>
                                        <span class="badge bg-secondary fs-5">{{ $tag->name }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <h6>Tareas Asociadas ({{ $tag->tasks->count() }})</h6>
                                        @forelse($tag->tasks as $task)
                                            <div class="alert alert-secondary">
                                                <strong>{{ $task->title }}</strong><br>
                                                <small>{{ Str::limit($task->description, 80) }}</small>
                                            </div>
                                        @empty
                                            <p class="text-muted">No hay tareas con esta etiqueta</p>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Editar Tag -->
                    <div class="modal fade" id="editTagModal{{ $tag->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('tags.update', $tag) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Etiqueta</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="edit_name{{ $tag->id }}" class="form-label">Nombre *</label>
                                            <input type="text" class="form-control" 
                                                   id="edit_name{{ $tag->id }}" 
                                                   name="name" value="{{ $tag->name }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Actualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Eliminar Tag -->
                    <div class="modal fade" id="deleteTagModal{{ $tag->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Confirmar Eliminación</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Estás seguro de eliminar la etiqueta <strong>"{{ $tag->name }}"</strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <form action="{{ route('tags.destroy', $tag) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay etiquetas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $tags->links() }}
    </div>
</div>

<!-- Modal Crear Tag -->
<div class="modal fade" id="createTagModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('tags.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Etiqueta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection