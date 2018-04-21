$(function () {

    /////////////////////////////////////////////////////////
    // helpers
    /////////////////////////////////////////////////////////
    function getFromDict(dict, key, defaultValue) {
        if (key in dict) {return dict[key];}
        else {return defaultValue;}
    }

    /////////////////////////////////////////////////////////////////////////////
    // Miscs
    /////////////////////////////////////////////////////////////////////////////
    // tocs
    $(window).load(function () {
        $("#toc").toc({
            "selectors": "h2",
            "container": "body",
            "smoothScrolling": true,
            "prefix": "toc",
            "highlightOnScroll": true,
            "highlightOffset": 80
        });
        $("span[id^=toc]").addClass("toc-anchor");
        $("#toc-toggle").click(function () {
            if ($("#toc").is(":visible")) {
                $("#toc").hide();
                $("#toc-toggle").html("&#x25B2;");
            }
            else {
                $("#toc").show();
                $("#toc-toggle").html("&#x25BC;");
            }
        });
        $("#sidebar").show();
    });

    // tooltips
    $("[data-toggle=\"tooltip\"]").tooltip();

    // textearea resize
    autosize($("textarea"));

    // scroll on request
    $(window).load(function () {
        if ($("#scroll").length) {
            $(window).scrollTop($("#scroll").attr("data-scroll"));
        }
    });

    // display non visible alerts
    $.fn.isInViewport = function () {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();
        return elementBottom > viewportTop && elementTop < viewportBottom;
    };
    $(window).load(function () {
        if ($(".alert").length) {
            $(".alert").each(function (index, value) {
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
    function saveContent(formElt) {
        var contentName = formElt.find("input[name='item']").val();
        var textAreaElt = formElt.find("textarea");
        var submitElt = textAreaElt.parent().parent().find(".submit");
        var submitPrevValue = submitElt.val();
        submitElt.val("Saving ...");
        submitElt.addClass("disabled");
        $.ajax({
            type: "POST",
            url: "/admin/admin-contents-save",
            data: {item: contentName, content: textAreaElt.val()},
            dataType: "json",
            success: function (data) {
                var success = getFromDict(data, "success", false);
                if (success) {
                    toastr.success("Content <b>" + contentName + "</b> saved");
                    textAreaElt.removeClass("save-needed");
                    submitElt.val(submitPrevValue);
                    submitElt.hide();
                    submitElt.removeClass("disabled");
                }
                else {
                    toastr.error("Can't save content <b>" + contentName + "</b>: " + getFromDict(data, "hint", "No hint"));
                    submitElt.val(submitPrevValue);
                    submitElt.removeClass("disabled");
                }
            },
            error: function (data) {
                toastr.error("Can't save content <b>" + contentName + "</b>: " + getFromDict(data, "hint", "No hint"));
                submitElt.val(submitPrevValue);
                submitElt.removeClass("disabled");
            }
        });
    }

    // show save needed
    $(".content .submit, .page .submit").hide();
    $(".content textarea, .page textarea").on("change keydown paste", function (e) {
        if (e.ctrlKey || e.keyCode === 37 || e.keyCode === 38 || e.keyCode === 39 || e.keyCode === 40) {return;}
        $(this).parent().parent().find(".submit").show();
        $(this).addClass("save-needed");
    });
    // save hooks
    $(".content textarea").keydown(function (e) {
        if (e.ctrlKey && e.keyCode == 13) {
            saveContent($(e.target).parent().parent());
            e.preventDefault();
        }
    });
    $(".content .submit").click(function (e) {
        saveContent($(e.target).parent());
        e.preventDefault();
    });
    // preview pane
    $(".preview-modal").click(function () {
        $("#preview-modal-content").html();
        var converter = new Markdown.Converter();
        $("#preview-modal-content").html(converter.makeHtml($(this).parent().find("textarea").val()));
        $("#preview-modal").modal({});
    });

    /////////////////////////////////////////////////////////////////////////////
    // Pages management
    /////////////////////////////////////////////////////////////////////////////
    // show save needed
    $(".addPage .next").hide();
    $(".page textarea").on("change keydown paste", function (e) {
        if (e.ctrlKey || e.keyCode === 37 || e.keyCode === 38 || e.keyCode === 39 || e.keyCode === 40) {return;}
        $(this).parent().parent().find(".submit").show();
        $(this).addClass("save-needed");
    });
    $(".addPage #name").on("change keyup paste", function () {
        $(".addPage .next").show();
    });
    // add page pane
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
    // show save needed
    $(".file .submit").hide();
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
    // save url update
    $(".file button[type='submit'], .file input[type='submit']").click(function () {
        var u = new Url;
        u.hash = $(this).parent().parent().find("a").attr("name");
        window.location = u.toString();
    });

});