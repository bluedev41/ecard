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
                        alert('Your Card Has Been Delivered!');
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
            var toEmail1 = $('#esl-form input[name="toEmail1"]').val();
                // add validation that a gallery image is selected //
                
            if (! fromName || ! toEmail1) {
                alert('Before sending, please make sure to provide your name ' + fromName + 'and an e-mail' + toEmail1);
                return false;
            }
            return true;
        }

    });