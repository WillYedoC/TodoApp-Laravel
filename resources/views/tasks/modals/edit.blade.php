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
