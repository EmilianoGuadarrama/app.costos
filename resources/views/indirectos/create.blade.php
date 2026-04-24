@extends('layout')

@section('title','Nuevo Indirecto')

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

    .form-control{
        width:100%;
        padding:12px 15px;
        border:1px solid #ccc;
        border-radius:8px;
        font-size:1rem;
        background:#fff;
        transition:border-color .2s ease, box-shadow .2s ease;
    }

    .form-control:focus{
        outline:none;
        border-color:#999;
        box-shadow:0 0 0 3px rgba(0,0,0,.06);
    }

    .text-danger{
        color:#dc3545;
        font-size:.85rem;
        margin-top:6px;
        display:block;
        font-family:"Arial",sans-serif;
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

    .required-mark{
        color:#dc3545;
        margin-left:3px;
    }

    .alert-danger{
        background:#fff5f5;
        border:1px solid #fecaca;
        color:#991b1b;
        border-radius:10px;
        padding:14px 16px;
        margin-bottom:20px;
        font-family:"Arial",sans-serif;
        font-size:.92rem;
    }

    @media (max-width:768px){
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
    <a href="{{ route('indirectos.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i>
        Volver
    </a>

    <div class="form-panel">
        <div class="header-section">
            <h1>Nuevo Indirecto</h1>
            <p>Registra un porcentaje indirecto aplicable al análisis de precio unitario.</p>
        </div>

        @if ($errors->any())
            <div class="alert-danger">
                <strong>Corrige los siguientes errores:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('indirectos.store') }}" method="POST">
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
                    placeholder="Ejemplo: IND-001"
                >
                @error('clave')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="concepto">Concepto <span class="required-mark">*</span></label>
                <input
                    type="text"
                    id="concepto"
                    name="concepto"
                    class="form-control @error('concepto') is-invalid @enderror"
                    value="{{ old('concepto') }}"
                    required
                    maxlength="200"
                    placeholder="Ejemplo: Gastos administrativos de obra"
                >
                @error('concepto')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="porcentaje">Porcentaje (%) <span class="required-mark">*</span></label>
                <input
                    type="number"
                    step="0.0001"
                    min="0"
                    id="porcentaje"
                    name="porcentaje"
                    class="form-control @error('porcentaje') is-invalid @enderror"
                    value="{{ old('porcentaje', 0) }}"
                    required
                    placeholder="Ejemplo: 12"
                >
                @error('porcentaje')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea
                    id="descripcion"
                    name="descripcion"
                    class="form-control @error('descripcion') is-invalid @enderror"
                    rows="4"
                    maxlength="255"
                    placeholder="Describe brevemente a qué corresponde este indirecto."
                >{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="btn-actions">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-save me-2"></i>Guardar Indirecto
                </button>

                <a href="{{ route('indirectos.index') }}" class="btn-cancel">
                    <i class="bi bi-x-circle me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection