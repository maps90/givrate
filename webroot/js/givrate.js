if (typeof Givrate == "undefined") {
	var Givrate = {};

Givrate.namespace = function() {
	var a=arguments, o=null, i, j, d;
	for (i=0; i<a.length; i=i+1) {
		d=a[i].split(".");
		o=window;
		for (j=0; j<d.length; j=j+1) {
			o[d[j]]=o[d[j]] || {};
			o=o[d[j]];
		}
	}
	return o;
	}
}

var Givrate = function() {

	var startRate = function(el,rate) {
		var id = $('#rate-link'+el).attr('data-rate');
		var ratingId = rate;
		var userId = $('#rate-link'+el).attr('data-id');
		var alias = $('#rate-link'+el).attr('data-alias');
		var url = Croogo.basePath + 'give-rate/' + alias + '/' + id + '/' + ratingId + '/' + userId + '/getdata.json';
		$.getJSON(url, function(data) {
		});
	}

	return {
		startRate : startRate,
		init: function() { return this }
	}
}().init(this);
