@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tareas</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
            <i class="bi bi-plus-circle"></i> Nueva Tarea
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Etiquetas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td><span class="badge bg-info">{{ $task->category->name }}</span></td>
                        <td>
                            @foreach($task->tags as $tag)
                                <span class="badge bg-secondary">{{ $tag->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @if($task->is_completed)
                                <span class="badge bg-success">Completada</span>
                            @else
                                <span class="badge bg-warning">Pendiente</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info" 
                                    data-bs-toggle="modal" data-bs-target="#showTaskModal{{ $task->id }}">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning" 
                                    data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $task->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" data-bs-target="#deleteTaskModal{{ $task->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Ver Tarea -->
                    <div class="modal fade" id="showTaskModal{{ $task->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detalle de Tarea</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <h6>Título</h6>
                                        <p>{{ $task->title }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <h6>Descripción</h6>
                                        <p>{{ $task->description ?? 'Sin descripción' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <h6>Categoría</h6>
                                        <span class="badge bg-info">{{ $task->category->name }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <h6>Etiquetas</h6>
                                        @forelse($task->tags as $tag)
                                            <span class="badge bg-secondary">{{ $tag->name }}</span>
                                        @empty
                                            <p class="text-muted">Sin etiquetas</p>
                                        @endforelse
                                    </div>
                                    <div class="mb-3">
                                        <h6>Estado</h6>
                                        @if($task->is_completed)
                                            <span class="badge bg-success">Completada</span>
                                        @else
                                            <span class="badge bg-warning">Pendiente</span>
                                        @endif
                                    </div>
                                    {{-- <small class="text-muted">
                                        Creada: {{ $task->created_at->format('d/m/Y H:i') }}
                                    </small> --}}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Editar Tarea -->
                    <div class="modal fade" id="editTaskModal{{ $task->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="{{ route('tasks.update', $task) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Tarea</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="edit_title{{ $task->id }}" class="form-label">Título *</label>
                                            <input type="text" class="form-control" id="edit_title{{ $task->id }}" 
                                                   name="title" value="{{ $task->title }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="edit_description{{ $task->id }}" class="form-label">Descripción</label>
                                            <textarea class="form-control" id="edit_description{{ $task->id }}" 
                                                      name="description" rows="3">{{ $task->description }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="edit_category{{ $task->id }}" class="form-label">Categoría *</label>
                                            <select class="form-select" id="edit_category{{ $task->id }}" 
                                                    name="category_id" required>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" 
                                                        {{ $task->category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Etiquetas</label>
                                            <div class="row">
                                                @foreach($tags as $tag)
                                                    <div class="col-md-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" 
                                                                   name="tags[]" value="{{ $tag->id }}" 
                                                                   id="edit_tag{{ $task->id }}_{{ $tag->id }}"
                                                                   {{ $task->tags->contains($tag->id) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="edit_tag{{ $task->id }}_{{ $tag->id }}">
                                                                {{ $tag->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="is_completed" id="edit_is_completed{{ $task->id }}" 
                                                       value="1" {{ $task->is_completed ? 'checked' : '' }}>
                                                <label class="form-check-label" for="edit_is_completed{{ $task->id }}">
                                                    Marcar como completada
                                                </label>
                                            </div>
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

                    <!-- Modal Eliminar Tarea -->
                    <div class="modal fade" id="deleteTaskModal{{ $task->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Confirmar Eliminación</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Estás seguro de que deseas eliminar la tarea <strong>"{{ $task->title }}"</strong>?</p>
                                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
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
                        <td colspan="5" class="text-center">No hay tareas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $tasks->links() }}
    </div>
</div>

<!-- Modal Crear Tarea -->
<div class="modal fade" id="createTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Tarea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Título *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Categoría *</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="">Selecciona una categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Etiquetas</label>
                        <div class="row">
                            @foreach($tags as $tag)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="tags[]" value="{{ $tag->id }}" 
                                               id="tag{{ $tag->id }}"
                                               {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tag{{ $tag->id }}">
                                            {{ $tag->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="is_completed" id="is_completed" value="1"
                                   {{ old('is_completed') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_completed">
                                Marcar como completada
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection