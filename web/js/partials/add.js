function setModalHeader(e_slug) {
    $.post(Routing.generate('get_modal_header', {slug: e_slug}), function (data) {
        if (data.result) {
            $('.change-title').html(data.html);
        } else {
            console.log('Error:'+data.error);
        }
    });
}

function setPaymentHtml(e_slug, open) {
    $.ajax({
        type: 'POST',
        url: Routing.generate('event_pay', {event_slug: e_slug}),
        success: function (data) {
            if (data.result) {
                $('#pay-form').html(data.html).data('event', e_slug);
                $('#payment-sums').html(data.paymentSums);
                if (open === true) {
                    window.location.replace('#modal-payment');
                }
            } else {
                $('#pay-form').html(data.html);
                console.log('Error:' + data.error);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            switch (jqXHR.status) {
                case 401:
                    window.location.replace('#modal-signin-payment');
                    break;
                case 403: window.location.reload(true);
            }
        }
    });
}

$(document).on('click', '.user-payment__remove', function () {
        console.log('here');
        var elem = $(this);
        var e_slug = $('#pay-form').data('event');
        $.post(Routing.generate('remove_ticket_from_payment',
            {
                event_slug: e_slug,
                id: elem.data('ticket')
            }),
            function (data) {
                if (data.result) {
                    $('#pay-form').html(data.html);
                    $('#payment-sums').html(data.paymentSums);
                } else {
                    $('#pay-form').html(data.html);
                    console.log('Error:'+data.error);
                }
            });
});

$(document).on('click', '.add-wants-visit-event', function () {
        var elem = $(this);
        var e_slug = elem.data('event');
        $.ajax({
            type: 'POST',
            url: Routing.generate('add_wants_to_visit_event', { slug: e_slug}),
            success: function(data) {
                if (data.result) {
                    elem.removeClass('add-wants-visit-event').addClass('sub-wants-visit-event').prop('onclick',null)
                        .off('click').html(data.html);
                    setSubWantsOnclick();
                } else {
                    elem.html(data.html);
                    console.log('Error:'+data.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                switch (jqXHR.status) {
                    case 401:
                        window.location.replace('#modal-signin-payment');
                        break;
                    case 403:
                        window.location.reload(true);
                }
            }
        });
});

$(document).on('click', '.sub-wants-visit-event', function () {
        var elem = $(this);
        var e_slug = elem.data('event');
        $.post(Routing.generate('sub_wants_to_visit_event', {slug: e_slug}), function (data) {
            if (data.result) {
                elem.removeClass('sub-wants-visit-event').addClass('add-wants-visit-event').prop('onclick', null)
                    .off('click').html(data.html);
                setAddWantsOnclick();
            } else {
                elem.html(data.html);
                console.log('Error:'+data.error);
            }
        });
});

$(document).on('opening', '.remodal', function (e) {
    if (window.location.hash === '#modal-payment') {
        var e_slug = Cookies.get('event');
        if (e_slug) {
            Cookies.remove('event', { path: '/', http: false, secure : false });
            setModalHeader(e_slug);
            setPaymentHtml(e_slug, false);
        }
    }
});

$(document).ready(function () {

    $('.speaker-card__top').on('click', function () {
        var e_slug = $(this).data('event');
        var s_slug = $(this).data('speaker');

        $.get(Routing.generate('speaker_popup', { event_slug: e_slug, speaker_slug:s_slug}), function (data) {
            $('#speaker-popup-content').html(data.html);
            window.location.replace('#modal-speaker');
        });
    });

    $('.set-modal-header').on('click', function () {
        var e_slug = $(this).data('event');
        setModalHeader(e_slug);
    });

    $('.get-payment').on('click', function () {
        var elem = $(this);
        var e_slug = elem.data('event');
        setPaymentHtml(e_slug, true);
    });

    $('.add-promo-code-btn').on('click', function () {
        if ($('#user_promo_code').valid()) {
            var e_slug = $('#pay-form').data('event');
            $.post(Routing.generate('add_promo_code', {code: $("input[name='promo-code']").val(), event_slug: e_slug}),
                function (data) {
                    if (data.result) {
                        $('#pay-form').html(data.html);
                        $('#payment-sums').html(data.paymentSums);
                    } else {
                        $('#pay-form').html(data.html);
                        console.log('Error:' + data.error);
                    }
                });
        }
    });

    $('.add-user-btn').on('click', function () {
        if ($('#payment_user_name').valid() &&
            $('#payment_user_surname').valid() &&
            $('#payment_user_email').valid()) {
            var e_slug = $('#pay-form').data('event');
            $.post(Routing.generate('add_participant_to_payment',
                {
                    event_slug: e_slug,
                    name: $("input[name='user-name']").val(),
                    surname: $("input[name='user-surname']").val(),
                    email: $("input[name='user-email']").val()
                }),
                function (data) {
                    if (data.result) {
                        $('#pay-form').html(data.html);
                        $('#payment-sums').html(data.paymentSums);
                    } else {
                        $('#pay-form').html(data.html);
                        console.log('Error:' + data.error);
                    }
                });
        }
    });

    $(document).on('click', '.like-btn-js', function (e) {
        e.preventDefault();
        var rv_slug = $(this).data('review');
        $.post(Routing.generate('like_review', {review_slug: rv_slug}),
            function (data) {
                if (data.result) {
                    $("div[data-review='"+ rv_slug+"']").html('<i class="icon-like like-btn__icon"></i>'+data.likesCount);
                } else {
                    $("div[data-review='"+rv_slug+"']").html('<i class="icon-like like-btn__icon"></i>error');
                }
        });
    });

    // setAddWantsOnclick();
    // setSubWantsOnclick();
});
