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

                    @include('tags.modals.show', ['tag' => $tag])
                    @include('tags.modals.edit', ['tag' => $tag])
                    @include('tags.modals.delete', ['tag' => $tag])
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

@include('tags.modals.create')
@endsection