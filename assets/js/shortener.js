$(document).ready(function () {
    let domain = location.protocol + '//' + location.host,
        currentURL = location.href,
        href = currentURL.replace(domain, ''),
        alertDanger = $('.alert-danger'),
        alertPrimary = $('.alert-primary'),
        text = '',
        linkInput = $('input[name=link]');


    $('.shorten').click(function() {
        shorten();
    });

    linkInput.keyup(function(e) {
        if (e.keyCode === 13) {
            shorten();
        }
    });

    function shorten() {
        let button = $(this),
            xcsrf = $('input[name="csrf_test_name"]');

        if(! button.hasClass('disabled')) {
            button.addClass('disabled');

            $.ajax({
                url: domain + "/shortener/get",
                type: "POST",
                data: {
                    csrf_test_name: xcsrf.val(),
                    link: linkInput.val()
                },
                success: function(data){
                    // Перезаписываем csrf на новый
                    xcsrf.val(data['csrf']);

                    if (data['success']) {
                        // Выводим короткую ссылку
                        alertDanger.addClass('hide').html('');
                        alertPrimary.html("<a href='" + data['link'] + "' target='_blank'>" + data['link'] + "</a>").removeClass('hide');
                    } else {
                        // Произошла ошибка
                        linkInput.addClass('is-invalid');
                        if (data['message']) {
                            alertPrimary.addClass('hide').html('');
                            alertDanger.html(data['message']).removeClass('hide');
                        }
                    }

                    button.removeClass('disabled');
                },
                error: function(err){
                    alertPrimary.addClass('hide').html('');
                    alertDanger.html('Что-то пошло не так..').removeClass('hide');
                    button.removeClass('disabled');
                }
            });
        }
    }

    linkInput.bind('change keyup', function() {
        if (text !== $(this).val()) {
            text = $(this).val();

            linkInput.removeClass('is-invalid');
            alertDanger.addClass('hide');
            alertDanger.html('');
        }
    });
});