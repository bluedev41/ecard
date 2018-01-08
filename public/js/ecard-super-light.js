jQuery(document)
    .ready(function ($) {
        
        // set up slider

        $('#my-esl-slider').sliderPro({
            width: 960,
            height: 500,
            fade: true,
            arrows: true,
            buttons: false,
            fullScreen: true,
            shuffle: true,
            smallSize: 500,
            mediumSize: 1000,
            largeSize: 3000,
            thumbnailArrows: true,
            autoplay: false
        });

        // process ajax contact form

        var is_sending = false;
        var failure_message = 'Sorry, there was a problem. Please try again later.';

        $('#esl-form').submit(function (e) {
            if (is_sending || !validateInputs()) {
                return false;
            }
            e.preventDefault();
            var $this = $(this); 
            $.ajax({
                url: esl_data.ajax_url,
                type: 'post',
                dataType: 'JSON',
                data: $this.serialize(),
                beforeSend: function () {
                    is_sending = true;
                },
                error: handleFormError,
                success: function (data) {
                    if (data.status === 'success') {
                        alert('Your Card Has Been Delivered! ' + data.message);
                    } else {
                        alert (data.message);
                        handleFormError();
                    }
                }
            });
        });

        function handleFormError() {
            is_sending = false;
            alert(failure_message);
        }

        function validateInputs() {
            var fromName = $('#esl-form input[name="fromName"]').val();
            var fromEmail = $('#esl-form input[name="fromEmail"]').val();
            var toEmail = $('#esl-form input[name="toEmail[]"]').val();
                // add validation that a gallery image is selected //
            
            
            if (! fromName || ! toEmail || ! fromEmail) {
                alert('Please make sure to provide your name and an e-mail');
                return false;
            }
            return true;
        }

    });