document.addEventListener('DOMContentLoaded', function() {
    /**
     * jQuery(function($) {}); добавлено для избежания конфликта
     */
    jQuery(function ($) {
        console.log('tyty');
        function sendMail(formData, action, formContact) {
            console.log('email');
            console.log(formData);
            $.ajax({
                url: action,
                type: 'POST',
                data: formData,
                error: function (response) {

                    function sendMail(formData, action, formContact) {
                        $.ajax({
                            url: action,
                            type: 'POST',
                            data: formData,
                            error: function (response) {
                                console.log(response);
                                formContact.html('<p class="form-error">Произошла ошибка. Наши программисты уже решают проблему.</p>');
                            },
                            success: function (response) {
                                console.log(response);
                                formContact.html('<p class="form-success text-center">Ваша заявка успешно отправлена</p>');
                            }
                        });	//end ajax
                    }

                    $('.ajax-send-form').each(function (i, item) {
                        var formContact = $(item),
                            action = formContact.attr('action');

                        formContact.on('submit', function (event) {
                            event.preventDefault();

                            var formData = {};

                            formContact.find('.ajax-input').each(function (id, item) {
                                var name = $(item).attr('name'),
                                    input = $(item).val();
                                formData[name] = input;
                            });

                            sendMail(formData, action, formContact);
                        }); // end submit
                    });


                    console.log(response);
                    formContact.html('<p class="form-error">Произошла ошибка. Наши программисты уже решают проблему.</p>');
                },
                success: function (response) {
                    console.log(response);
                    formContact.html('<p class="form-success text-center">Ваша заявка успешно отправлена</p>');
                }
            });	//end ajax
        }

        let form = document.querySelector('.ajax-send-form');
        //console.log(form);
        if(form != null){
            $(form).each(function (i, item) {
                let formContact = $(item),
                    action = formContact.attr('action');

                formContact.on('submit', function (event) {
                    event.preventDefault();

                    let formData = {
                        fio: $('input[name="fio"]').val(),
                        email: $('input[name="email"]').val(),
                        phone: $('input[name="phone"]').val(),
                        subject: $('input[name="subject"]').val(),
                        to_email: $('input[name="to_email"]').val(),
                        message: $('textarea[name="message"]').val()
                    };

                    sendMail(formData, action, formContact);
                }); // end submit
            });
        }
    });
});