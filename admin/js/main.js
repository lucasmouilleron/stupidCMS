$(function() {

	autosize($("textarea"));

	$(".content .submit").hide();
	$(".content textarea").keyup(function(){
		$(this).parent().parent().find(".submit").show();
	});

	$(".image .submit").hide();
	$(".image input:file").change(function(){
		$(this).parent().parent().find(".submit").show();
	});

    $(".preview-modal").click(function() {
        $("#preview-modal-content").html();
        var converter = new Markdown.Converter();
        $("#preview-modal-content").html(converter.makeHtml($(this).parent().find("textarea").val()));
        $("#preview-modal").modal({});
    });
});