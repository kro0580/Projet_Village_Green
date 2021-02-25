// Messages Sweet Alert pour suppression profil et produit

function deleteForm() {
    event.preventDefault(); // Empêche la soumission du formulaire
    var form = document.forms["delete"];
    swal({
        title: "Etes-vous certain ?",
        text: "Une fois supprimé, vous ne pourrez plus voir votre profil !",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                swal("Votre profil a été supprimé avec succès !", {
                    button: false,
                    icon: "success"
                });
                form.submit();
            } else {
                swal("Votre profil n'a pas été supprimé !", {
                    button: "Ouf !",
                    icon: "error"
                });
            }
        });
}

function deleteForm2() {
    event.preventDefault(); // Empêche la soumission du formulaire
    var form = document.forms["delete2"];
    swal({
        title: "Etes-vous certain ?",
        text: "Une fois supprimé, vous ne pourrez plus voir ce produit !",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                swal("Votre produit a été supprimé avec succès !", {
                    button: false,
                    icon: "success"
                });
                form.submit();
            } else {
                swal("Votre produit n'a pas été supprimé !", {
                    button: "Ouf !",
                    icon: "error"
                });
            }
        });
}