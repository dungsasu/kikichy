$(document).ready(function () {
    $("#form_contact").on("submit", function (e) {
        console.log("clicked");
        e.preventDefault();
        if (validateFormContact()) {
            console.log("clicked_2");

            // $("#submit_form_contact").css("pointer-events", "none");
            $(this).off("submit").submit();
            return true;
        }
        return false;
    });

    var alert_info = $("#alert_info").val();

    alert_info1 = alert_info ? JSON.parse(alert_info) : [];

    function validateFormContact() {
        $("label.label_error").prev().remove();
        $("label.label_error").remove();
        // email_new = $('#email_new').val();

        if (!notEmpty("fullname", alert_info1[0])) {
            return false;
        }
        if (!checkSQLInjection("fullname", alert_info1[1])) {
            console.log("tên có chứa từ khoá ");
            return false;
        }
        if (!notEmpty("phone", alert_info1[2])) {
            return false;
        }
        if (!isPhone("phone", alert_info1[3])) {
            return false;
        }
        if (!notEmpty("email", alert_info1[4])) {
            return false;
        }
        if (!emailValidator("email", alert_info1[5])) {
            return false;
        }
        if (!notEmpty("title", alert_info1[6])) {
            return false;
        }
        if (!checkSQLInjection("title", alert_info1[8])) {
            return false;
        }
        if (!notEmpty("content", alert_info1[7])) {
            return false;
        }
        if (!checkSQLInjection("content", alert_info1[9])) {
            return false;
        }

        return true;
    }
});
