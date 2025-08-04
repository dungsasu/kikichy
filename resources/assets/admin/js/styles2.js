$(document).ready(function () {
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    openSidebarCart();
    openSidebarVoucher();
    change_cart();
    mouse_hover_menu();
    get_images_color();

    applyVoucher();
    applyInputVoucher();
    deleteVoucher();

    $("#password-register").on("input", function () {
        var password = $(this).val(); // Lấy giá trị của input
        //console.log(password);
        evaluatePasswordCriteria(password); // Đánh giá mật khẩu và thay đổi màu sắc
    });
    showPasswordToggle();
    resetFormRegister();
    fancyBox();
    $(".login-btn").on("click", function () {
        $("#modalToggle").modal("toggle");
        setTimeout(() => {
            $("#modalToggle").modal("show");
        }, 500);
    });
    toggle_login_modal();
    forgot_password();

    
});

var isLoading = false;

function fancyBox() {
    Fancybox.bind('[data-fancybox="gallery"]', {
        Thumbs: {
            type: "classic",
        },
    });
}

function applyVoucher() {
    $(".applyVoucher").on("click", function () {
        let code = $(this).attr("data-code");

        $.ajax({
            url: "/apply-voucher",
            type: "POST",
            data: {
                voucher: code,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.status == 200) {
                    $(".code-voucher").text(response.data.code)
                    $(".voucher-discount").text(response.data.voucher)
                    $(".alert-message-voucher").text("");

                    closeNavVoucher();
                    $(".input_apply_btn").addClass("d-none");
                    $(".voucher-apply").removeClass("d-none");
                }
            }
        })
    })
}

function deleteVoucher() {
    $(".delete-voucher").on("click", function () {
        let code = $(this).attr("data-code");

        $.ajax({
            url: "/delete-voucher",
            type: "POST",
            data: {
                voucher: code,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.status == 200) {
                    $(".input_apply_btn").removeClass("d-none");
                    $(".voucher-apply").addClass("d-none");
                }
            }
        })
    })
}
function toggle_login_modal() {
    $("#modalToggle").on("hidden.bs.modal", function (e) {
        $(".modal_login").removeClass("d-none");
        $(".modal_register").addClass("d-none");
        $(".model-forgot-password").addClass("d-none");
        $(".modal-forgot-password").addClass("d-none");

        $(".step-1").addClass("d-none");
        $(".step-2").addClass("d-none");
        $(".step-3").addClass("d-none");

        $(".forgot-step-1").addClass("d-none");
        $(".forgot-step-2").addClass("d-none");
        $(".forgot-step-3").addClass("d-none");
        $(".forgot-step-4").addClass("d-none");

        const modal = document.querySelector(".modal-content, .otpCode");
        const inputs = modal.querySelectorAll(
            'input[type="text"], input[type="password"]'
        );

        inputs.forEach((input) => {
            input.value = "";
        });
    });
}
function get_images_color() {
    if (isLoading) return;
    isLoading = true;

    $(document).on("click", ".color-item-outside", function () {
        var colorId = $(this).data("color-id");
        var productId = $(this).data("id-product");
        var default_color = $(this).data("default-color");
        $(".left").html('<span class="loader m-auto pt-3"></span>');

        $.ajax({
            url: "/api/products-color",
            type: "GET",
            data: {
                colorId: colorId,
                productId: productId,
                defaultColor: default_color,
            },
            success: function (response) {
                setTimeout(() => {
                    if (response && response.html) {
                        $(".size-available").html(response.html_size);
                        $(".tooltip").remove();
                        $(".wrapper-button-add-cart").html(
                            response.html_button
                        );

                        if ($(".group_btn_popup_prd").length > 0) {
                            $(".group_btn_popup_prd").html(
                                response.html_button_buynow
                            );
                        }

                        var tooltipTriggerList = [].slice.call(
                            document.querySelectorAll(
                                '[data-bs-toggle="tooltip"]'
                            )
                        );
                        var tooltipList = tooltipTriggerList.map(function (
                            tooltipTriggerEl
                        ) {
                            return new bootstrap.Tooltip(tooltipTriggerEl);
                        });
                        $(".left").fadeOut(300, function () {
                            $(this).html(response.html).fadeIn(300);
                            isLoading = false;

                            setTimeout(() => {
                                $(".main-carousel").owlCarousel({
                                    loop: false,
                                    margin: 10,
                                    nav: false,
                                    items: 1,
                                    autoplay: true,
                                    slideSpeed: 300,
                                    animateOut: "animate__fadeOutDown",
                                    animateIn: "animate__fadeInDown",
                                });
                            }, 200);

                            var owl = $(".main-carousel");
                            owl.on("changed.owl.carousel", function (event) {
                                let item = event.item.index - 2;
                                $(".nav-item").removeClass("active");
                                $(`.nav-item[data-position=${item}]`).addClass(
                                    "active"
                                );
                            });
                            Fancybox.bind('[data-fancybox="gallery"]', {
                                Thumbs: {
                                    type: "classic",
                                },
                            });

                            $(".nav-item").click(function () {
                                let position = $(this).data("position");
                                owl.trigger("to.owl.carousel", [
                                    position,
                                    1000,
                                ]);
                                $(".nav-item").removeClass("active");
                                $(this).addClass("active");
                            });

                            $(".nav-item").hover(function () {
                                owl.trigger("stop.owl.autoplay");
                            });
                        });
                    }
                }, 500);
            },
            error: function (xhr, status, error) {
                console.error("An error occurred: " + error);
            },
        });
    });
}

