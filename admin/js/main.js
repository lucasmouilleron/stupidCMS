$(function() {

	autosize($("textarea"));

    $(".preview-modal").click(function() {
        $("#preview-modal-content").html();
        var converter = new Markdown.Converter();
        $("#preview-modal-content").html(converter.makeHtml($(this).parent().find("textarea").val()));
        $("#preview-modal").modal({});
    });
});