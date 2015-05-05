$(function() {

	autosize($("textarea"));

	$(".content .submit").hide();
	$(".content textarea").keyup(function(){
		$(this).parent().parent().find(".submit").show();
		$(this).css({"border-color":"red"});
	});

	$(".image .submit").hide();
	$(".image input:file").change(function(e){
		var $image = $(this).parent().parent().find("img");
		if (e.target.files && e.target.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$image.attr("src", e.target.result);
			};
			reader.readAsDataURL(e.target.files[0]);
		}
		$image.css({"opacity":"0.3"});
		$(this).parent().parent().find(".submit").show();
	});

	$(".preview-modal").click(function() {
		$("#preview-modal-content").html();
		var converter = new Markdown.Converter();
		$("#preview-modal-content").html(converter.makeHtml($(this).parent().find("textarea").val()));
		$("#preview-modal").modal({});
	});
});