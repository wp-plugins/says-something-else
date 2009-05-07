jQuery(document).ready(function() {
	if (jQuery('.commentlist .says').length>0 && sse_words.length>0) {
		jQuery('.commentlist .says').each(function() {
			var randomSays=Math.floor(sse_words.length*Math.random());
			jQuery(this).text(sse_words[randomSays]+':');
		});
	}
});
