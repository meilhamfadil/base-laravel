$.extend($.fn.dataTable.defaults, {
    serverSide: true,
    processing: true,
    ajax: {
        url: '',
        type: 'post',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: function (d) {
            d._token = $('input[name=_token]').val();
            var data = $.extend({}, d, (typeof buildDatatableParam === 'function') ? buildDatatableParam() : {});
            return data;
        }
    },
    responsive: true,
    dom: '<"row"<"col-6"l><"col-6 text-right"f>>t<"row"<"col-6"i><"col-6 text-right"p>>r',
    lengthMenu: [
        [5, 10, 25, 50, -1],
        [5, 10, 25, 50, 'Semua'],
    ],
    language: {
        decimal: '',
        emptyTable: 'Data tidak tersedia',
        info: 'Menampilkan data _START_ sampai _END_ dari total _TOTAL_ data',
        infoEmpty: 'Menampilkan data kosong',
        infoFiltered: '',
        infoPostFix: '',
        thousands: '.',
        lengthMenu: 'Menampilkan _MENU_ data',
        loadingRecords: 'Memuat...',
        processing: 'Memuat...',
        search: 'Cari:',
        zeroRecords: 'Data tidak ditemukan',
        paginate: {
            first: 'Awal',
            last: 'Akhir',
            next: 'Lanjut',
            previous: 'Kembali'
        },
        aria: {
            'sortAscending': ': pengurutan diaktifkan',
            'sortDescending': ': pengurutan terbalik diaktifkan'
        }
    }
});

$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

$.fn.formHandler = function (
    validator = {},
    action = function () {
        showMessage('You haven\'t register callback yet', 'error')
    }
) {
    this.validate({
        ...validator,
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            element.closest('.input-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function (form) {
            action(form)
        }
    })
}

$("document").ready(function () {

    $(".select2").select2({
        minimumResultsForSearch: -1,
        theme: 'bootstrap4'
    });

    $(".select2-findable").select2({
        theme: 'bootstrap4'
    });

});

function showMessage(message, type = 'success', body = null) {
    let temp = null
    if (body !== null) {
        temp = message;
        message = body;
        body = temp;
    }

    switch (type) {
        case 'warning':
            toastr.warning(message, body)
            break;
        case 'error':
            toastr.error(message, body)
            break;
        case 'info':
            toastr.info(message, body)
            break;
        default:
            toastr.success(message, body)
            break;
    }
}

function disable(view) {
    view.attr('disabled', 'disabled')
}

function enable(view) {
    view.removeAttr('disabled')
}

let loadingContentHolder = ''

function loading(view, show) {
    if (show) {
        loadingContentHolder = view.html()
        view.html('<img class="loader" src="/img/loader.svg">');
    } else {
        view.html(loadingContentHolder);
    }
}

function swap(origin, target, simultaneously = true) {
    let originObject = (typeof origin === 'string') ? $(origin) : origin
    let targetObject = (typeof target === 'string') ? $(target) : target
    if (simultaneously) {
        originObject.slideUp();
        targetObject.slideDown();
    } else {
        originObject.slideUp(function () {
            targetObject.slideDown();
        });
    }
}

function redirect(target) {
    if (!target.startsWith('/'))
        target = '/' + target
    $(location).prop('href', APP_URL + target)
}