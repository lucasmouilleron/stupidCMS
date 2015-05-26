$(function() {

    $("[data-toggle=\"tooltip\"]").tooltip(); 
	autosize($("textarea"));

	$(".content .submit, .page .submit").hide();
	$(".content textarea, .page textarea").on("change keyup paste", function() {
		$(this).parent().parent().find(".submit").show();
		$(this).css({"border-color":"red"});
	});

	$(".addPage .next").hide();
	$(".addPage #name").on("change keyup paste", function() {
		$(".addPage .next").show();
	});

	$(".file .submit").hide();
	$(".file input:file").change(function(e){
		var $file = $(this).parent().parent().find("img");
		if (e.target.files && e.target.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$file.attr("src", e.target.result);
			};
			reader.readAsDataURL(e.target.files[0]);
		}
		$file.css({"opacity":"0.3"});
		$(this).parent().parent().find(".submit").show();
	});

	$(".preview-modal").click(function() {
		$("#preview-modal-content").html();
		var converter = new Markdown.Converter();
		$("#preview-modal-content").html(converter.makeHtml($(this).parent().find("textarea").val()));
		$("#preview-modal").modal({});
	});

	$(".addPage select").change(function() {
		var templateID = $(".addPage select").val();
		$.ajax({
			url: "get-template-content?template="+templateID
		}).fail(function(a,b) {
			console.log(a,b);
		}).done(function(data) {
			console.log(data);
			$(".addPage #content").val(data);
			autosize.update($(".addPage #content"));
			$(".addPage #content").trigger("change");
			
		});
	});
});