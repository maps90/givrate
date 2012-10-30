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

Givrate.namespace('Givrate.Ratings');

Givrate.Ratings.star = function(ev) {
	var rateId = $(ev.currentTarget).attr('data-rate_id').replace(/^s/, '');
	var rating = $(ev.currentTarget).attr('data-rating').replace(/^s/, '');
	var userId = $(ev.currentTarget).attr('data-user_id').replace(/^s/, '');
	var alias = $(ev.currentTarget).attr('data-alias');
	var url = Croogo.basePath + 'givrate/ratings/submit.json';
	$.post(url, { rate_id: rateId, rating: rating, user_id: userId, alias: alias }, function(data) {
		if (data == true) {
			var replacing = '<span class="rated">Rated</span>';
			$('ul.rating').fadeTo(400, 0, function() {
				$(this).html(replacing).fadeTo(400, 1);
			});
		}
	});
	return false;
}
