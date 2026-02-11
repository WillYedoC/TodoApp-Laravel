<!-- Modal Ver Categoría -->
<div class="modal fade" id="showCategoryModal{{ $category->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6>Nombre</h6>
                    <p>{{ $category->name }}</p>
                </div>
                <div class="mb-3">
                    <h6>Tareas Asociadas ({{ $category->tasks->count() }})</h6>
                    @forelse($category->tasks as $task)
                        <div class="alert alert-secondary">
                            <strong>{{ $task->title }}</strong><br>
                            <small>{{ Str::limit($task->description, 80) }}</small>
                        </div>
                    @empty
                        <p class="text-muted">No hay tareas en esta categoría</p>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
