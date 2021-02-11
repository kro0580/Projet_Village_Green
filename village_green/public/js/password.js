// Barre de force du mot de passe

var pass = document.getElementById("password")
    pass.addEventListener('keyup', function()
    {
        checkPassword(pass.value)
    })

    function checkPassword(password)
    {
        var strengthBar = document.getElementById("strength")
        var strength = 0;
            if (password.match(/[a-zA-Z0-9][a-zA-Z0-9]+/)) {
                strength += 1
            }
            if (password.match(/[~?]+/)) {
                strength += 1
            }
            if (password.match(/[!@£$%^&*()]+/)) {
                strength += 1
            }
            if (password.length >= 8) {
                strength += 1
            }
    
        switch (strength) 
        {
            case 0 :
                strengthBar.style.width = "0%";
                break
            case 1:
                strengthBar.style.width = "25%";
                strengthBar.setAttribute ("class","progress-bar progress-bar-striped progress-bar-animated bg-danger")
                break
            case 2:
                strengthBar.style.width = "50%";
                strengthBar.setAttribute ("class","progress-bar progress-bar-striped progress-bar-animated bg-warning")
                break
            case 3:
                strengthBar.style.width = "75%";
                strengthBar.setAttribute ("class","progress-bar progress-bar-striped progress-bar-animated bg-success")
                break
            case 4:
                strengthBar.style.width = "100%";
                strengthBar.setAttribute ("class","progress-bar progress-bar-striped progress-bar-animated bg-success")
                break
        }

    }

// Pour dévoiler le mot de passe que l'on tape

$(document).ready(function () {
    $("#eye").click(function () {
        if ($("#password").attr("type") === "password") {
            $("#password").attr("type", "text");
        } else {
            $("#password").attr("type", "password");
        }
    });
});