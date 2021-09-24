$(document).ready(function () {
    console.log('ok');
    var type_conditon = false;
    var question_valid = false;
    var mcq_valid = false;
    var tof_valid = false;
    var ans1_valid = false;
    var ans2_valid = false;
    var ans3_valid = false;
    var mcq_rightans_valid = false;

    $('ul.product-info li a').click(function (event) {
        event.preventDefault();
        $(this).next('div').slideToggle(200);
    });

    $("#tor").hide();
    $("#mcq").hide();
    $("#addquestiondiv").hide();
    $("#questionstable").hide();

    $.fn.reset = function() {
        mcq_valid = false;
        tof_valid = false;
        ans1_valid = false;
        ans2_valid = false;
        ans3_valid = false;
        $('#mcqansb').html('Choose Ans');
        $('#tofbtn').html('Choose Ans');
    };

    $("#create_quiz_btn").click(function () {
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

    $(".atype").click(function () {
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

    $(".mcqans").click(function () {
        var value = $(this).html();
        $('#mcqansb').html(value);
    });

    $(".tofans").click(function () {
        var value = $(this).html();
        $('#tofbtn').html(value);
    });

    $("#addqbtn").click(function () {


        var type = $.trim($('#typebtn').html());
        var question = $('#questioncontent').val();
        var mcq_rightans_value = $.trim($('#mcqansb').html());
        var tof_rightans_value = $.trim($('#tofbtn').html());
        var ans1 = $('#ans1').val();
        var ans2 = $('#ans2').val();
        var ans3 = $('#ans3').val();

        // console.log(mac);
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
            $("#questionstable").show();
        }
    });
});