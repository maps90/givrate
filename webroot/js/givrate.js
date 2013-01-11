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
	var rating = $(ev.currentTarget).attr('data-rating').replace(/^s/, '');
	var token = $(ev.currentTarget).attr('data-token');
	var url = Croogo.basePath + 'rate/submit.json';
	$.post(url, { rating: rating, token: token}, function(data) {
		if (data == false) {
			alert('False');
		}

		if (data == true) {
			var current_val = $('.avg').text();
			var count_user = $('.stat.rate').text();
			var current_rate = ((parseInt(current_val) + parseInt(rating)) / (parseInt(count_user) + 1)) * 18;
			var now_val = ((parseInt(current_val) + parseInt(rating)) / (parseInt(count_user) + 1));
			$('ul.rating').css({'width': current_rate + 'px', 'background-position' : '0px 72px'});
			$('.avg span').text(Math.round(now_val).toFixed(1));
			$('.stars .rating li a').css({'display': 'none'});
			return false;
		}
	});
}
