function readyModal(button) {
    // handle click ajax for sending email - ugly
    var urpID = $(button).attr("data-urpid");
    var email = $(button).attr("data-email");
    //        var rating_info = $(this).parent("td").siblings("td").text();

    //        OLD WAY
    // var url = root_url + "/teds/ajax_service.php?" + "email=" + email + "&urpID=" + urpID + "&sendEmail=true";
    var url = "ajax_service.php?email=" + email + "&assessmentID=" + urpID + "&sendEmail=true";

    var res = email.match(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i);

    if (res) {
        $('#emailModal div.email_check')
            .html("")
            .append(email);
        $('#emailModal #email_sender_confirm').attr("data-url", url);
        $('#emailModal').modal();
    } else {
        Notice.init("The email address provided is invalid!", "notice_warning");
    }
}


$(function() {
//    var root_url = "http://localhost:90"; -- local dev
//    var root_url = "https://depts.washington.edu/tedsrate"; // -- remote env
    var root_url = "//depts.washington.edu/tedsrate"; // -- remote env




    $('.email_sender').click(function() {
        debugger;
        console.log("start sender");
        // handle click ajax for sending email - ugly
        var urpID = $(this).attr("data-urpid");
        var email = $(this).attr("data-email");
        //        var rating_info = $(this).parent("td").siblings("td").text();

        //        OLD WAY
        // var url = root_url + "/teds/ajax_service.php?" + "email=" + email + "&urpID=" + urpID + "&sendEmail=true";
        var url = "ajax_service.php?email=" + email + "&urpID=" + urpID + "&sendEmail=true";

//        $('#emailModal div.rating_info_check')
//            .html("")
//            .append(rating_info);

debugger;
		var res = email.match(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i);

		if (res) {
            $('#emailModal div.email_check')
                .html("")
                .append(email);
            $('#emailModal #email_sender_confirm').attr("data-url", url);
            $('#emailModal').modal();
        } else {
            Notice.init("The email address provided is invalid!", "notice_warning");
        }
    })

    $('#email_sender_confirm').click(function() {
        var url = $(this).attr("data-url");
        console.log(url);
        $.ajax({
            url:url
        })
        .done(function(data) {
//            console.log("server succeeded");
//            console.log(data[1]);
            data = jQuery.parseJSON(data);
            if (data && data[1]) {
                copyToClipboard(data[2]);
                Notice.init(data[1], data[0] ? "notice_success" : "notice_warning");
            } else {
                Notice.init("Server error! please try again!", "notice_warning");
            }
            $('#emailModal #email_sender_confirm').attr("data-url", null);
        })
        .fail(function() {
            // do sth with error
            console.log("server failed");
            Notice.init("Cannot connect to server, please try again later!", "notice_warning");
        });
    })

    // handle logout
    $('#logout').click(function() {
        var c = confirm("Please confirm: log out?");
        if (c) {
            $('#logout_form').submit();
        }
    })
});

function copyToClipboard(text) {
    window.prompt("Copy to clipboard: Ctrl/Command+C, Enter", text);
}
