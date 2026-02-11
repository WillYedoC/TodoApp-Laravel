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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
