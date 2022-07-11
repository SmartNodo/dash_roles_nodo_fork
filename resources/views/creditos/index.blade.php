@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Creditos</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">                            

                        <a class="btn btn-warning" href="/consulta"> Nuevo</a>
                            <table class="table table-triped mt-2">
                                <thead>
                                    <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">User</th>
                                    <th scope="col">State</th>
                                    <th scope="col">Nss</th>
                                    <th scope="col">creditNumber</th>                                    
                                    <th scope="col">Status</th>
                                    <th scope="col">Balance</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Fecha Actualizacion</th>                                        
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

<script>
    let _token = `{{ Session::get('bearer_token') }}`;
    document.addEventListener('DOMContentLoaded', function () {
        lista();
    }, false);

    async function lista(){      
        const res = await fetch('http://127.0.0.1:8000/api/list-credits', {
            method:'GET',
            headers:{
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': 'Bearer '+_token,
            },   
        });      
        const data = await res.json()                       
        $("#tbody").empty()
        let tr = '';
        console.log(data)
        data.data.forEach(data2 => {
            console.log(data2);
            tr += `<tr>
                        <td>`+data2.id+`</td>
                        <td>`+data2.user+`</td>
                        <td>`+data2.state+`</td>
                        <td>`+data2.nss+`</td>
                        <td>`+data2.creditNumber+`</td>                        
                        <td>`+data2.status+`</td>
                        <td>`+data2.balance+`</td>
                        <td>`+data2.email+`</td>
                        <td>`+data2.updated_at+`</td>`
        });
        $("#tbody").append(tr);     
    }  
</script>

