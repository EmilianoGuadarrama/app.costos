@extends('layout')

@section('title','Crear Generador')

@section('content')
    <style>
        .form-card{
            max-width:900px;
            margin:0 auto;
            background:#fff;
            border:1px solid rgba(0,0,0,.18);
            padding:32px;
        }
        .form-wrapper{
            max-width:620px;
            margin:0 auto;
        }
    </style>

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h2 class="fw-bold mb-1">Nuevo Generador</h2>
            <p class="text-secondary mb-0">Captura la información del generador.</p>
        </div>

        <a href="{{ route('generadores.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <form action="{{ route('generadores.store') }}" method="POST" class="form-wrapper" id="formGenerador">
            @csrf

            <div class="row mb-3 align-items-center">
                <label class="col-md-4 fw-semibold">Concepto</label>
                <div class="col-md-8">
                    <input name="concepto" type="text" class="form-control" value="{{ old('concepto') }}" required>
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-md-4 fw-semibold">Unidad</label>
                <div class="col-md-8">
                    <input name="unidad" type="text" class="form-control" value="{{ old('unidad') }}" required>
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-md-4 fw-semibold">Localización</label>
                <div class="col-md-8">
                    <input name="localizacion" type="text" class="form-control" value="{{ old('localizacion') }}">
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-md-4 fw-semibold">Ejes</label>
                <div class="col-md-8">
                    <input name="ejes" type="text" class="form-control" value="{{ old('ejes') }}">
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-md-4 fw-semibold">No. de Piezas</label>
                <div class="col-md-8">
                    <input name="no_piezas" id="no_piezas" type="number" class="form-control" min="0" step="1"
                           value="{{ old('no_piezas', 0) }}">
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-md-4 fw-semibold">Ancho</label>
                <div class="col-md-8">
                    <input name="ancho" id="ancho" type="number" class="form-control" min="0" step="0.01"
                           value="{{ old('ancho', 0) }}">
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-md-4 fw-semibold">Largo</label>
                <div class="col-md-8">
                    <input name="largo" id="largo" type="number" class="form-control" min="0" step="0.01"
                           value="{{ old('largo', 0) }}">
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-md-4 fw-semibold">Alto</label>
                <div class="col-md-8">
                    <input name="alto" id="alto" type="number" class="form-control" min="0" step="0.01"
                           value="{{ old('alto', 0) }}">
                </div>
            </div>

            <div class="row mb-4 align-items-center">
                <label class="col-md-4 fw-semibold">Resultado</label>
                <div class="col-md-8">
                    <input name="resultado" id="resultado" type="number" class="form-control" min="0" step="0.0001"
                           value="{{ old('resultado', 0) }}">
                    <small class="text-secondary">Tip: Se puede calcular automático: piezas × ancho × largo × alto.</small>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary px-4" id="btnCalcular">
                    <i class="bi bi-calculator me-2"></i> Calcular
                </button>

                <button type="submit" class="btn btn-secondary px-4">
                    <i class="bi bi-plus-circle me-2"></i> Guardar Generador
                </button>
            </div>
        </form>
    </div>

    <script>
        function num(id){
            const v = document.getElementById(id).value;
            const n = parseFloat(v);
            return isNaN(n) ? 0 : n;
        }

        function calcularResultado(){
            const piezas = num('no_piezas');
            const ancho  = num('ancho');
            const largo  = num('largo');
            const alto   = num('alto');
            const res = piezas * ancho * largo * alto;
            document.getElementById('resultado').value = (isNaN(res) ? 0 : res.toFixed(4));
        }

        document.getElementById('btnCalcular').addEventListener('click', calcularResultado);

        // si quieres auto-calcular mientras escribes, descomenta:
        // ['no_piezas','ancho','largo','alto'].forEach(id => {
        //     document.getElementById(id).addEventListener('input', calcularResultado);
        // });
    </script>
@endsection
