@extends('layout')

@section('title','Unidad de Medida')

@section('content')
    <style>
        .page-header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            flex-wrap:wrap;
            gap:16px;
            margin-bottom:20px;
        }

        .page-title{
            font-size:2rem;
            font-weight:800;
            color:#1f2937;
            margin-bottom:4px;
        }

        .page-subtitle{
            color:#6b7280;
            margin:0;
            font-size:.98rem;
        }

        .panel-card{
            background:#ffffff;
            border-radius:24px;
            border:1px solid #e5e7eb;
            box-shadow:0 10px 30px rgba(0,0,0,.05);
            padding:28px;
        }

        .toolbar{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
        }

        .toolbar .btn{
            border-radius:12px;
            font-weight:600;
            padding:.65rem 1rem;
        }

        .btn-soft{
            background:#fff;
            border:1px solid #d1d5db;
            color:#374151;
        }

        .btn-soft:hover{
            background:#f9fafb;
            color:#111827;
        }

        .btn-dark-custom{
            background:#6b7280;
            border:1px solid #6b7280;
            color:#fff;
        }

        .btn-dark-custom:hover{
            background:#4b5563;
            border-color:#4b5563;
            color:#fff;
        }

        .table-wrap{
            overflow-x:auto;
        }

        .table-custom{
            width:100%;
            min-width:850px;
            margin:0;
            border-collapse:separate;
            border-spacing:0;
        }

        .table-custom thead th{
            background:#f9fafb;
            color:#111827;
            font-size:.78rem;
            font-weight:800;
            text-transform:uppercase;
            letter-spacing:.04em;
            text-align:center;
            padding:14px 10px;
            border-top:1px solid #d1d5db;
            border-bottom:1px solid #d1d5db;
            border-right:1px solid #d1d5db;
        }

        .table-custom thead th:first-child{
            border-left:1px solid #d1d5db;
            border-top-left-radius:14px;
        }

        .table-custom thead th:last-child{
            border-top-right-radius:14px;
        }

        .table-custom tbody td{
            padding:14px 10px;
            vertical-align:middle;
            border-bottom:1px solid #e5e7eb;
            border-right:1px solid #e5e7eb;
            background:#fff;
            color:#374151;
            font-size:.95rem;
        }

        .table-custom tbody tr td:first-child{
            border-left:1px solid #e5e7eb;
        }

        .table-custom tbody tr:hover td{
            background:#fafafa;
        }

        .table-custom tbody tr:last-child td:first-child{
            border-bottom-left-radius:14px;
        }

        .table-custom tbody tr:last-child td:last-child{
            border-bottom-right-radius:14px;
        }

        .action-group{
            display:flex;
            align-items:center;
            justify-content:center;
            gap:8px;
        }

        .action-btn{
            width:34px;
            height:34px;
            border:none;
            border-radius:10px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            text-decoration:none;
            transition:.2s ease;
            font-size:1rem;
        }

        .action-btn.view{
            background:#eef2ff;
            color:#4338ca;
        }

        .action-btn.view:hover{
            background:#e0e7ff;
            color:#312e81;
        }

        .action-btn.edit{
            background:#f3f4f6;
            color:#374151;
        }

        .action-btn.edit:hover{
            background:#e5e7eb;
            color:#111827;
        }

        .action-btn.delete{
            background:#fef2f2;
            color:#dc2626;
        }

        .action-btn.delete:hover{
            background:#fee2e2;
            color:#991b1b;
        }

        .empty-text{
            color:#9ca3af;
            text-align:center;
        }
    </style>

    <div class="page-header">
        <div>
            <h2 class="page-title">Unidad de Medida</h2>
            <p class="page-subtitle">Listado general de unidades de medida registradas.</p>
        </div>

        <div class="toolbar">
            <button class="btn btn-soft" type="button">
                <i class="bi bi-funnel me-1"></i> Filtrar
            </button>

            <a href="{{ route('unidad_medida.create') }}" class="btn btn-dark-custom">
                <i class="bi bi-plus-circle me-1"></i> Nueva Unidad
            </a>
        </div>
    </div>

    <div class="panel-card">
        <div class="table-wrap">
            <table class="table-custom">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th style="width:160px;">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse(($unidades ?? []) as $unidad)
                    <tr>
                        <td class="text-center">{{ $unidad->id_unidad ?? '' }}</td>
                        <td>{{ $unidad->nombre ?? '' }}</td>
                        <td>{{ $unidad->descripcion ?? '' }}</td>
                        <td>
                            <div class="action-group">
                                <a class="action-btn view"
                                   href="{{ Route::has('unidad_medida.show') ? route('unidad_medida.show', $unidad->id_unidad) : '#' }}"
                                   title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a class="action-btn edit"
                                   href="{{ route('unidad_medida.edit', $unidad->id_unidad) }}"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ Route::has('unidad_medida.destroy') ? route('unidad_medida.destroy', $unidad->id_unidad) : '#' }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Deseas eliminar esta unidad de medida?');"
                                      style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-btn delete" type="submit" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    @for($i = 1; $i <= 6; $i++)
                        <tr>
                            <td class="empty-text">{{ $i }}</td>
                            <td class="empty-text">—</td>
                            <td class="empty-text">—</td>
                            <td>
                                <div class="action-group">
                                    <a class="action-btn view"
                                       href="{{ Route::has('unidad_medida.show') ? route('unidad_medida.show', $i) : '#' }}"
                                       title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a class="action-btn edit"
                                       href="{{ route('unidad_medida.edit', $i) }}"
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <button class="action-btn delete" type="button" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endfor
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
