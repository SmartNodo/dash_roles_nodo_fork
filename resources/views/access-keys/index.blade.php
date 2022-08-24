@extends('layouts.app')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Llaves de acceso</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                        {{-- <a class="btn btn-primary" href="/consulta"> Nuevo</a> --}}
                            <table class="table table-triped mt-2">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Estado</th>
                                        <th scope="col" class="text-center">User</th>
                                        <th scope="col" class="text-center">Password</th>
                                        <th scope="col" class="text-center">
                                            Última actualización <br>
                                            Día/Mes/Año
                                        </th>
                                        <th scope="col" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody"></tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modals')
<div class="modal fade" tabindex="-1" role="dialog" id="update-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" class="form-control" id="id">
                    <div class="form-group">
                        <label for="id-state">Estado</label>
                        <select name="idState_state" class="form-control" id="id-state" required>
                            <option value="">-- Selecciona Estado --</option>
                            @foreach ($states as $state)
                                <option value="{{$state->idState}}">{{$state->name}}</option>
                            @endforeach
                        </select>
                        {{-- <small id="emailHelp" class="form-text text-muted"></small> --}}
                    </div>
                    <div class="form-group">
                        <label for="user">Usuario</label>
                        <input type="text" name="user" id="user" class="form-control" id="user" required>
                    </div>
                    <div class="form-group">
                        <label for="pass">Password</label>
                        <input type="text" name="pass" class="form-control" id="pass" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
{{-- Script variable global _token --}}
<script>
    // Global variable
    var _token = `{{ Session::get('bearer_token') }}`;
    console.log(_token)
</script>

{{-- Script encargado de realizar las acciones del CRUD --}}
<script src="{{ asset('js/access_keys/index.js') }}"></script>

{{-- Script que agrega eventos para realizar las acciones del CRUD --}}
<script>

    document.addEventListener('DOMContentLoaded', ApiListAccessKeys(), false);

    document.addEventListener("submit", sendUpdateForm, false);

</script>
@endsection

