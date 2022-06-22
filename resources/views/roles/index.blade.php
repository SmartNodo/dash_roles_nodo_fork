@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Roles</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            @can('crear-rol')
                                <a class="btn btn-warning" href="{{ route('roles.create') }}"> Nuevo</a>
                            @endcan

                            <table class="table table-triped mt-2">
                                <thead>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                                @can('editar-rol')
                                                    <a class="btn btn-info"
                                                        href="{{ route('roles.edit', $role->id) }}">Editar</a>
                                                @endcan

                                                @can('borrar-rol')
                                                    {{-- HTML - Collective --}}
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                                                    {!! Form::submit('Borrar', ['class' => 'btn btn-danger']) !!}
                                                    {!! Form::close() !!}
                                                @endcan

                                            </td>
                                            <td>


                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination justify-content-end">
                                {!! $roles->links() !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
