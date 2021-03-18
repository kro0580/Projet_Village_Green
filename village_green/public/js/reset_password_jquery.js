// Fonction pour la réinitialisation du mot de passe

function verif4() 
{     
// Récupère la valeur saisie dans le champ input      
     
     var mdp = $("#inputPassword").val();
     var mdp_v = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$/;
     
// On teste si la valeur est bonne

     // PASSWORD

     if (mdp === "") 
     {            
        var html = '<div class="alert alert-danger" role="alert">Veuillez saisir votre nouveau mot de passe !</div>';
        $("#alertmdp").append(html); 
        event.preventDefault();
        return false;
     }
     else if (mdp_v.test(mdp) == false)
     {
        var html = '<div class="alert alert-warning" role="alert">Format non valide !</div>';
        $("#alertmdp").append(html);
        event.preventDefault();
        return false;
     }
    
    // Si aucun test n'a renvoyé faux, c'est qu'il n'y a pas d'erreur, le script arrive ici, le formulaire est envoyé via submit()
    document.forms[0].submit();
}

     $("#btn_reset").click(function(event) 
{    
    // Appel de la fonction verif4()
    verif4();             
});