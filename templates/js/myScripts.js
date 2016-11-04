function welcomeMessage(user){
    $("#welcomeAlert").addClass("alert-success");
    $("#welcomeAlertText").html("Bonjour "+user+" Vous êtes bien Authentifié" );
    $("#welcomeAlert").css("visibility","visible");
}

function errorMessage(json, statut, erreur){
    $("#welcomeAlert").addClass("alert-danger");
    $("#welcomeAlertText").html("Statut: "+statut+"<br>"+
                                "Erreur: "+erreur+"<br>"+
                                "Reponse: "+json);
    $("#welcomeAlert").css("visibility","visible");

}

function showUsersList(allUsers){
    $.each(allUsers, function(key,value){
        $("#users_list").append(
            "<tr id='"+key+"'>"+
                "<td>"+key+"</td>"+
                "<td>"+value.email+"</td>"+
                "<td>"+value.is_superuser+"</td>"+
                "<td>"+value.creation_time+"</td>"+
                "<td>"+value.last_update_time+"</td>"+
                "<td><button class='btn btn-xs btn-danger deleteUserButton' id='"+value.id+"' >supprimer</button></td>"+
            "</tr>");
    });

    $(".deleteUserButton").click(function() {
        var id = this.id;
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            confirmButtonClass: 'btn btn-lg btn-success',
            cancelButtonClass: 'btn btn-lg btn-danger',
            buttonsStyling: false
        }).then(function() {
            deleteUser(id),
                $("#user_"+id).remove()
            swal(
                'Deleted!',
                'The user has been deleted.',
                'success'
            )
        }, function(dismiss) {
            // dismiss can be 'cancel', 'overlay',
            // 'close', and 'timer'
            if (dismiss === 'cancel') {
                swal(
                    'Cancelled'
                )
            }
        })
    });
}



function deleteUser( id){
    console.log(id);
    $.ajax({
        url : 'v1/users/'+id,
        type : 'DELETE',
        method : "DELETE",
        dataType : 'json',
        beforeSend: function (request)
        {
            request.setRequestHeader("Authorization", token);
        },
        success : function(json, statut, request){
            welcomeMessage(json.connected_user.email);
            showUsersList(json.all_users);
        },
        error : function(json, statut, erreur){
            errorMessage(json.connection, statut, erreur);
        },
        complete : function(resultat, statut){

        }
    })
}

$("#add_user_btn").click(function() {
    swal.setDefaults({
        input: 'text',
        confirmButtonText: 'Next &rarr;',
        showCancelButton: true,
        animation: false,
        progressSteps: ['1', '2', '3']
    })

    var steps = [
        {
            title: 'Email',
            text: 'Veillez entrer l\'email de l\'utilisateur'
        },
        {
            title:'Mot de passe',
            input:'password'
        },
        {
            title: 'SuperUser ?',
            text: '1 si oui 0 sinon',
            input:'number'
        }
    ]

    swal.queue(steps).then(function(result) {
        console.log(result);
        swal.resetDefaults()
        swal({
            title: 'All done!',
            html:
            'Your answers: <pre>' +
            JSON.stringify(result) +
            '</pre>',
            confirmButtonText: 'Lovely!',
            showCancelButton: false
        })
    }, function() {
        swal.resetDefaults()
    })  
});