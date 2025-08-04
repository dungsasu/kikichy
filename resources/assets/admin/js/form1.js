/*************** CHECK FORM ***************/
//If the length of the element's string is 0 then display helper message
function notEmpty(elemid, helperMsg) {
    elem = $("#" + elemid);
    var val_elem = elem.val();

    if (!val_elem || val_elem.length == 0) {
        invalid(elemid, helperMsg);
        elem.focus(); // set the focus to this input
        return false;
    } else {
        valid(elemid);
        return true;
    }
}
function notEmptySelect(elemid, txt_default, helperMsg) {
    var elem = $("#" + elemid);
    console.log(elem.val());
    if (!elem.val() || elem.val() == txt_default || elem.val() == 0) {
        console.log(elem.val());
        invalid(elemid, helperMsg);
        elem.focus();
        return false;
    } else {
        valid(elemid);
        return true;
    }
}
function notEmpty2(elemid, txt_default, helperMsg) {
    elem = $("#" + elemid);
    if (!elem.val() || elem.val().length == 0) {
        invalid(elemid, helperMsg);
        elem.focus(); // set the focus to this input
        return false;
    } else if (elem.val() == txt_default) {
        invalid(elemid, helperMsg);
        elem.focus(); // set the focus to this input
        return false;
    } else {
        valid(elemid);
        return true;
    }
}
//For texarea
function notEmptyTextarea(elemid, helperMsg) {
    elem = $("#" + elemid);
    if (elem.val().length == 0) {
        invalid(elemid, helperMsg);
        elem.focus(); // set the focus to this input
        return false;
    } else {
        valid(elemid);
        return true;
    }
}

function isPhone(elemid, helperMsg) {
    elem = $("#" + elemid);
    var numericExpression = /(84|0[3|5|7|8|9])+([0-9]{8})\b/g;
    if (elem.val().match(numericExpression)) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, helperMsg);
        return false;
    }
}

function notValue(elemid, helperMsg) {
    elem = $("#" + elemid);
    if (elem.val() == "0") {
        invalid(elemid, helperMsg);
        elem.focus(); // set the focus to this input
        return false;
    } else {
        valid(elemid);
        return true;
    }
}

function notfile(elemid, helperMsg) {
    elem = $("#" + elemid);
    if (elem.val() == "") {
        invalid(elemid, helperMsg);
        elem.focus(); // set the focus to this input
        return false;
    } else {
        valid(elemid);
        return true;
    }
}

function isUsername(elemid, helperMsg) {
    elem = $("#" + elemid);
    var strExp = /^[0-9a-zA-Z_-]+$/;
    if (elem.val().match(strExp)) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, helperMsg);
        return false;
    }
}

//If the element's string matches the regular expression it is all numbers

function isNumeric(elemid, helperMsg) {
    elem = $("#" + elemid);
    var numericExpression = /^[0-9]+$/;
    if (elem.val().match(numericExpression)) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, helperMsg);
        elem.focus();
        return false;
    }
}

/*
 * check number list follow: 048082354;09238549;
 */
function isNumericList(elemid, helperMsg) {
    elem = $("#" + elemid);
    var numericExpression = /^[0-9; ]+$/;
    if (elem.val().match(numericExpression)) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, helperMsg);
        elem.focus();
        return false;
    }
}

//If the element's string matches the regular expression it is all letters
function isAlphabet(elemid, helperMsg) {
    elem = $("#" + elemid);
    var alphaExp = /^[a-zA-Z]+$/;
    if (elem.val().match(alphaExp)) {
        return true;
    } else {
        invalid(elemid, helperMsg);
        elem.focus();
        return false;
    }
}

//If the element's string matches the regular expression it is numbers and letters
function isAlphanumeric(elemid, helperMsg) {
    elem = $("#" + elemid);
    var alphaExp = /^[0-9a-zA-Z]+$/;
    if (elem.val().match(alphaExp)) {
        return true;
    } else {
        invalid(elemid, helperMsg);
        elem.focus();
        return false;
    }
}

// Limit length
function lengthRestriction(elemid, min, max) {
    elem = $("#" + elemid);
    var uInput = elem.val();
    if (uInput.length >= min && uInput.length <= max) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, "Vui lòng nhập từ " + min + " đến " + max + " số");
        elem.focus();
        return false;
    }
}
// check kiem tra co' ky' tu' hoa trong chuoi hay khong
function uppercase(elemid, helperMsg) {
    elem = $("#" + elemid);
    var uInput = elem.val();

    var isnum = /[A-Z]/.test(uInput);
    if (isnum) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, helperMsg);
        return false;
    }
}
// check kiem tra co' so' trong chuoi hay khong
function number_pass(elemid, helperMsg) {
    elem = $("#" + elemid);
    var uInput = elem.val();

    var isnum = /[0-9]/.test(uInput);
    if (isnum) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, helperMsg);
        return false;
    }
}
// Min length
function lengthMin(elemid, min, helperMsg) {
    elem = $("#" + elemid);
    var uInput = elem.val();
    if (uInput.length >= min) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, helperMsg);
        return false;
    }
}

