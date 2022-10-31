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
                        <div class="card-footer" id="cardFooter">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection



@section('scripts')
<script>
let _token = `{{ Session::get('bearer_token') }}`;
let url = document.location.origin+'/api/list-credits';


document.addEventListener('DOMContentLoaded', function () {
    let pageNumber = 1;
    lista(pageNumber);

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault()
        pageNumber = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(pageNumber)
        lista(pageNumber)
    })

}, false);


async function lista(pageNumber) {
    const res = await fetch(url+`/?page=${pageNumber}`, {
        method:'GET',
        headers:{
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer '+_token,
        },
    });

    const data = await res.json()

    $("#tbody").empty()
    $("#cardFooter").empty();

    let tr = '';
    data.credits.data.forEach(credit => {
        console.log(credit);
        tr += `<tr>
                    <td>`+credit.id+`</td>
                    <td>`+credit.user+`</td>
                    <td>`+credit.state+`</td>
                    <td>`+credit.nss+`</td>
                    <td>`+credit.creditNumber+`</td>
                    <td>`+credit.status+`</td>
                    <td>`+credit.balance+`</td>
                    <td>`+credit.email+`</td>
                    <td>`+credit.updated_at+`</td>`
    });

    $("#tbody").append(tr);
    $("#cardFooter").append(data.pagination);
}

</script>
@endsection

