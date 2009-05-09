jQuery(document).ready(function() {
	if (jQuery('.commentlist .says').length>0 && sse_words.length>0) {
		jQuery('.commentlist .says').each(function() {
			var randomSays=Math.floor(sse_words.length*Math.random());
			var saysReplace=sse_words[randomSays]+':';
			saysReplace=saysReplace.replace(/\0/g, '0').replace(/\\([\\'"])/g, '$1');
			jQuery(this).text(saysReplace);
		});
	}
});
