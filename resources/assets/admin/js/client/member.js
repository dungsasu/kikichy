$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $(".btn_add").on("click", function () {
        $(".btn_submit_add").show();
        $(".btn_submit_edit").hide();

        $(".title_modal").text("Thêm địa chỉ");
        $(".btn_submit_add_edit").text("Thêm");
        $("#name").val("");
        $("#phone").val("");
        $("#address").val("");
        $("#provinces").val("0");
        $("#districts").val("0");
        $("#wards").val("0");
    });
    $("#add_member_address").on("submit", function (e) {
        e.preventDefault();

        var form = $(this);
        var formData = form.serialize();

        if (validateFormAddress()) {
            e.preventDefault();
            $(".btn_submit_edit").css("pointer-events", "none");
            $.ajax({
                url: form.attr("action"),
                method: form.attr("method"),
                data: formData,
                success: function (response) {
                    window.location.href = "/tai-khoan/quan-ly-dia-chi";
                },
                error: function (xhr, status, error) {
                    console.error("Error submitting form:", error);
                },
            });
        }
    });
    $(".btn_edit").on("click", function (event) {
        event.preventDefault();

        var edit_id = $(this).data("id");
        console.log(edit_id);
        $(".title_modal").text("Chỉnh sửa địa chỉ");
        $(".btn_submit_add").hide();
        $(".btn_submit_edit").show();

        //fill data len modal
        var nameValue = $("#member_name_" + edit_id)
            .text()
            .trim();
        var phoneValue = $("#member_phone_" + edit_id)
            .text()
            .trim();
        var addressValue = $("#address_member_" + edit_id)
            .text()
            .trim();

        var fetchLocationData = {
            id_item_to_fetch: edit_id,
        };
        $.ajax({
            url: "/tai-khoan/quan-ly-dia-chi/lay-dia-chi",
            method: "GET",
            data: fetchLocationData,
            success: function (response) {
                $("#name").val(nameValue);
                $("#phone").val(phoneValue);
                $("#address").val(addressValue);

                $("#provinces").val(response.data.city);
                $("#districts").data("member-district", response.data.district);
                $("#wards").data("member-ward", response.data.ward);
                $("#provinces").trigger("change");

                if (response.data.set_default == 1) {
                    $("#set_default").prop("checked", true);
                } else {
                    $("#set_default").prop("checked", false);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching data:", error);
                alert("Có lỗi xảy ra. Vui lòng thử lại.");
            },
        });

        $("#id_address").val(edit_id);
        $("#name").val($(this).data("name"));
        $("#phone").val($(this).data("phone"));
        $("#address").val($(this).data("address"));

        //xong thi gan gia tri data-id-edit cho btn chinh sua
        $("#btn_submit_edit").data("id-edit", edit_id);
    });
    $("#btn_submit_edit").on("click", function (e) {
        e.preventDefault();

        var edit_id = $(this).data("id-edit");
        console.log(edit_id);
        var nameValue = $("#name").val();
        var phoneValue = $("#phone").val();
        var addressValue = $("#address").val();
        var provinceValue = $("#provinces").val();
        var districtValue = $("#districts").val();
        var wardValue = $("#wards").val();
        var set_default = $("#set_default").is(":checked") ? 1 : 0;

        var dataToSend = {
            _token: $('meta[name="csrf-token"]').attr("content"),
            id_edit: edit_id,
            member_name: nameValue,
            member_phone: phoneValue,
            member_address: addressValue,
            id_province: provinceValue,
            id_district: districtValue,
            id_ward: wardValue,
            set_default: set_default,
        };
        if (validateFormAddress()) {
            e.preventDefault();
            $(".btn_submit_edit").css("pointer-events", "none");
            $.ajax({
                url: "/tai-khoan/quan-ly-dia-chi/chinh-sua",
                method: "POST",
                data: dataToSend,
                success: function (response) {
                    $("#user_address_add").modal("hide");
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching data:", error);
                },
            });
        }
    });

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

                    $("#districts").val(
                        $("#districts").data("member-district")
                    );
                    $("#districts").trigger("change");
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

    $(".btn_delete").on("click", function () {
        var id = $(this).data("id");
        var result = confirm("Bạn có chắc chắn muốn xóa địa chỉ này không?");
        var delete_url = "/tai-khoan/quan-ly-dia-chi/xoa";
        var dataToSend = {
            _token: $('meta[name="csrf-token"]').attr("content"),
            id_delete: id,
        };
        console.log(delete_url);
        console.log("Data to be sent: ", dataToSend);
        if (result) {
            $.ajax({
                url: delete_url,
                method: "POST",
                data: dataToSend,
                success: function (response) {
                    if (response.success) {
                        console.log(response);
                        alert("Địa chỉ đã được xóa thành công.");
                        location.reload();
                    } else {
                        alert("Có lỗi xảy ra. Vui lòng thử lại.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error deleting data:", error);
                    alert("Có lỗi xảy ra. Vui lòng thử lại.");
                },
            });
        }
    });
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

                    $("#wards").val($("#wards").data("member-ward"));
                    $("#wards").trigger("change");
                },
            });
        } else {
            $("#wards")
                .attr("disabled", "disabled")
                .html('<option value="">Select a ward</option>');
        }
    });

    var alert_info = $("#alert_info").val();

    alert_info1 = alert_info ? JSON.parse(alert_info) : [];
    function validateFormAddress() {
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
        if (!notEmpty("address", alert_info1[12])) {
            return false;
        }

        if (!notEmptySelect("provinces", "0", alert_info1[15])) {
            return false;
        }
        if (!notEmptySelect("districts", "0", alert_info1[13])) {
            return false;
        }
        if (!notEmptySelect("wards", "0", alert_info1[14])) {
            return false;
        }

        return true;
    }
});
