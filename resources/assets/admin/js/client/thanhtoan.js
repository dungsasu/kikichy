$(document).ready(function () {
    $(".item_address_select").on("click", function () {
        var edit_id = $(this)
            .find('span[id^="address_fr_list_"]')
            .attr("id")
            .match(/\d+$/)[0];
        //console.log("Address item id:" + addressItemId);
        var fetchLocationData = {
            id_item_to_fetch: edit_id,
        };
        console.log(fetchLocationData);

        $.ajax({
            url: "/tai-khoan/quan-ly-dia-chi/lay-dia-chi",
            method: "GET",
            data: fetchLocationData,
            success: function (response) {
                console.log(response);
                $("#name").val(response.data.name);
                $("#phone").val(response.data.phone);
                $("#address").val(response.data.address);

                $("#provinces").val(response.data.city);
                $("#districts").data("member-district", response.data.district);
                $("#wards").data("member-ward", response.data.ward);
                $("#provinces").trigger("change");
                $("#list_address_choose").modal("hide");
            },
            error: function (response) {
                console.error("Error fetching data:", error);
                alert("Có lỗi xảy ra. Vui lòng thử lại.");
            },
        });
    });
    var alert_info = $("#alert_info").val();
    alert_info1 = alert_info ? JSON.parse(alert_info) : [];

    $(".confirm_purchase").click(function (e) {
        var submit = "#form-thanh-toan";
        if (checkFormsubmit2()) {
            e.preventDefault();
            $(".confirm_purchase").css("pointer-events", "none");
            $("#form-thanh-toan").submit();
        }
    });
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
    });

    $.ajax({
        url: "/provinces",
        type: "GET",
        success: function (data) {
            var provincesSelect = $("#provinces");
            let province_id = $("#provinces").data("member-province");
            data.forEach(function (province) {
                provincesSelect.append(
                    new Option(province.name, province.code)
                );
            });
            if (province_id) {
                $("#provinces").val(province_id);
                $("#provinces").trigger("change");
            }
        },
    });

    // When a province is selected, load districts
    $("#provinces").change(function () {
        var provinceCode = $(this).val();
        if (provinceCode) {
            $("#districts")
                .removeAttr("disabled")
                .html('<option value="">Quận/Huyện</option>');
            $("#wards")
                .attr("disabled", "disabled")
                .html('<option value="">Phường/Xã</option>');
            $.ajax({
                url: "/districts/" + provinceCode,
                type: "GET",
                success: function (data) {
                    var districtsSelect = $("#districts");
                    data.forEach(function (district) {
                        districtsSelect.append(
                            new Option(district.name, district.code)
                        );
                    });
                    let district_id = $("#districts").data("member-district");

                    if (district_id) {
                        $("#districts").val(district_id);
                        $("#districts").trigger("change");
                    }
                },
            });
        } else {
            $("#districts")
                .attr("disabled", "disabled")
                .html('<option value="">Quận/Huyện</option>');
            $("#wards")
                .attr("disabled", "disabled")
                .html('<option value="">Phường/Xã</option>');
        }
    });

    // When a district is selected, load wards
    $("#districts").change(function () {
        var districtCode = $(this).val();
        if (districtCode) {
            $("#wards")
                .removeAttr("disabled")
                .html('<option value="">Phường/Xã</option>');
            $.ajax({
                url: "/wards/" + districtCode,
                type: "GET",
                success: function (data) {
                    var wardsSelect = $("#wards");
                    data.forEach(function (ward) {
                        wardsSelect.append(new Option(ward.name, ward.code));
                    });

                    let ward_id = $("#wards").data("member-ward");

                    if (ward_id) {
                        $("#wards").val(ward_id);
                        $("#wards").trigger("change");
                    }
                },
            });
        } else {
            $("#wards")
                .attr("disabled", "disabled")
                .html('<option value="">Select a ward</option>');
        }
    });

    $("#openSidebar").off("click");

    $(".btn_adjust_quantity .increase").on("click", function () {
        var input = $(this)
            .closest(".adjust_quantity")
            .find(".change-quantity-cart");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            input.val(currentVal + 1);
        } else {
            input.val(1);
        }
    });
    $(".btn_adjust_quantity .decrease").on("click", function () {
        var input = $(this)
            .closest(".adjust_quantity")
            .find(".change-quantity-cart");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal) && currentVal > 0) {
            input.val(currentVal - 1);
        } else {
            input.val(0);
        }
    });
});

function checkFormsubmit2() {
    $("label.label_error").prev().remove();
    $("label.label_error").remove();
    // email_new = $('#email_new').val();

    if (!notEmpty("name", alert_info1[1])) {
        return false;
    }
    if (!notEmpty("phone", alert_info1[5])) {
        return false;
    }
    if (!isPhone("phone", alert_info1[6])) {
        return false;
    }
    if (!notEmpty("email", alert_info1[3])) {
        return false;
    }
    if (!emailValidator("email", alert_info1[4])) {
        return false;
    }
    if (!notEmpty("address", alert_info1[12])) {
        return false;
    }

    if (!notEmptySelect("provinces", "0", alert_info1[13])) {
        return false;
    }
    if (!notEmptySelect("districts", "0", alert_info1[14])) {
        return false;
    }
    if (!notEmptySelect("wards", "0", alert_info1[14])) {
        return false;
    }

    return true;
}
