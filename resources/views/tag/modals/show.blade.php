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
