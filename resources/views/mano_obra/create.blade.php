@extends('layout')

@section('title','Nueva Mano de Obra')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .dash-form-view{
        min-height:100%;
        background:#f8f8f8;
        font-family:"Arial",sans-serif;
        color:#111;
        padding:20px;
    }

    .form-panel{
        background:#fff;
        padding:40px;
        border-radius:12px;
        box-shadow:0 4px 10px rgba(0,0,0,.05);
        max-width:650px;
        margin:0 auto;
    }

    .header-section{
        border-bottom:1px solid #eaeaea;
        padding-bottom:20px;
        margin-bottom:30px;
    }

    .header-section h1{
        font-size:1.8rem;
        font-weight:700;
        margin:0;
        font-family:"Garamond","Baskerville",serif;
        color:#111;
    }

    .header-section p{
        margin:8px 0 0;
        color:#666;
        font-size:.92rem;
        font-family:"Arial",sans-serif;
    }

    .form-group{
        margin-bottom:22px;
    }

    .form-group label{
        display:block;
        margin-bottom:8px;
        font-weight:600;
        font-size:.95rem;
        color:#333;
    }

    .form-control,
    .form-select{
        width:100%;
        padding:12px 15px;
        border:1px solid #ccc;
        border-radius:8px;
        font-size:1rem;
        background:#fff;
        transition:border-color .2s ease, box-shadow .2s ease;
    }

    .form-control:focus,
    .form-select:focus{
        outline:none;
        border-color:#999;
        box-shadow:0 0 0 3px rgba(0,0,0,.06);
    }

    .form-control.is-invalid,
    .form-select.is-invalid{
        border-color:#dc3545;
    }

    .text-danger{
        color:#dc3545;
        font-size:.85rem;
        margin-top:6px;
        display:block;
        font-family:"Arial",sans-serif;
    }

    .btn-actions{
        display:flex;
        gap:12px;
        flex-wrap:wrap;
        margin-top:8px;
    }

    .btn-submit{
        background:#111;
        color:#fff;
        border:none;
        padding:12px 25px;
        border-radius:8px;
        font-size:.92rem;
        font-weight:600;
        cursor:pointer;
        flex:1;
        min-width:180px;
        transition:background .2s ease;
    }

    .btn-submit:hover{
        background:#333;
    }

    .btn-cancel{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        text-decoration:none;
        background:#fff;
        color:#111;
        border:1px solid #d1d5db;
        padding:12px 25px;
        border-radius:8px;
        font-size:.92rem;
        font-weight:600;
        min-width:180px;
        transition:all .2s ease;
    }

    .btn-cancel:hover{
        background:#f3f4f6;
        color:#111;
    }

    .btn-back{
        display:inline-flex;
        align-items:center;
        gap:8px;
        margin-bottom:20px;
        color:#666;
        text-decoration:none;
        font-size:.92rem;
        font-weight:500;
    }

    .btn-back:hover{
        color:#111;
    }

    .required-mark{
        color:#dc3545;
        margin-left:3px;
    }

    .alert{
        border-radius:10px;
        padding:14px 16px;
        margin-bottom:20px;
        font-family:"Arial",sans-serif;
        font-size:.92rem;
    }

    .alert-danger{
        background:#fff5f5;
        border:1px solid #fecaca;
        color:#991b1b;
    }

    @media (max-width: 768px){
        .form-panel{
            padding:24px;
        }

        .btn-actions{
            flex-direction:column;
        }

        .btn-submit,
        .btn-cancel{
            width:100%;
        }
    }
</style>

<div class="dash-form-view">
    <a href="{{ route('mano_obra.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i>
        Volver
    </a>

    <div class="form-panel">
        <div class="header-section">
            <h1>Nueva Mano de Obra</h1>
            <p>Registra una categoría de mano de obra con su unidad de medida y salario unitario.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Corrige los siguientes errores:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('mano_obra.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="clave">Clave <span class="required-mark">*</span></label>
                <input
                    type="text"
                    id="clave"
                    name="clave"
                    class="form-control @error('clave') is-invalid @enderror"
                    value="{{ old('clave') }}"
                    required
                    maxlength="50"
                    placeholder="Ejemplo: MO-001"
                >
                @error('clave')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="categoria">Categoría <span class="required-mark">*</span></label>
                <input
                    type="text"
                    id="categoria"
                    name="categoria"
                    class="form-control @error('categoria') is-invalid @enderror"
                    value="{{ old('categoria') }}"
                    required
                    maxlength="150"
                    placeholder="Ejemplo: Oficial Albañil"
                >
                @error('categoria')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="unidad_medida_id">Unidad de Medida <span class="required-mark">*</span></label>
                <select
                    id="unidad_medida_id"
                    name="unidad_medida_id"
                    class="form-select @error('unidad_medida_id') is-invalid @enderror"
                    required
                >
                    <option value="">Seleccione una unidad de medida</option>
                    @foreach($unidades as $u)
                        <option value="{{ $u->id }}" {{ old('unidad_medida_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->nombre }} ({{ $u->abreviatura }})
                        </option>
                    @endforeach
                </select>
                @error('unidad_medida_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="salario_unitario">Salario Unitario <span class="required-mark">*</span></label>
                <input
                    type="number"
                    step="0.01"
                    min="0"
                    id="salario_unitario"
                    name="salario_unitario"
                    class="form-control @error('salario_unitario') is-invalid @enderror"
                    value="{{ old('salario_unitario', 0) }}"
                    required
                    placeholder="Ejemplo: 450.00"
                >
                @error('salario_unitario')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="btn-actions">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-save me-2"></i>Guardar
                </button>

                <a href="{{ route('mano_obra.index') }}" class="btn-cancel">
                    <i class="bi bi-x-circle me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection