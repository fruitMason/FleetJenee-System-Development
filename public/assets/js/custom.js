//custom functions and declarations
let _token = $('meta[name="csrf-token"]').attr('content');

var oTable;
let modal_effect = 'effect-sign';


function waitme(element) {
    $("#"+element).waitMe({
        effect : 'ios',
        text : 'Processing Please wait',
        bg : 'rgba(255,255,255,0.7)',
        color : '#000',
        maxSize : '',
        waitTime : -1,
        textPos : 'vertical',
        fontSize : '',
        source : '',
        onClose : function() {}
    });
}

function hidewaitme(element) {
    $("#"+element).waitMe("hide");
}


function show_toast(title, message, type, delay = 5000) {
    switch (type) {
        case 'success':
            toastr.success(message, title,
                {
                    positionClass: 'toast-bottom-right',
                    containerId: 'toast-bottom-right',
                    "showMethod": "slideDown",
                    "hideMethod": "slideUp",
                    "hideEasing": "swing",
                    timeOut: delay
                });
            break;

        case 'error':
            toastr.error(message, title,
                {
                    positionClass: 'toast-bottom-right',
                    containerId: 'toast-bottom-right',
                    "showMethod": "slideDown",
                    "hideMethod": "slideUp",
                    "hideEasing": "swing",
                    timeOut: delay
                });
            break;

        case 'info':
            toastr.info(message, title,
                {
                    positionClass: 'toast-bottom-right',
                    containerId: 'toast-bottom-right',
                    "showMethod": "slideDown",
                    "hideMethod": "slideUp",
                    "hideEasing": "swing",
                    timeOut: delay
                });
            break;

        case 'warning':
            toastr.warning(message, title,
                {
                    positionClass: 'toast-bottom-right',
                    containerId: 'toast-bottom-right',
                    "showMethod": "slideDown",
                    "hideMethod": "slideUp",
                    "hideEasing": "swing",
                    timeOut: delay
                });
            break;
    }
}

function modal_animation(element) {
    // On show
    $("#"+element).on('show.bs.modal', function () {
        $(this).addClass(modal_effect);
    });

    $("#"+element).on('hidden.bs.modal', function(e) {
        $(this).removeClass(function(index, className) {
            return (className.match(/(^|\s)effect-\S+/g) || []).join(' ');
        });
    });

}

function modal_select(select_class, form_id) {

    select_class.select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        dropdownParent: form_id,
        width: '100%'
    });
}

function modal_select_search(select_class, form_id) {

    select_class.select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        dropdownParent: form_id,
        width: '100%'
    });
}


function add_error_class(element) {
    $("#"+element).addClass('input-validation-error');
}

function remove_error_class(element) {
    $("#"+element).removeClass('input-validation-error');
}

function refreshDataTable() {
    window.LaravelDataTables["dataTableBuilder"].ajax.reload();
}

function refreshPage() {
    document.location.reload();
}

function ajax(route, data = {}, waitme_pivot = 'card_content', success) {
    $.ajax({
        url: route,
        data: data,
        type: 'POST',
        beforeSend: function () {
            waitme(waitme_pivot);
        },
        complete: function () {
            hidewaitme(waitme_pivot);
        },
        error: function (xhr, status, error) {
            console.log(xhr);
            let errors = '';
            $.each(xhr.responseJSON.errors, function (i, v) {
                errors+='- '+v[0]+'<br/>';
            });

            if(xhr.responseJSON.errors !== undefined) {
                show_toast('Code #'+xhr.status, errors, 'warning');
            }
            else {
                show_toast('Code #'+xhr.status, xhr.responseJSON.message.substring(0, 100), 'warning');
            }
        },
        dataType: 'json',
        success: success
    });
}

function ajax_upload(route, data = {}, waitme_pivot = 'card_content', success) {
    $.ajax({
        url: route,
        data: data,
        processData: false,
        contentType: false,
        type: 'POST',
        beforeSend: function () {
            waitme(waitme_pivot);
        },
        complete: function () {
            hidewaitme(waitme_pivot);
        },
        error: function (xhr, status, error) {
            let errors = '';
            $.each(xhr.responseJSON.errors, function (i, v) {
                errors+='- '+v[0]+'<br/>';
            });

            if(xhr.responseJSON.errors !== undefined) {
                show_toast('Code #'+xhr.status, errors, 'warning');
            }
            else {
                show_toast('Code #'+xhr.status, xhr.responseJSON.message.substring(0, 100), 'warning');
            }
            $('#form_bulk_upload').trigger('reset');
        },
        dataType: 'json',
        success: success
    });
}

function scrollTo(element) {
    $("html").animate(
        {
            scrollTop: $('#'+element).offset().top
        },
        1000 //speed
    );
}

function resetForm(element) {
    $("#"+element).trigger('reset');
}
