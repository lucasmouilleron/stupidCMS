$(function () {

    /////////////////////////////////////////////////////////////////////////////
    // Miscs
    /////////////////////////////////////////////////////////////////////////////
    $.fn.isInViewport = function () {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();
        return elementBottom > viewportTop && elementTop < viewportBottom;
    };

    $("[data-toggle=\"tooltip\"]").tooltip();
    autosize($("textarea"));
    $(window).load(function () {
        if ($("#scroll").length) {
            $(window).scrollTop($("#scroll").attr("data-scroll"));
        }
        if ($(".alert").length) {
            $(".alert").each(function (index, value){
                var elt = $(this);
                var eltContent = elt.html();
                if (!elt.isInViewport()) {
                    if (elt.hasClass("alert-success")) {toastr.success(eltContent);}
                    else if (elt.hasClass("alert-danger")) {toastr.error(eltContent);}
                    else if (elt.hasClass("alert-warning")) {toastr.warning(eltContent);}
                    else {toastr.info(eltContent);}
                }
            });
        }
    });


    /////////////////////////////////////////////////////////////////////////////
    // Help
    /////////////////////////////////////////////////////////////////////////////
    $(".help a").each(function () {
        $(this).attr("target", "_new");
    });

    /////////////////////////////////////////////////////////////////////////////
    // Contents management
    /////////////////////////////////////////////////////////////////////////////
    $(".content .submit, .page .submit").hide();
    $(".content textarea, .page textarea").on("change keyup paste", function () {
        $(this).parent().parent().find(".submit").show();
        $(this).css({"border-color": "red"});
    });
    $(".content textarea, .page textarea").keydown(function (e) {
        if (e.ctrlKey && e.keyCode == 13) {
            var formElt = $(e.target).parent().parent();
            formElt.find("input.scroll").val($(window).scrollTop());
            formElt.find("input.submit").click();
            e.preventDefault();
        }
    });
    $(".content .btn").click(function () {
        var u = new Url;
        u.hash = $(this).parent().parent().find("a").attr("name");
        window.location = u.toString();
    });
    $(".preview-modal").click(function () {
        $("#preview-modal-content").html();
        var converter = new Markdown.Converter();
        $("#preview-modal-content").html(converter.makeHtml($(this).parent().find("textarea").val()));
        $("#preview-modal").modal({});
    });

    /////////////////////////////////////////////////////////////////////////////
    // Pages management
    /////////////////////////////////////////////////////////////////////////////
    $(".addPage .next").hide();
    $(".addPage #name").on("change keyup paste", function () {
        $(".addPage .next").show();
    });
    $(".addPage select").change(function () {
        var templateID = $(".addPage select").val();
        $.ajax({
            url: "get-template-content?template=" + templateID
        }).fail(function (a, b) {
            console.log(a, b);
        }).done(function (data) {
            console.log(data);
            $(".addPage #content").val(data);
            autosize.update($(".addPage #content"));
            $(".addPage #content").trigger("change");

        });
    });

    /////////////////////////////////////////////////////////////////////////////
    // Files management
    /////////////////////////////////////////////////////////////////////////////
    $(".file .submit").hide();
    $(".file button[type='submit'], .file input[type='submit']").click(function () {
        var u = new Url;
        u.hash = $(this).parent().parent().find("a").attr("name");
        window.location = u.toString();
    });
    $(".file input:file").change(function (e) {
        var $file = $(this).parent().parent().find("img");
        if (e.target.files && e.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $file.attr("src", e.target.result);
            };
            reader.readAsDataURL(e.target.files[0]);
        }
        $file.css({"opacity": "0.3"});
        $(this).parent().parent().find(".submit").show();
    });

});