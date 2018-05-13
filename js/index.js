function validate() {
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    try {

        if (email.trim() === '')
            throw "Enter email";

        if (password.trim() === '')
            throw "Enter password";

    } catch (e) {
        document.getElementById('errorTxt').innerHTML = e;
        document.getElementById('errorTxt').style.display = 'block';

        setTimeout(function() {
            document.getElementById('errorTxt').style.display = 'none';
        }, 3000);

        return false;
    }

    return true;
}

function signIn() {
    if (!validate())
        return;

    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;

    $.ajax({
        cache: false,
        url: 'services/api.php?f=userLogin',
        method: 'POST',
        data: {email: email, password: password},
        success: function(resp) {
            var result = JSON.parse(resp);

            if(result.error === 1) {
                document.getElementById('errorTxt').innerHTML = result.msg;
                document.getElementById('errorTxt').style.display = 'block';

                setTimeout(function() {
                    document.getElementById('errorTxt').style.display = 'none';
                }, 3000);
                return;
            }

            window.location.href = 'landing.php';
        }
    });

    return false;
}