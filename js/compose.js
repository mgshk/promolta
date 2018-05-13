function validate() {
    var to = document.getElementById('to').value;
    var subject = document.getElementById('subject').value;
    var body = document.getElementById('body').value;

    try {

        if (to.trim() === '')
            throw "Enter reciepient";

        if (subject.trim() === '')
            throw "Enter subject";

        if (body.trim() === '')
            throw "Enter body";

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

function autosave() {
    if (!validate())
        return;

    var to = document.getElementById('to').value;
    var subject = document.getElementById('subject').value;
    var body = document.getElementById('body').value;
    var parent_id = document.getElementById('parent_id').value;

    $.ajax({
        cache: false,
        url: 'services/api.php?f=autosave',
        method: 'POST',
        data: {
            to: to, 
            subject: subject,
            body: body,
            parent_id: parent_id
        },
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

            document.getElementById('errorTxt').innerHTML = 'Saved successfuly and moved to drafts';
            document.getElementById('errorTxt').style.display = 'block';

            setTimeout(function() {
                document.getElementById('errorTxt').style.display = 'none';
                window.location.href = 'landing.php';
            }, 3000);
        }
    });

    return false;
}

function sendMail(type) {

	if (!validate())
        return;

    var to = document.getElementById('to').value;
    var subject = document.getElementById('subject').value;
    var body = document.getElementById('body').value;
    var parent_id = document.getElementById('parent_id').value;

    $.ajax({
        cache: false,
        url: 'services/api.php?f=sendMail',
        method: 'POST',
        data: {
        	to: to, 
        	subject: subject,
        	body: body,
        	parent_id: parent_id,
            type: type
        },
        success: function(resp) {
            var result = JSON.parse(resp);

            if(result.error === 1) {
                document.getElementById('errorTxt').innerHTML = result.msg;
                document.getElementById('errorTxt').style.display = 'block';
                return;
            }

            window.location.href = 'landing.php';
        }
    });

    return false;
}