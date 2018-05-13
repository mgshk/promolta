$(document).on('click', '.menu', function() {

    if ($(this).hasClass('active'))
        return;

    $('.menu').removeClass('active');
    $(this).addClass('active');

    var item = $(this).data('item');
    var type = $(this).attr('id');

    getMails(item, type);
    
    return false;
});

$(document).on('click', '.view', function() {
    var id = $(this).data('id');
    var status = $(this).data('status');
    var type = $(this).data('type');

    if (status === 'unread') {
        $.ajax({
            cache: false,
            url: 'services/api.php?f=readMail',
            method: 'POST',
            data: {id: id},
            success: function(resp) {
                var result = JSON.parse(resp);

                if (result.error === 0) {
                    window.location.href = 'view.php?id='+ id+'&type='+type;
                } else {
                    document.getElementById('errorTxt').innerHTML = result.msg;
                    document.getElementById('errorTxt').style.display = 'block';
                }
            }
        });
    } else {
        if (type === "draft")
            window.location.href = 'compose.php?id='+ id +'&type='+type;
        else
            window.location.href = 'view.php?id='+ id+'&type='+type;
    }

    return false;
});

function getMails(item, type) {
    $.ajax({
        cache: false,
        url: 'services/api.php?f=' + item,
        method: 'POST',
        data: {},
        success: function(resp) {
            var result = JSON.parse(resp);

            document.getElementById('msg').style.display = 'none';
            $('.lists').empty();

            if (result.error === 1) {
                document.getElementById('msg').innerHTML = '<p>There are no conversations</p>';
                document.getElementById('msg').style.display = 'block';
            } else {
                var mails = result.mails;

                mails.forEach(function(mail, index) {
                    if (mail.status === '0' && type != 'sent')
                        $('.lists').append('<li class="unread" id="mail_'+mail.mail_id+'"><a class="view" data-status="unread" data-type="'+type+'" data-id="'+mail.mail_id+'">' +mail.subject+ '</a> <span class="dateTxt">' +mail.date_sent+ '</span> <a href="javascript:void(0);" onclick="deleteMail('+mail.mail_id+', \''+type+'\');">Delete</a></li>');
                    else
                        $('.lists').append('<li class="read" id="mail_'+mail.mail_id+'"><a class="view" data-status="read" data-type="'+type+'" data-id="'+mail.mail_id+'">' +mail.subject+ '</a>  <span class="dateTxt">' +mail.date_sent+ '</span> <a href="javascript:void(0);" onclick="deleteMail('+mail.mail_id+', \''+type+'\');">Delete</a></li>');
                });
            }
        }
    });

    return false;
}

function deleteMail(id, type) {
    $.ajax({
        cache: false,
        url: 'services/api.php?f=deleteMail',
        method: 'POST',
        data: {
            id: id,
            type: type
        },
        success: function(resp) {
            var result = JSON.parse(resp);

            if (result.error === 1) {
                document.getElementById('content').innerHTML = '<p>Something went wrong</p>';
            } else {
                $('#mail_'+id).remove();
            }
        }
    });

    return false;
}

getMails('getInbox', 'inbox');
