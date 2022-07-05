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
                                <button type="submit" class="text-center" id="btn-send-query" >Consultar</button>
                            {{--
                                <div class="actions clearfix">
                                    <ul role="menu" aria-label="Pagination">
                                        <li aria-hidden="false" aria-disabled="false">
                                            <a href="#next" role="menuitem">Next</a>
                                        </li>
                                    </ul>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </section>
            </form>
          </div>

          <div class="col-6 container-result">
            <section>
                <div class="inner">
                    <h1 class="text-center py-4">Resultado</h1>
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

            const response = await fetch('guardar-consulta', {
                method: 'POST',
                headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data),
            })

            const result = await response.json()
            document.getElementById('result')

            document.querySelector('.container-result').style.display = "block";

            console.log('result')
            console.log(result.result)
            console.log(result.result[0]['ahorroEcoSalario'])

            let resultContainer = document.getElementById('result');
            // Vaciar el contenedor
            resultContainer.innerHTML = "";

            // <p> Estado ${result.result[0]['estado']} </p>
            // <p> ${result.result[0]['usuarioSimulado']} </p>
            // <p> ${result.result[0]['isBroxel']} </p>
            // <p> ${result.result[0]['marcaVista']} </p>
            // <p> ${result.result[0]['estadoMunicipio']} </p>

            resultContainer.innerHTML = `
                <p> Nombre Acreditado: ${result.result[0]['nombreDH']} </p>
                <p> NSS: ${result.result[0]['nss']} </p>
                <p> Dirección Vivienda: ${result.result[0]['domicilio']} </p>
                <p> Código postal: ${result.result[0]['codigoPostal']} </p>
                <p> Monto de la Constancia para la compra de ecotecnologías: $${result.result[0]['costoEcoTec']} </p>
                <p> Ahorro Minimo Requerido: $${result.result[0]['ahorroEcoSalario']} </p>
                <p> ${result.result[0]['numAvaluo']} </p>
            `;

        }

    </script>

</body>
</html>