let index = 0; //will be changed with question id from the databse
$(document).ready(function () {
    var type_conditon = false;
    var question_valid = false;
    var mcq_valid = false;
    var tof_valid = false;
    var ans1_valid = false;
    var ans2_valid = false;
    var ans3_valid = false;
    var mcq_rightans_valid = false;

    $('ul.product-info li a').click(function (event) { //prevent link default action
        event.preventDefault();
        $(this).next('div').slideToggle(200);
    });

    $("#tor").hide(); //initial state
    $("#mcq").hide();
    $("#addquestiondiv").hide();
    $("#questionstable").hide();
    $("#editmcqdiv").hide();
    $("#edittofdiv").hide();

    $.fn.reset = function () { //reset for changing question type
        mcq_valid = false;
        tof_valid = false;
        ans1_valid = false;
        ans2_valid = false;
        ans3_valid = false;
        $('#mcqansb').html('Choose Ans');
        $('#tofbtn').html('Choose Ans');
    };

    $.fn.hardReset = function () { //reset after adding a question
        $.fn.reset();
        $('#typebtn').html('Choose type');
        $('#questioncontent').val("");
        $('#ans1').val("");
        $('#ans2').val("");
        $('#ans3').val("");
        $("#tor").hide();
        $("#mcq").hide();
    };

    $("#create_quiz_btn").click(function () { //create quiz btn
        var name = $('#quizname').val();
        if (name.length > 3) {

            $('#quizname').attr('readonly', true);
            $("#createbtn").hide();
            $("#addquestiondiv").show();
            $('#quizname').removeClass("alert-danger");
        } else {
            $('#quizname').addClass("alert-danger");
        }
    });

    $(".atype").click(function () { //choosing type action
        var type = $(this).html();
        $.fn.reset();
        mcq_rightans_valid = false;
        if (type == 'MCQ') {
            $('#typebtn').html(type);
            $("#tor").hide();
            $("#mcq").show();
        } else {
            $('#typebtn').html(type);
            $("#mcq").hide();
            $("#tor").show();
        }
    });

    $(".mcqans").click(function () { //mcq dropdown action
        var value = $(this).html();
        $('#mcqansb').html(value);
    });

    $(".upmcqans").click(function () { //edit mcq dropdown action
        var value = $(this).html();
        $('#upmcqansb').html(value);
    });

    $(".tofans").click(function () { //tof dropdown action
        var value = $(this).html();
        $('#tofbtn').html(value);
    });

    $(".uptofans").click(function () { //edit tof dropdown action
        var value = $(this).html();
        $('#uptofansb').html(value);
    });

    $.fn.update_mcq_btn = function (tr_id) { //update mcq fuction
        $("#upmcqbtn").click(function () {
            var question = $('#mcqquestionupdate').val();
            var ans1 = $('#firstmcqansup').val();
            var ans2 = $('#secondmcqansup').val();
            var ans3 = $('#thirdmcqansup').val();
            var right_ans = $('#upmcqansb').html();

            var question_valid = false;
            var ans1_valid = false;
            var ans2_valid = false;
            var ans3_valid = false;

            if (question.length > 4) {
                question_valid = true;
                $('#mcqquestionupdate').removeClass("alert-danger");
            } else {
                question_valid = false;
                $('#mcqquestionupdate').addClass("alert-danger");
            }

            if (ans1.length > 0) {
                ans1_valid = true;
                $('#firstmcqansup').removeClass("alert-danger");
            } else {
                ans1_valid = false;
                $('#firstmcqansup').addClass("alert-danger");
            }

            if (ans2.length > 0) {
                ans2_valid = true;
                $('#secondmcqansup').removeClass("alert-danger");
            } else {
                ans2_valid = false;
                $('#secondmcqansup').addClass("alert-danger");
            }

            if (ans3.length > 0) {
                ans3_valid = true;
                $('#thirdmcqansup').removeClass("alert-danger");
            } else {
                ans3_valid = false;
                $('#thirdmcqansup').addClass("alert-danger");
            }

            if (question_valid && ans1_valid && ans2_valid && ans3_valid) {
                $(tr_id).find("td:eq(0)").html(question);
                $(tr_id).find("td:eq(1)").html(ans1);
                $(tr_id).find("td:eq(2)").html(ans2);
                $(tr_id).find("td:eq(3)").html(ans3);
                $(tr_id).find("td:eq(4)").html(right_ans);
                $("#editmcqdiv").hide();
                $('html, body').animate({
                    scrollTop: $(tr_id).offset().top
                }, 2000);
            }
        });
    };

    $.fn.update_tof_btn = function (tr_id) { //update tof fuction
        $("#uptofbtn").click(function () {
            var question = $('#tofquestionupdate').val();
            var right_ans = $('#uptofansb').html();
            if (question.length > 4) {
                $(tr_id).find("td:eq(0)").html(question);
                $(tr_id).find("td:eq(4)").html(right_ans);
                $("#edittofdiv").hide();
                $('html, body').animate({
                    scrollTop: $(tr_id).offset().top
                }, 2000);
                $('#tofquestionupdate').removeClass("alert-danger");
            } else {
                $('#tofquestionupdate').addClass("alert-danger");
            }
        });
    };

    $.fn.removebtnlistener = function () { //table remove item listner initialiser
        $(".removebtn").click(function () {
            var id = $(this).attr('id');
            const myArr = id.split("_");
            var id_num = myArr[2];
            var tr_id = "#tr_" + id_num;
            $(tr_id).remove();
        });
    };

    $.fn.editbtnlistener = function () { //table edit item listner initialiser
        $(".editbtn").click(function () {
            var id = $(this).attr('id');
            const myArr = id.split("_");
            var id_num = myArr[2];
            var tr_id = "#tr_" + id_num;
            var btn_type = $(this).attr('id');
            btn_type = btn_type.split("_");
            btn_type = btn_type[0];
            if (btn_type == 'tof') {
                console.log("tofffffff");
                $('#tofquestionupdate').val($(tr_id).find("td:eq(0)").text());
                $('#uptofansb').html($(tr_id).find("td:eq(4)").text());
                $("#edittofdiv").show();
                $('html, body').animate({
                    scrollTop: $("#edittofdiv").offset().top
                }, 2000);
                $.fn.update_tof_btn(tr_id);
            } else {
                $('#mcqquestionupdate').val($(tr_id).find("td:eq(0)").text());
                $('#firstmcqansup').val($(tr_id).find("td:eq(1)").text());
                $('#secondmcqansup').val($(tr_id).find("td:eq(2)").text());
                $('#thirdmcqansup').val($(tr_id).find("td:eq(3)").text());
                $('#upmcqansb').html($(tr_id).find("td:eq(4)").text());
                $("#editmcqdiv").show();
                $('html, body').animate({
                    scrollTop: $("#editmcqdiv").offset().top
                }, 2000);
                $.fn.update_mcq_btn(tr_id);
            }

        });

    };

    $("#addqbtn").click(function () { //add btn fuction


        var type = $.trim($('#typebtn').html());
        var question = $('#questioncontent').val();
        var mcq_rightans_value = $.trim($('#mcqansb').html());
        var tof_rightans_value = $.trim($('#tofbtn').html());
        var ans1 = $('#ans1').val();
        var ans2 = $('#ans2').val();
        var ans3 = $('#ans3').val();

        if (type != 'Choose type') {
            type_conditon = true;
        }
        if (question.length < 5) {
            $('#questioncontent').addClass("alert-danger");
            question_valid = false;
        } else {
            $('#questioncontent').removeClass("alert-danger");
            question_valid = true;
        }

        if (ans1.length < 1) {
            $('#ans1').addClass("alert-danger");
            ans1_valid = false;
        } else {
            $('#ans1').removeClass("alert-danger");
            ans1_valid = true;
        }

        if (ans2.length < 1) {
            $('#ans2').addClass("alert-danger");
            ans2_valid = false;
        } else {
            $('#ans2').removeClass("alert-danger");
            ans2_valid = true;
        }

        if (ans3.length < 1) {
            $('#ans3').addClass("alert-danger");
            ans3_valid = false;
        } else {
            $('#ans3').removeClass("alert-danger");
            ans3_valid = true;
        }

        if (mcq_rightans_value == 'Choose Ans') {
            mcq_rightans_valid = false;
        } else {
            mcq_rightans_valid = true;
        }

        if (ans1_valid && ans2_valid && ans3_valid && mcq_rightans_valid) {
            mcq_valid = true;
        } else {
            mcq_valid = false;
        }

        if (tof_rightans_value == 'Choose Ans') {
            tof_alid = false;
        } else {
            tof_valid = true;
        }

        if (type_conditon && question_valid && (mcq_valid || tof_valid)) {

            var tr = `
            <tr id='tr_` + index + `'>
            <td>` + question + `</td>`;
            if (mcq_valid) {
                tr += `<td>` + ans1 + `</td>
                <td>` + ans2 + `</td>
                <td>` + ans3 + `</td>
                <td>` + mcq_rightans_value + `</td>`;
            } else {
                tr += `<td>True</td>
                <td>False</td>
                <td>NULL</td>
                <td>` + tof_rightans_value + `</td>`;
            }
            tr += `<td>`;
            if (mcq_valid) {
                tr += `
            <a class="btn btn-warning btn-icon-split editbtn" id="mcq_edit_` + index + `">`;
            } else {
                tr += `
                <a class="btn btn-warning btn-icon-split editbtn" id="tof_edit_` + index + `">`;
            }
            tr += `<span class="icon text-white-50">
            <i class="fas fa-info-circle"></i>
            </span>
            <span class="text " >Edit</span>
            </a>
            </td>
            <td>`;
            if (mcq_valid) {
                tr += `
            <a class="btn btn-danger btn-icon-split removebtn" id="mcq_remove_` + index + `">`;
            } else {
                tr += `
                <a class="btn btn-danger btn-icon-split removebtn" id="tof_remove_` + index + `">`;
            }
            tr += `<span class="icon text-white-50">
            <i class="fas fa-trash"></i>
            </span>
            <span class="text">Remove</span>
            </a>
            </td>
            </tr>`;
            jQuery("#questionsTable").append(tr);
            $("#questionstable").show();
            $.fn.hardReset();
            index++;
        }
        $.fn.editbtnlistener();
        $.fn.removebtnlistener();

    });
});