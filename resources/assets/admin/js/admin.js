let selected = [];

$(document).ready(function () {

    $('.select2').select2();

    $(".delete-record").click(function () {
        var link = $(this).data("link");
        deleteRecord(link)
    })

    $('#submit1, #submit2, #submit3').on('click', function (e) {
        e.preventDefault();
        let submitValue = $(this).attr('id').replace('submit', '');
        updateEditorValues();
        addHiddenInputAndSubmit(submitValue);
    });

    $("#selectAllRecord").on('change', function () {
        var isChecked = $(this).is(':checked');
        $('.form-record[type=checkbox]').not('#selectAllRecord').prop('checked', isChecked);
        $('.form-record[type=checkbox]').not('#selectAllRecord').each(function () {
            if ($(this).is(':checked')) {
                selected.push($(this).val());
            } else {
                selected = selected.filter(item => item != $(this).val());
            }
        })
    });
    $('.form-record[type=checkbox]').not('#selectAllRecord').on('change', function () {
        if ($(this).is(':checked')) {
            selected.push($(this).val());
        } else {
            selected = selected.filter(item => item != $(this).val());
        }
    })



    $(`.button-action`).on('click', function () {
        let link = $(this).data('link');
        let model = $(this).data('model');
        if (link == '/api/delete') {
            Swal.fire({
                title: "Thông báo?",
                text: "Bạn chắc chắn muốn xoá bản ghi này!",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "Chắc chắn!",
                customClass: {
                    confirmButton: "btn btn-primary me-3",
                    cancelButton: "btn btn-label-secondary"
                },
                buttonsStyling: !1
            }).then(function (n) {
                if (n.value) {
                    performAjaxRequest(link, model);
                } else if (n.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Huỷ bỏ",
                        text: "Bản ghi chưa được xoá",
                        icon: "error",
                        customClass: {
                            confirmButton: "btn btn-success"
                        }
                    });
                }
            })
        } else {
            performAjaxRequest(link, model);
        }
    })

    $(".form-check-input-status").on('change', function () {
        let id = $(this).attr('id');
        if ($(this).is(':checked')) {
            $(this).data('link', `/api/${id}/1`)
        } else {
            $(this).data('link', `/api/${id}/0`)
        }
        let link = $(this).data('link');
        let model = $(this).data('model');
        $.ajax({
            type: "POST",
            url: link,
            data: {
                ids: [parseInt($(this).data('id'))],
                model: model
            },
            success: function (res) {
                let icon = res.status == 200 ? "success" : "error";
                let title = res.status == 200 ? "Thành công!" : "Thất bại!";

                if (res.status == 200) {
                    showToasts(res.message, 'success');
                }
                else {
                    showToasts(res.message, 'error');
                }
            },
            error: function (r) {
                console.log(r);
            }
        })
    })


});

function showToasts(content, type) {
    $('.bs-toast').toast('show')
    $('.toast-body').text(content)
    if ('success' == type) {
        $('.toast-header').addClass('bg-success');
        $('.toast-header').removeClass('bg-danger')
    } else {
        $('.toast-header').addClass('bg-danger');
        $('.toast-header').removeClass('bg-success');
    }
}

function updateEditorValues() {
    if (typeof window.editors !== 'undefined' && window.editors !== null && Object.keys(window.editors).length > 0) {
        Object.keys(window.editors).forEach(item => {
            let editor = item.replace('editor_', '');
            $(`#${editor}-value`).val(window.editors[item].getData());
        });
    }
    if (typeof window.ace_editor !== 'undefined' && window.ace_editor !== null && Object.keys(window.ace_editor).length > 0) {
        Object.keys(window.ace_editor).forEach(item => {
            let ace_editor = item.replace('_ace_editor', '');
            $(`#${ace_editor}_ace_value`).val(window.ace_editor[item].getValue());
        });
    }
}

function addHiddenInputAndSubmit(submitValue) {
    $('<input>').attr({
        type: 'hidden',
        name: 'shouldRedirect',
        value: submitValue
    }).appendTo('#formAccountSettings');
    $("#formAccountSettings").submit();
}

function deleteRecord(link) {
    Swal.fire({
        title: "Thông báo?",
        text: "Bạn chắc chắn muốn xoá bản ghi này!",
        icon: "warning",
        showCancelButton: !0,
        confirmButtonText: "Chắc chắn!",
        customClass: {
            confirmButton: "btn btn-primary me-3",
            cancelButton: "btn btn-label-secondary"
        },
        buttonsStyling: !1
    }).then(function (n) {
        if (n.value) {
            $.ajax({
                type: "GET",
                url: link,
                success: function (res) {
                    res = JSON.parse(res);
                    if (res.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Thành công!",
                            text: "Đã xoá bản ghi thành công!",
                            customClass: {
                                confirmButton: "btn btn-success"
                            }
                        });

                        setTimeout(() => {
                            window.location.reload()
                        }, 300);
                    }
                },
                error: function (r) {
                    console.log(r);
                }
            });
        } else if (n.dismiss === Swal.DismissReason.cancel) {
            Swal.fire({
                title: "Huỷ bỏ",
                text: "Bản ghi chưa được xoá",
                icon: "error",
                customClass: {
                    confirmButton: "btn btn-success"
                }
            });
        }
    });
}







function performAjaxRequest(link, model) {
    $.ajax({
        type: "POST",
        url: link,
        data: {
            ids: selected,
            model: model
        },
        success: function (res) {
            let icon = res.status == 200 ? "success" : "error";
            let title = res.status == 200 ? "Thành công!" : "Thất bại!";

            Swal.fire({
                icon: icon,
                title: title,
                text: res.message,
                customClass: {
                    confirmButton: "btn btn-success"
                }
            });

            if (res.status == 200) {
                setTimeout(() => {
                    window.location.reload()
                }, 300);
            }
        },
        error: function (r) {
            console.log(r);
        }
    })
}