function openSidebarVoucher() {
    $(".openVoucher").on("click", openNavVoucher);
    $("#closeSidebarVoucher").on("click", closeNavVoucher);
    $(".backdrop").on("click", function (event) {
        if (!$(event.target).closest(".sidebar_cart, .openVoucher").length) {
            closeNavVoucher();
        }
    });
}

function openSidebarCart() {
    $(".openSidebar").on("click", openNav);
    $("#closeSidebar").on("click", closeNav);
    $(".backdrop").on("click", function (event) {
        if (!$(event.target).closest(".sidebar_cart, .openSidebar").length) {
            closeNav();
        }
    });
}

function applyInputVoucher() {
    $(".btn-input-apply-voucher").on("click", function () {
        let val = $(".input_apply_voucher").val();
        $.ajax({
            url: "/apply-voucher",
            type: "POST",
            data: {
                voucher: val,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.status == 200) {
                    $(".code-voucher").text(response.data.code)
                    $(".voucher-discount").text(response.data.voucher)

                    closeNavVoucher();
                    $(".input_apply_btn").addClass("d-none");
                    $(".voucher-apply").removeClass("d-none");
                    $(".alert-message-voucher").text("");
                } else {
                    $(".alert-message-voucher").text(response.message);
                }
            }
        })

    })
}

function showPasswordToggle() {
    $("#show-password-toggle").on("change", function () {
        var isChecked = $(this).is(":checked");
        var passwordField = $("#password-register");

        if (isChecked) {
            passwordField.attr("type", "text"); // Hiển thị mật khẩu
        } else {
            passwordField.attr("type", "password"); // Ẩn mật khẩu
        }
    });
}
function resetFormRegister() {
    $("#modalToggle").on("hidden.bs.modal", function (e) {
        $("#name-register").val("");
        $("#email-register").val("");
        $("#phone-register").val("");
        $("#dob-register").val("");
    });
}
function openNav() {
    $("#mySidebar").css("transform", "translateX(0%)");
    $(".main-menu-header-mobile").css("left", "-1000px");
    $("body").css("overflow", "auto");

    showBackdrop();
}

function openNavVoucher() {
    $("#mySidebarVoucher").css("transform", "translateX(0%)");
    $(".main-menu-header-mobile-voucher").css("left", "-1000px");
    $("body").css("overflow", "auto");

    showBackdropVoucher();
}

function closeNav() {
    $("#mySidebar").css("transform", "translateX(100%)");
    hideBackdrop();
}

