const ApiListAccessKeys = async () => {
    const endpoint = document.location.origin+'/api/access-keys';
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
            tr += `<tr class="bg-danger text-white text-center">`
        } else {
            tr += `<tr class="text-center">`
        }

        let itemStr = encodeURIComponent(JSON.stringify(accessKey))
        const d = new Date(accessKey.updated_at).toLocaleDateString("es-MX")

        tr += `
            <td>${ accessKey.state.name }</td>
            <td>${ accessKey.user }</td>
            <td>${ accessKey.pass }</td>
            <td>${ d }</td>
            <td>
                <button
                    type="button"
                    class="btn btn-warning"
                    data-toggle="modal"
                    data-target="#update-modal"
                    data-item="${itemStr}"
                    onclick="loadDataToupdateModal(event, this)"
                >
                    Actualizar
                </button>
            </td>
        </tr>`
    });

    tbody.innerHTML = tr
}

const ApiUpdateAccessKey = async () => {

    const formData = new FormData( document.querySelector('#update-modal form') )

    const data = {
        id: formData.get('id'),
        idState_state: formData.get('idState_state'),
        user: formData.get('user'),
        pass: formData.get('pass')
    }

    const endpoint = `${document.location.origin}/api/access-keys/${formData.get('id')}/update`

    // Make request:
    const response = await fetch(endpoint, {
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

    location.reload();
}

function loadDataToupdateModal(e, el) {
    e = e || window.event;

    let accessKey = JSON.parse(decodeURIComponent( el.dataset.item ))

    document.querySelector('#update-modal form select[id=id-state]').value = accessKey.idState_state
    document.querySelector('#update-modal form input[id=user]').value = accessKey.user
    document.querySelector('#update-modal form input[id=pass]').value = accessKey.pass
    document.querySelector('#update-modal form input[id=id]').value = accessKey.id

}


function sendUpdateForm(e) {
    e.preventDefault();

    ApiUpdateAccessKey()
}


// $("#update-modal-form").submit(function(e) {

//     let id = $(this).find('input[id=id]').val()
//     let newPass = $(this).find('input[id=pass]').val()
//     let newPartner = $(this).find('select[id=partner]').val()
//     console.log(`newPartner ${newPartner}`)

// 	let arrayRequest = [];
// 	arrayRequest.push({id: id });
// 	arrayRequest.push({pass: newPass});
// 	arrayRequest.push({idPartner: newPartner});


//     samf.setURL('sam_laravel/public/access-keys/update');

// 	samf.asyncPost(arrayRequest).then(data => {

// 		if (data.code == 1) {
// 			waitingDialog.hide();
//             // Cierra modal:
//             console.log('cierra el modal: ')
//             $('#update-modal').modal('hide')
//             // Redibuja la tabla
//             waitingDialog.show();
//             drawKeys();
//             waitingDialog.hide();
// 		}else{
// 			swal("ERROR", data.msg +" Details:" + data.msgAdditional.image[0], "warning");
// 			waitingDialog.hide();
// 		}
// 	}).catch(error => {
// 			waitingDialog.hide();
//             return 'Fetch error:' + error.message;
// 	});
// });