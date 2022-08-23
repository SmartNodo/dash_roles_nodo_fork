@extends('layouts.app')

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
                                        <th scope="col">Estado</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">State</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Password</th>
                                        <th scope="col">Acciones</th>
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
<div class="modal fade" tabindex="-1" role="dialog" id="updateModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

<script>

    let _token = `{{ Session::get('bearer_token') }}`;
    let endpoint = document.location.origin+'/api/access-keys';

    document.addEventListener('DOMContentLoaded', function () {
        ApiListAccessKeys(_token, endpoint);
    }, false);

    const ApiListAccessKeys = async (_token, endpoint) => {
    // bloquea el código hasta que obtenga una respuesta:
    const response = await fetch(endpoint, {
        method:'GET',
        headers:{
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer '+_token,
        },
    })

    const result = await response.json()
    console.log(result)


    let tbody = document.getElementById('tbody')
    tbody.innerHTML = ""

    let tr = ''

    result.data.forEach(accessKey => {

        if(accessKey.status == 0) {
            tr += `<tr class="bg-danger text-white">`
        } else {
            tr += `<tr>`
        }

        tr += `
                <td>${ accessKey.state.name }</td>
                <td>${ accessKey.status }</td>
                <td>${ accessKey.user }</td>
                <td>${ accessKey.pass }</td>
                <td>${ accessKey.updated_at }</td>
                <td>
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#updateModal">
                        Actualizar
                    </button>
                </td>
            </tr>`
        });

    tbody.innerHTML = tr

}

    // async function lista(){      
    //     const res = await fetch(endpoint, {
    //         method:'GET',
    //         headers:{
    //             'Accept': 'application/json',
    //             'Content-Type': 'application/json',
    //             'Authorization': 'Bearer '+_token,
    //         },   
    //     });      
    //     const data = await res.json()                       
    //     $("#tbody").empty()
    //     let tr = '';
    //     console.log(data)
    //     data.data.forEach(data2 => {
    //         console.log(data2);
    //         tr += `<tr>
    //                     <td>`+data2.id+`</td>
    //                     <td>`+data2.user+`</td>
    //                     <td>`+data2.state+`</td>
    //                     <td>`+data2.nss+`</td>
    //                     <td>`+data2.creditNumber+`</td>                        
    //                     <td>`+data2.status+`</td>
    //                     <td>`+data2.balance+`</td>
    //                     <td>`+data2.email+`</td>
    //                     <td>`+data2.updated_at+`</td>`
    //     });
    //     $("#tbody").append(tr);     
    // }  
</script>

