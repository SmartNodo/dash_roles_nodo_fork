<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consultar crédito</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/styles.css">

</head>
<body>
    <div class="container">
        <div class="row align-items-center vh-100">
          <div class="col-6">
            <form action="" id="form-query">
                <!-- SECTION 1 -->
                <h4></h4>
                <section>
                    <div class="inner">
                        <h1 class="text-center py-4">Consultas de crédito</h1>
                        <hr>
                        {{-- <a href="#" class="avartar">
                            <img src="images/avartar.png" alt="">
                        </a> --}}
                        <div class="form-row form-group">
                            <div class="form-holder">
                                <input type="text" class="form-control" name="credit_number" id="credit-number" placeholder="Número de crédito">
                            </div>
                            <div class="form-holder">
                                {{-- <input type="text" class="form-control" placeholder="Last Name"> --}}
                                <select name="id_state" class="form-control" id="id-state">
                                    <option value="">Seleccionar estado</option>
                                    @foreach ($states as $state)
                                        <option value="{{$state->idState}}">{{$state->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row form-group justify-content-center">
                                <button type="submit" class="text-center m-1" id="btn-send-query" >Consultar</button> 
                                <button type="button" class="text-center m-1"  onclick="window.location.href='\creditos';">  Lista </button>                                                                
                            </div>
                        </div>
                        <div class="col" id="error">
                            <div class="alert alert-danger" role="alert">
                            </div>
                        </div>
                    </div>
                </section>
            </form>
          </div>

          <div class="col-6 container-result">
            <section>
                <div class="inner">
                    <h2 class="text-center py-4">Datos del Acreditado</h2>
                    <div id="result">
                    </div>
                </div>
            </section>
          </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>

    <script>
        let creditNumber, idState;


        document.getElementById('btn-send-query').addEventListener('click', (event) => {
            event.preventDefault()

            idState = document.forms["form-query"].id_state.value;
            creditNumber = document.forms["form-query"].credit_number.value;

            // 1.- Guardar consulta
            const data = {
                creditNumber: creditNumber,
                idState: idState
            }

            saveQuery(data)

            // 2.- Ejecutar Scraper
            // 3.- Actualizar consulta

            // const endpoint = "save-query"


        })


        const saveQuery = async (data) => {
            const _token = "{{Session::get('bearer_token')}}"

            // Contenedores:
            let errorContainer = document.getElementById('error')
            errorContainer.innerHTML = ""

            document.querySelector('.container-result').style.display = "none";

            let resultContainer = document.getElementById('result')
            resultContainer.innerHTML = ""


            // 1- Petición api para consultar crédito
            const response = await fetch('api/check-credit-number', {
                method: 'POST',
                headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${_token}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify(data),
            })

            const result = await response.json()

            // 2. Si el crédito tiene error
            if(result.error) {
                // Vaciar el contenedor
                errorContainer.innerHTML = `<div class="alert alert-danger" role="alert"> ${result.error} </div>`
                errorContainer.style.display = "block"
                return 0
            } else {
                errorContainer.style.display = "none"
            }

            // 3. Si el crédito no tiene error: pinta el resultado
            const r = result.result

            console.log(typeof result.result['isBroxel'])
            console.log(result.result['isBroxel'] === 'false')

            const balance =  result.result['costoEcoTec'];
            let dollarUSLocale = Intl.NumberFormat('en-US');

            // Mostrar contenedor
            document.querySelector('.container-result').style.display = "block";
            // Vaciar el contenedor
            resultContainer.innerHTML = `
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="row">Nombre Acreditado</th>
                        <td> ${result.result['nombreDH']} </td>
                    </tr>
                    <tr>
                        <th>NSS</th>
                        <td>${result.result['nss']}</td>
                    </tr>
                    <tr>
                        <th>Dirección Vivienda</th>
                        <td>${result.result['domicilio']}</td>
                    </tr>
                    <tr>
                        <th>Código postal</th>
                        <td> ${result.result['codigoPostal']}</td>
                    </tr>
                    <tr>
                        <th>Balance</th>
                        <td>$${ dollarUSLocale.format(balance) }</td>
                    </tr>
                    ${ /* <tr>
                        <th>Ahorro Minimo Requerido</th>
                        <td>$${result.result['ahorroEcoSalario']}</td>
                    </tr> */'' }
                    <tr>
                        <th></th>
                        <td>
                            <h6>
                                <span class="badge bg-info">
                                    ${ result.result['isBroxel'] == 'false'? 'Casia': 'Broxel' }
                                </span>
                            </h6>
                        </td>
                    </tr>
                    </tbody>
            </table>`
        }

    </script>

</body>
</html>