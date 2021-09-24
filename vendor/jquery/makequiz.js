$(document).ready(function () {

    $('ul.product-info li a').click(function (event) {
        event.preventDefault();
        $(this).next('div').slideToggle(200);
    });

    $("#tor").hide();
    $("#mcq").hide();
    $("#addquestiondiv").hide();

    $("#create_quiz_btn").click(function () {
        var name = $('#quizname').val();
        if (name.length > 3) {

            $('#quizname').attr('readonly', true);
            $("#createbtn").hide();
            $("#addquestiondiv").show();
            $('#quizname').removeClass("alert-danger");
        }else{
            $('#quizname').addClass("alert-danger");
        }
    });

    $(".atype").click(function () {
        var type = $(this).html();
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
});