function closeNavVoucher() {
    $("#mySidebarVoucher").css("transform", "translateX(100%)");
    // hideBackdrop();
}

function change_cart() {
    $(".show-cart").on("click", function () {
        openNav();
        $(".cart-box").hide();
    });

    $(document).on("change", ".color_select", function () {
        $(".sidebar-content").html(shimmer());

        let $this = $(this);
        let stt = $(this).data("stt");
        let color_name = $(this).val();
        let color_value = $(this).find("option:selected").data("code");
        let csrfToken = $('meta[name="csrf-token"]').attr("content");
        let hash = $(this).data("hash");
        let options = {
            color: {
                label: color_name,
                value: color_value,
            },
        };
        $.ajax({
            url: "/update-cart",
            type: "POST",
            dataType: "json",
            data: {
                hash: hash,
                options: options,
                _token: csrfToken,
            },
            success: function (response) {
                // let arr = response.message;
                // $this.data("hash", arr[stt]);
                // arr.forEach((item, index) => {
                //     $(`.color_select[data-stt=${index}]`).data('hash', item)
                //     $(`.size_select[data-stt=${index}]`).data('hash', item)
                //     $(`.change-quantity-cart[data-stt=${index}]`).data('hash', item)
                // })
                $(".sidebar-content").html(response.html);
                if (response.quantity === 0) {
                    location.href = "/";
                }
                // $this.trigger('change');
            },
            error: function (xhr, status, error) {
                // Xử lý khi có lỗi (ví dụ: hiển thị thông báo lỗi, ...)
                console.error(error);
                alert("Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng");
            },
        });
    });

    $(document).on("change", ".size_select", function () {
        $(".sidebar-content").html(shimmer());

        let $this = $(this);
        let stt = $(this).data("stt");
        let size_name = $(this).val();
        let csrfToken = $('meta[name="csrf-token"]').attr("content");
        let hash = $(this).data("hash");
        let options = {
            size: {
                label: size_name,
                value: size_name,
            },
        };
        $.ajax({
            url: "/update-cart",
            type: "POST",
            dataType: "json",
            data: {
                hash: hash,
                options: options,
                _token: csrfToken,
            },
            success: function (response) {
                let arr = response.message;
                // $this.data("hash", arr[stt]);
                // arr.forEach((item, index) => {
                //     $(`.color_select[data-stt=${index}]`).data('hash', item)
                //     $(`.size_select[data-stt=${index}]`).data('hash', item)
                //     $(`.change-quantity-cart[data-stt=${index}]`).data('hash', item)
                // })
                $(".sidebar-content").html(response.html);
                if (response.quantity === 0) {
                    location.reload();
                }
            },
            error: function (xhr, status, error) {
                // Xử lý khi có lỗi (ví dụ: hiển thị thông báo lỗi, ...)
                console.error(error);
                alert("Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng");
            },
        });
    });

    $(document).on("click", ".remove-cart-item", function () {
        $(".sidebar-content").html(shimmer());

        let $this = $(this);
        let hash = $(this).data("hash");
        let csrfToken = $('meta[name="csrf-token"]').attr("content");

        $.ajax({
            url: "/remove-cart-item",
            type: "POST",
            dataType: "json",
            data: {
                hash: hash,
                _token: csrfToken,
            },
            success: function (response) {
                console.log(response.quantity);
                if (response.quantity === 0) {
                    location.reload();
                }
                $(".sidebar-content").html(response.html);
            },
            error: function (xhr, status, error) {
                // Xử lý khi có lỗi (ví dụ: hiển thị thông báo lỗi, ...)
                console.error(error);
                alert("Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng");
            },
        });
    });

    $(document).on(
        "keyup change",
        ".change-quantity-cart",
        debounce(function () {
            $(".sidebar-content").html(shimmer());
            let $this = $(this);
            let hash = $(this).data("hash");
            let csrfToken = $('meta[name="csrf-token"]').attr("content");
            let quantity = $(this).val();
            let size_name = $(this)
                .closest(".prd_parameter")
                .find(".size_select")
                .val();
            let color_name = $(this)
                .closest(".prd_parameter")
                .find(".color_select")
                .val();
            let color_value = $(this)
                .closest(".prd_parameter")
                .find(".color_select :selected")
                .data("code");

            let fcolor = $(this)
                .closest(".prd_parameter")
                .find(".color_select :selected")
                .data("fcolor");
            let fsize = $(this)
                .closest(".prd_parameter")
                .find(".size_select :selected")
                .data("fsize");

            let options = {
                size: {
                    label: size_name,
                    value: fsize,
                },
                color: {
                    label: color_name,
                    value: color_value,
                    fcolor: fcolor,
                },
            };
            $.ajax({
                url: "/update-cart",
                type: "POST",
                dataType: "json",
                data: {
                    hash,
                    quantity,
                    options,
                    _token: csrfToken,
                },
                success: function (response) {
                    $(".sidebar-content").html(response.html);
                    $(".quantity-cart").text(response.quantity);
                    if (response.quantity === 0) {
                        location.reload();
                    }
                    if ($(".total-pay").length > 0) {
                        $(".total-pay").text(response.total);
                    }

                    if ($(".sumAmount-pay").length > 0) {
                        $(".sumAmount-pay").text(response.sumAmount);
                    }
                    if ($(".subTotal-pay").length > 0) {
                        $(".subTotal-pay").text(response.total);
                    }
                },
                error: function (xhr, status, error) {
                    // Xử lý khi có lỗi (ví dụ: hiển thị thông báo lỗi, ...)
                    console.error(error);
                    alert("Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng");
                },
            });
        }, 300)
    );
    $("#modalToggle").on("hidden.bs.modal", function (e) {
        $(".modal_login").removeClass("d-none");
        $(".modal_register").addClass("d-none");
        $(".step-1").addClass("d-none");
        $(".step-2").addClass("d-none");
        $(".step-3").addClass("d-none");
    });
    
    $(".register-btn").on("click", function () {
        $(".modal_register").removeClass("d-none");
        $(".step-1").removeClass("d-none");
        $(".modal_login").addClass("d-none");
    });

    let email = "";

    $(".register-btn-step1").on("click", function () {
        email = $("input[name='email-phone-register'").val();
        console.log(validate_register());

        if (!validate_register()) {
            return false;
        }
        $(".step-1").addClass("d-none");
        $(".step-2").removeClass("d-none");
        $.ajax({
            url: "/send-code",
            type: "POST",
            data: {
                email: email,
                type: "register",
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.status === "success") {
                } else {
                    Swal.fire({
                        position: "top",
                        icon: "error",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            },
            error: function (xhr) {
                alert("An error occurred: " + xhr.responseText);
            },
        });
    });

    input_code();

    input_code_forgot();

    $(".register-btn-step2").on("click", function () {
        if ($("#otp").val().length == 6) {
            verifyOTP();
        }
    });

    $(".register-btn-step3").on("click", function (e) {
        register(email);
    });

    $("input[name='email-phone-register']").on(
        "input",
        debounce(checkEmail, 500)
    );

    openMenuMobile();
    closeMenuMobile();
    subMenuMobile();
    click_search_header();
}
let email_forgot;

function forgot_password() {
    $(".forget-password").click(function () {
        $(".modal_login, .model_register").addClass("d-none");
        $(".modal-forgot-password").removeClass("d-none");
        $(".forgot-step-1").removeClass("d-none");
    });

    $(".forgot-password-btn-step1").click(function () {
        if (!validate_forgot()) {
            return false;
        }
        email_forgot = $("#email-forgot").val();
        $(".forgot-password-btn-step1").text("Đang xử lý. Vui lòng chờ...");
        $(".forgot-password-btn-step1").css("pointer-events", "none");
        $.ajax({
            url: "/send-code",
            type: "POST",
            data: {
                email: email_forgot,
                type: "forgot-password",
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.status === "success") {
                    $(".forgot-step-1").addClass("d-none");
                    $(".forgot-step-2").removeClass("d-none");
                } else {
                    Swal.fire({
                        position: "top",
                        icon: "error",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
                $(".forgot-password-btn-step1").text("Tiếp tục");
                $(".forgot-password-btn-step1").css("pointer-events", "auto");
            },
            error: function (xhr) {
                alert("An error occurred: " + xhr.responseText);
            },
        });
    });

    $(".forgot-btn-step2").click(function () {
        if ($("#otp-forgot").val().length == 6) {
            verifyOTPForgot();
        }
    });

    $("#password-forgot").on("input", function () {
        var password_forgot = $(this).val();
        console.log(password_forgot);
        evaluatePasswordCriteriaForgot(password_forgot);
    });

    $(".forgot-btn-step3").click(function () {
        if (!validate_change_pass()) {
            return;
        }
        $.ajax({
            url: "/change-password",
            type: "POST",
            data: {
                email: email_forgot,
                new_password: $("#password-forgot").val(),
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    $(".forgot-step-3").addClass("d-none");
                    $(".forgot-step-4").removeClass("d-none");
                } else {
                    Swal.fire({
                        position: "top",
                        icon: "success",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }

                // Điều hướng hoặc làm gì đó khi đăng ký thành công
            },
            error: function (xhr, status, error) {
                // Xử lý lỗi
                console.error("Đã xảy ra lỗi khi đăng ký:", error);
            },
        });
    });
}
function validate_change_pass() {
    if (!notEmpty("password-forgot", "Bạn chưa nhập mật khẩu mới")) {
        return false;
    }
    if (
        !notEmpty(
            "confirm-password-forgot",
            "Nhập lại mật khẩu không được để trống"
        )
    ) {
        return false;
    }
    if ($("#password-forgot").val() !== $("#confirm-password-forgot").val()) {
        invalid(
            "confirm-password-forgot",
            "Mật khẩu nhập lại không trùng khớp"
        );
        return fasle;
    }
    return true;
}

function closeMenuMobile() {
    $(".close-menu-mobile").on("click", function () {
        $("body").css("overflow", "auto");
        $(".main-menu-header-mobile").css("left", "-1000px");
    });
}
function openMenuMobile() {
    $(".open-menu-btn").on("click", function () {
        $("body").css("overflow", "hidden");
        $(".main-menu-header-mobile").css("left", "0");
    });
}

function subMenuMobile() {
    $(".main-menu.level-2")
        .prev("a")
        .css({ "background-color": "#f5f5f5", padding: "8px" });

    $(".back-menu-level-1").on("click", function () {
        $(".main-menu-header-mobile-level-1").css("left", "-1000px");
    });

    $(".main-menu .child-indicator").on("click", function () {
        $("#navMenuContent").html("");
        $(".title-menu-level-0").text($(this).parent().children("a").text());
        var spanContent = $(this).parent().find(".menu-wrapper").html();
        $("#navMenuContent").html(spanContent);
        $(".main-menu-header-mobile-level-1").css("left", "0px");
    });

    // Optional: close the menu when clicking outside of it
    $(window).click(function (event) {
        var navMenu = $("#navMenu");
        if (
            !$(event.target).closest("li").length &&
            !$(event.target).closest(navMenu).length
        ) {
            navMenu.css("width", "0");
        }
    });
}
function checkEmail() {
    let email = $("input[name='email-phone-register']").val();
    $.ajax({
        url: "/check-mail",
        method: "post",
        data: {
            email: email,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.status !== "success") {
                $(".label_error_email").text(response.message.email);
                $(".label_error_email").show();
            } else {
                $(".label_error_email").text("");
                $(".label_error_email").hide();
            }
        },
    });
}

function register(email) {
    $(".modal_login").removeClass("d-none");
    $(".modal_register").addClass("d-none");
    $(".step-1").addClass("d-none");
    $(".step-2").addClass("d-none");
    $(".step-3").addClass("d-none");

    $.ajax({
        url: "/register",
        type: "POST",
        data: {
            email,
            password: $("#password-register").val(),
            name: $("#name-register").val(),
            phone: $("#phone-register").val(),
            dob: $("#dob-register").val(),
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            // Xử lý kết quả thành công
            Swal.fire({
                position: "top",
                icon: "success",
                text: response.message,
                showConfirmButton: false,
                timer: 1500,
            });
            // Điều hướng hoặc làm gì đó khi đăng ký thành công
        },
        error: function (xhr, status, error) {
            // Xử lý lỗi
            console.error("Đã xảy ra lỗi khi đăng ký:", error);
        },
    });
}

function evaluatePasswordCriteria(password) {
    var hasLowerCase = /[a-z]/.test(password);
    var hasUpperCase = /[A-Z]/.test(password);
    var lengthValid = password.length >= 8 && password.length <= 16;
    var onlyAllowedCharacters = /^[a-zA-Z0-9]+$/.test(password);

    var criteria = {
        lowerCase: hasLowerCase,
        upperCase: hasUpperCase,
        length: lengthValid,
        characters: onlyAllowedCharacters,
    };

    for (var key in criteria) {
        if (criteria[key]) {
            $("." + key).css("color", "green");
        } else {
            $("." + key).css("color", "red");
        }
    }
}

function evaluatePasswordCriteriaForgot(password) {
    var hasLowerCase = /[a-z]/.test(password);
    var hasUpperCase = /[A-Z]/.test(password);
    var lengthValid = password.length >= 8 && password.length <= 16;
    var onlyAllowedCharacters = /^[a-zA-Z0-9]+$/.test(password);

    var criteria = {
        "lowerCase-forgot": hasLowerCase,
        "upperCase-forgot": hasUpperCase,
        "length-forgot": lengthValid,
        "characters-forgot": onlyAllowedCharacters,
    };

    for (var key in criteria) {
        if (criteria[key]) {
            $("#" + key).css("color", "green");
        } else {
            $("#" + key).css("color", "red");
        }
    }
}
function verifyOTP(otp) {
    // URL to send the OTP verification request to
    const url = "/verify-otp";

    $.ajax({
        url: url,
        type: "POST",
        data: {
            otp: $("#otp").val(),
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.success) {
                $(".step-1").addClass("d-none");
                $(".step-2").addClass("d-none");
                $(".step-3").removeClass("d-none");
                $(".label_error").hide();
            } else {
                $(".label_error").text(response.message);
                $(".label_error").show();
            }
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error("Error while verifying OTP: " + error);
        },
    });
}

function verifyOTPForgot(otp) {
    // URL to send the OTP verification request to
    const url = "/verify-otp";

    $.ajax({
        url: url,
        type: "POST",
        data: {
            otp: $("#otp-forgot").val(),
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.success) {
                $(".forgot-step-1").addClass("d-none");
                $(".forgot-step-2").addClass("d-none");
                $(".forgot-step-3").removeClass("d-none");
                $(".label_error").hide();
            } else {
                $(".label_error").text(response.message);
                $(".label_error").show();
            }
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error("Error while verifying OTP: " + error);
        },
    });
}
function input_code() {
    ((ele) => {
        let finalCode = [];

        function focusNext(currEle) {
            const next = currEle.nextElementSibling;
            if (next !== null) {
                next.focus();
            }
        }

        function focusPrev(currEle) {
            const prev = currEle.previousElementSibling;
            if (prev !== null) {
                prev.focus();
            }
        }

        function handleBackspace(currEle) {
            if (currEle.value === "") {
                focusPrev(currEle);
            } else {
                currEle.value = "";
            }
        }

        function isValid(keyCode) {
            return (
                (keyCode >= 48 && keyCode <= 57) ||
                (keyCode >= 96 && keyCode <= 105)
            ); // Includes number pad keys
        }

        const wrap = document.createElement("div");
        wrap.classList.add("otpCode");

        for (let i = 0; i < ele.maxLength; i++) {
            const input = document.createElement("input");
            input.classList.add("otpCode__digit");
            input.type = "tel";
            input.pattern = ele.pattern;
            input.maxLength = 1;
            input.required = true;

            input.addEventListener("keypress", (evt) => {
                if (!isValid(evt.keyCode)) {
                    evt.preventDefault();
                }
            });

            input.addEventListener("keyup", (evt) => {
                if (isValid(evt.keyCode)) {
                    const value =
                        evt.keyCode >= 96 && evt.keyCode <= 105
                            ? evt.keyCode - 48
                            : evt.keyCode; // Handle number pad input
                    evt.currentTarget.value = String.fromCharCode(value);
                    focusNext(evt.currentTarget);
                }

                finalCode[i] = evt.currentTarget.value;
                ele.value = finalCode.join("");
            });

            input.addEventListener("keydown", (evt) => {
                switch (evt.keyCode) {
                    case 8: // Backspace
                        handleBackspace(evt.currentTarget);
                        break;
                    case 37: // Left arrow
                        focusPrev(evt.currentTarget);
                        break;
                    case 39: // Right arrow
                        focusNext(evt.currentTarget);
                        break;
                    default:
                        return;
                }
            });

            wrap.appendChild(input);
        }

        ele.parentNode.insertBefore(wrap, ele);
    })(document.querySelector("[data-controller=oneTimeCode]"));
}

function input_code_forgot() {
    ((ele) => {
        let finalCode = [];

        function focusNext(currEle) {
            const next = currEle.nextElementSibling;
            if (next !== null) {
                next.focus();
            }
        }

        function focusPrev(currEle) {
            const prev = currEle.previousElementSibling;
            if (prev !== null) {
                prev.focus();
            }
        }

        function handleBackspace(currEle) {
            if (currEle.value === "") {
                focusPrev(currEle);
            } else {
                currEle.value = "";
            }
        }

        function isValid(keyCode) {
            return (
                (keyCode >= 48 && keyCode <= 57) ||
                (keyCode >= 96 && keyCode <= 105)
            ); // Includes number pad keys
        }

        const wrap = document.createElement("div");
        wrap.classList.add("otpCode");

        for (let i = 0; i < ele.maxLength; i++) {
            const input = document.createElement("input");
            input.classList.add("otpCode__digit");
            input.type = "tel";
            input.pattern = ele.pattern;
            input.maxLength = 1;
            input.required = true;

            input.addEventListener("keypress", (evt) => {
                if (!isValid(evt.keyCode)) {
                    evt.preventDefault();
                }
            });

            input.addEventListener("keyup", (evt) => {
                if (isValid(evt.keyCode)) {
                    const value =
                        evt.keyCode >= 96 && evt.keyCode <= 105
                            ? evt.keyCode - 48
                            : evt.keyCode; // Handle number pad input
                    evt.currentTarget.value = String.fromCharCode(value);
                    focusNext(evt.currentTarget);
                }

                finalCode[i] = evt.currentTarget.value;
                ele.value = finalCode.join("");
            });

            input.addEventListener("keydown", (evt) => {
                switch (evt.keyCode) {
                    case 8: // Backspace
                        handleBackspace(evt.currentTarget);
                        break;
                    case 37: // Left arrow
                        focusPrev(evt.currentTarget);
                        break;
                    case 39: // Right arrow
                        focusNext(evt.currentTarget);
                        break;
                    default:
                        return;
                }
            });

            wrap.appendChild(input);
        }

        ele.parentNode.insertBefore(wrap, ele);
    })(document.querySelector("[data-controller=oneTimeCodeForgot]"));
}
function click_search_header() {
    $("#searchIcon").click(function () {
        var searchForm = $("#searchForm");
        if (searchForm.css("display") === "none") {
            searchForm.css("display", "block").animate(
                {
                    opacity: 1,
                    transform: "scale(1)",
                },
                500,
                function () {
                    searchForm.addClass("show");
                }
            );
        } else {
            searchForm.animate(
                {
                    opacity: 0,
                    transform: "scale(0.9)",
                },
                500,
                function () {
                    searchForm.css("display", "none").removeClass("show");
                }
            );
        }
    });
}
function mouse_hover_menu() {
    let images = get_hot_categories();
    let hover = "";

    $(".main-menu .evel-0").on("mouseover", function () {

        $("#expand-content .wrapper").html("");

        var expandContent = $(this).find(".expand").html();
        if(expandContent == undefined) {
            $(".image-category-hot").html("");
            $("#expand-content").css("padding", "0px");
            return;
        };

        $("#expand-content .wrapper").html(expandContent);
        if (expandContent !== undefined) {
            $("#expand-content").css("padding", "10px 0px");
        } else {
            $("#expand-content").css("padding", "0px");
        }

        let titleLevel0Anchor = $(this).find(".title-menu-0").text();

        let html = "";
        Object.keys(images).forEach((item) => {
            if (titleLevel0Anchor.toLowerCase() === item.toLowerCase()) {
                images[item].forEach((val, index) => {
                    if (index < 2) {
                        html += `<a href="${val.href}" class="image-level-1 ms-3">
                                        <img src="${val.image}" />
                                        <div class="mt-2 fw-bold">${val.text}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
                                        </svg>
                                        </div>
                                    </a>`;
                    }
                });
            }
        });
        $(".image-category-hot").html(html);
    });

    var isHovering = false;

    $(".close-main-menu").mouseleave(function (event) {
        // Check if the mouse has left the document
        // if (!$(event.relatedTarget).closest(".evel-0").length || event.relatedTarget === null) {
        $("#expand-content .wrapper").html("");
        $(".image-category-hot").html("");
        $("#expand-content").css("padding", "0px");
        // }
        isHovering = false;
    });

    $("#expand-content").mouseleave(function (event) {
        if (!isHovering) {
            $("#expand-content .wrapper").html("");
            $(".image-category-hot").html("");
            $("#expand-content").css("padding", "0px");
        }
    });
}

function get_hot_categories() {
    const hiddenInputValue = document.getElementById("hot_images").value;
    const imageData = JSON.parse(hiddenInputValue);
    const images = groupByParentRoot(imageData);
    return images;
}

function groupByParentRoot(data) {
    const groupedData = {};
    for (const item of data) {
        const parentRoot = item.parent_root;
        if (!groupedData[parentRoot]) {
            groupedData[parentRoot] = [];
        }
        groupedData[parentRoot].push({
            image: item.image,
            text: item.parent_text,
            href: item.parent_href,
        });
    }
    return groupedData;
}

function debounce(func, delay) {
    let debounceTimer;
    return function () {
        const context = this;
        const args = arguments;
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => func.apply(context, args), delay);
    };
}

function format_price(value) {
    return value.replace(/[.\s₫]/g, "");
}
function showBackdrop() {
    $(".backdrop")
        .css({
            opacity: 1,
        })
        .css("pointer-events", "initial");
}

function showBackdropVoucher() {
    $(".backdropVoucher")
        .css({
            opacity: 1,
        })
        .css("pointer-events", "initial");
}

function hideBackdrop() {
    $(".backdrop")
        .css({
            opacity: 0,
        })
        .css("pointer-events", "none");
}

function hideBackdropVoucher() {
    $(".backdropVoucher")
        .css({
            opacity: 0,
        })
        .css("pointer-events", "none");
}

function validate_register() {
    if (!notEmpty("name-register", "Vui lòng nhập tên của bạn")) {
        return false;
    }
    if (!notEmpty("email-register", "Vui lòng nhập email của bạn")) {
        return false;
    }
    if (!notEmpty("phone-register", "Vui lòng nhập số điện thoại")) {
        return false;
    }
    if (!notEmpty("dob-register", "Vui lòng chọn ngày sinh nhật")) {
        return false;
    }
    return true;
}
function validate_forgot() {
    if (!notEmpty("email-forgot", "Vui lòng nhập email của bạn")) {
        return false;
    }

    return true;
}
function shimmer() {
    let html = `
<div class="example">
  <div class="avatar shimmer"></div>
  <div class="floatLeft">
    <div class="line shimmer"></div>
    <div class="line shimmer"></div>
    <div class="line line--trunc shimmer"></div>
  </div>
</div>
    `;
    return html;
}