// length max
function lengthMax(elemid, max, helperMsg) {
    elem = $("#" + elemid);
    var uInput = elem.val();
    if (uInput.length < max) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, helperMsg);
        return false;
    }
}
// min price
function lengtprice(elemid, min, helperMsg) {
    elem = $("#" + elemid);
    var uInput = elem.val();
    if (uInput && uInput >= min) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, helperMsg);
        return false;
    }
}

// max price
function maxprice(elemid, max, helperMsg) {
    elem = $("#" + elemid);
    var uInput = elem.val();
    if (uInput && uInput <= max) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, helperMsg);
        return false;
    }
}

// min price
function minprice(elemid, min, helperMsg) {
    elem = $("#" + elemid);
    var uInput = elem.val();
    if (uInput && uInput >= min) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, helperMsg);
        return false;
    }
}

// Select box ( multi select)
function madeSelection(elemid, helperMsg) {
    elem = $("#" + elemid);
    var i;
    for (i = 0; i < elem.options.length; i++) {
        if (elem.options[i].selected && elem.options[i].value != "") {
            return true;
        }
    }
    invalid(elemid, helperMsg);
    return false;
}

// Select checkbox
function madeCheckbox(elemid, helperMsg) {
    elem = $("#" + elemid);
    if (elem.is(":checked") == false) {
        alert(helperMsg);
        return false;
        invalid(elemid);
    } else {
        return true;
    }
}

/*
 *  For checkbox multi.
 *  Min 1 item is checked
 */
function checkMultiCheckbox(containerid, helperMsg) {
    fields = $("#" + containerid).find("input:checked");
    length_checked = fields.length;
    if (!length_checked) {
        invalid(elemid, helperMsg);
        return false; // The form will *not* submit
    }
    return true;
}

// Email
function emailValidator(elemid, helperMsg) {
    elem = $("#" + elemid);
    var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
    if (elem.val().match(emailExp)) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, helperMsg);
        return false;
    }
}

// Password and repassword
function checkMatchPass(helperMsg) {
    elem_value = $("#password").val();
    elem2_value = $("#re-password").val();

    if (elem_value != elem2_value) {
        invalid("re-password", helperMsg);
        return false;
    } else {
        valid("re-password");
        return true;
    }
}

function checkMatchPass_2(pass, repass, helperMsg) {
    elem_value = $("#" + pass).val();
    elem2_value = $("#" + repass).val();
    if (elem_value != elem2_value) {
        invalid(repass, helperMsg);
        return false;
    } else {
        valid(repass);
        return true;
    }
}

// Email and re-email

function checkMatchEmail(helperMsg) {
    elem_value = $("#email").val();
    elem2_value = $("#re-email").val();

    if (elem_value != elem2_value) {
        invalid("re-email", helperMsg);
        return false;
    } else {
        valid("re-email");
        return true;
    }
}

function CustomAlert() {
    this.render = function (dialog) {
        var winW = window.innerWidth;
        var winH = window.innerHeight;
        var dialogoverlay = document.getElementById("dialogoverlay");
        var dialogbox = document.getElementById("dialogbox");
        dialogoverlay.style.display = "block";
        dialogoverlay.style.height = winH + "px";
        dialogbox.style.left = winW / 2 - 320 * 0.5 + "px";
        dialogbox.style.top = "100px";
        dialogbox.style.display = "block";
        document.getElementById("dialogboxhead").innerHTML = "Thông báo:";
        document.getElementById("dialogboxbody").innerHTML = dialog;
        document.getElementById("dialogboxfoot").innerHTML =
            '<button onclick="Alert.ok()">OK</button>';
    };
    this.ok = function () {
        document.getElementById("dialogbox").style.display = "none";
        document.getElementById("dialogoverlay").style.display = "none";
    };
}
var Alert = new CustomAlert();

/*
 * Change border color where valid
 */
function valid(element, helperMsg = "", type = "") {
    $("#" + element).removeClass("redborder");
    //$("#" + element).parent().find('.label_error').prev().remove();
    $("#" + element)
        .parent()
        .find(".label_error")
        .remove();
    //$("#" + element).parent().find('.label_success').prev().remove();
    $("#" + element)
        .parent()
        .find(".label_success")
        .remove();
}
/*
 * Change border color where invalid
 */
function invalid(element, helperMsg = "", type = "") {
    //$("#" + element).parent().find('.label_error').prev().remove();
    $("#" + element)
        .parent()
        .find(".label_error")
        .remove();
    //$("#" + element).parent().find('.label_success').prev().remove();
    $("#" + element)
        .parent()
        .find(".label_success")
        .remove();
    $("<div class='label_error'>" + helperMsg + "</div>").insertAfter(
        $("#" + element)
            .parent()
            .children(":last")
    );
    $("#" + element).addClass("redborder");
    $("#" + element).focus();
    // $('html, body').animate({
    //     scrollTop: $('#' + element).position().top,
    // }, 'slow');
}
function checkSQLInjection(elemid, helperMsg) {
    const sqlInjectionRegex =
        /(\s*([\0\b\'\"\n\r\t\%\_\\]*\s*(((select\s*.+\s*from\s*.+)|(insert\s*.+\s*into\s*.+)|(update\s*.+\s*set\s*.+)|(delete\s*.+\s*from\s*.+)|(drop\s*.+)|(truncate\s*.+)|(alter\s*.+)|(exec\s*.+)|(\s*(all|any|not|and|between|in|like|or|some|contains|containsall|containskey)\s*.+[\=\>\<=\!\~]+.+)|(let\s+.+[\=]\s*.*)|(begin\s*.*\s*end)|(\s*[\/\*]+\s*.*\s*[\*\/]+)|(\s*(\-\-)\s*.*\s+)|(\s*(contains|containsall|containskey)\s+.*)))(\s*[\;]\s*)*)+)/i;

    elem = $("#" + elemid);
    let isValid = !sqlInjectionRegex.test(elem.val());
    console.log(isValid);
    if (!isValid) {
        invalid(elemid, helperMsg);
        elem.focus();
        return false;
    }

    valid(elemid);
    return true;
}

// Ví dụ sử dụng

/************* NEW FUNCTION : CHECK ALL, SUBMIT FORM ***********/
function checkAll(n, fldName, c) {
    if (!fldName) {
        fldName = "cb";
    }
    var f = document.fontForm;

    var n2 = 0;
    for (i = 0; i < n; i++) {
        cb = eval("f." + fldName + "" + i);
        if (cb) {
            cb.checked = c;
            n2++;
        }
    }
    if (c) {
        document.fontForm.boxchecked.value = n2;
    } else {
        document.fontForm.boxchecked.value = 0;
    }
}

function isChecked(isitchecked) {
    if (isitchecked == true) {
        document.fontForm.boxchecked.value++;
    } else {
        document.fontForm.boxchecked.value--;
    }
}

function checkSubmit(msg) {
    if (document.fontForm.boxchecked.value == 0) {
        alert(msg);
        return false;
    } else {
        return true;
    }
}

function submitform(pressbutton) {
    if (pressbutton) {
        document.fontForm.task.value = pressbutton;
    }
    if (typeof document.fontForm.onsubmit == "function") {
        document.fontForm.onsubmit();
    }
    document.fontForm.submit();
}

function submitform(pressbutton, msg) {
    if (msg) {
        if (confirm(msg)) {
            if (pressbutton) {
                document.fontForm.task.value = pressbutton;
            }
            if (typeof document.fontForm.onsubmit == "function") {
                document.fontForm.onsubmit();
            }
            document.fontForm.submit();
        }
    }
}

function remove_image(module, view, id, field, divNum) {
    if (confirm("Bạn chắc chắn muốn xóa ảnh này?")) {
        $.ajax({
            url:
                root +
                "index.php?module=" +
                module +
                "&view=" +
                view +
                "&raw=1&task=delete_image",
            type: "get",
            data: {
                field: field,
                id: id,
            },
            error: function () {
                alert("Lỗi xóa dữ liệu");
            },
            success: function () {
                $("#" + divNum).remove();
            },
        });
    } else {
        return false;
    }
}

function remove_file(module, view, id, field, divNum) {
    if (confirm("Bạn chắc chắn muốn file này?")) {
        $.ajax({
            url:
                root +
                "index.php?module=" +
                module +
                "&view=" +
                view +
                "&raw=1&task=delete_file",
            type: "get",
            data: {
                field: field,
                id: id,
            },
            error: function () {
                alert("Lỗi xóa dữ liệu");
            },
            success: function () {
                $("." + divNum).remove();
            },
        });
    } else {
        return false;
    }
}
//ít nhất 1 kí tự số
function minOneNumber(elemid, helperMsg) {
    elem = $("#" + elemid);
    var numericExpression = /[0-9]/;
    if (elem.val().match(numericExpression)) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, helperMsg);
        return false;
    }
}
//ít nhất 1 kí tự in hoa
function minOneChar(elemid, helperMsg) {
    elem = $("#" + elemid);
    var numericExpression = /[A-Z]/;
    if (elem.val().match(numericExpression)) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, helperMsg);
        return false;
    }
}
//Chỉ có thể dùng các kí tự từ a-z
function onlyChar_a_z(elemid, helperMsg) {
    elem = $("#" + elemid);
    var numericExpression = /[~`!#$%\^&*+=[\]\\';,/{}|\\":<>\?]/;
    if (elem.val().match(numericExpression)) {
        valid(elemid);
        return true;
    } else {
        invalid(elemid, helperMsg);
        return false;
    }
}
