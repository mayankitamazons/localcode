console.log(CurrencyWidgetDefaultTab);
console.log(CurrencyWidgetDefaultCurrency);
console.log(CurrencyWidgetDefaultProduct);

if (CurrencyWidgetDefaultTab == null)
	CurrencyWidgetDefaultTab = "Default";

if (CurrencyWidgetDefaultCurrency == null)
	CurrencyWidgetDefaultCurrency = "Default";

if (CurrencyWidgetDefaultProduct == null)
	CurrencyWidgetDefaultProduct = "Default";

var countselect=0;
	

var AllDefaults = CurrencyWidgetDefaultTab == "Default" && CurrencyWidgetDefaultCurrency == "Default" && CurrencyWidgetDefaultProduct == "Default";
console.log(AllDefaults);

$(window).ready(function () {
	var count = 0;
	var countclk = 0;
	
	$('.buycurrency .autocomplete-holder').css("background", "url(/media/5333/rendered.png) #FFF 98% no-repeat");

	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
		var settingwidget = setInterval(function () {
				myTimer()
			}, 500);

		function myTimer() {
			if ($("#ui-id-2").has("li").length > 0) {
				$('#ui-id-1,#ui-id-2,#ui-id-3').innerWidth($('input[name="currency"]').innerWidth());
				clearInterval(settingwidget);
			}
		}

	}
    

	if (/Android|webOS|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
		$('.autocomplete-holder').on('click', function (e) {
			var pWidth = $(this).innerWidth(); //use .outerWidth() if you want borders
			var pOffset = $(this).offset();
			var x = e.pageX - pOffset.left;

			widgetchange(pWidth, x);
		});

	} else if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
		$(document).on('touchstart', '.autocomplete-holder', function (e) {

			var pWidth = $(this).innerWidth(); //use .outerWidth() if you want borders
			var pOffset = $(this).offset();
			var x = e.originalEvent.touches[0].pageX - pOffset.left;

			widgetchange(pWidth, x);

		});

		$(document).on('focus', '#x-buy-amount', function (e) {
			$('.autocomplete-holder .autocomplete.ui-autocomplete-input').prop('readonly', false);
		});

	} else {
		$('.autocomplete-holder').on('click', function (e) {
			var pWidth = $(this).innerWidth(); //use .outerWidth() if you want borders
			var pOffset = $(this).offset();
			var x = e.pageX - pOffset.left;
			widgetchange(pWidth, x);

		});
	}

	function widgetchange(pWidth, e) {

		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
			$('#ui-id-1,#ui-id-2,#ui-id-3').innerWidth($('input[name="currency"]').innerWidth());

		}

		if (pWidth / 1.1 > e) {

			if (countclk == 0) {
				$('.autocomplete-holder .autocomplete.ui-autocomplete-input').prop('readonly', true);
				countclk = 1;
				if(countselect==0){
				$('input[name="currency"]').addClass('noselect');
				countselect=1;
				}
				else{$(".autocomplete-holder .autocomplete.ui-autocomplete-input").prop("readonly", false);}
			} else {

				if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
					$('input[name="currency"]').removeClass('noselect');
					$(".autocomplete-holder .autocomplete.ui-autocomplete-input").blur();
					$(".autocomplete-holder .autocomplete.ui-autocomplete-input").prop("readonly", false);
					$(".autocomplete-holder .autocomplete.ui-autocomplete-input").focus();
					$('#ui-id-1,#ui-id-2,#ui-id-3').innerWidth($('input[name="currency"]').innerWidth());

				} else {

					$('.autocomplete-holder .autocomplete.ui-autocomplete-input').prop('readonly', false);
				}

			}

			$('#ui-id-1,#ui-id-2').css('display', 'block');
			$('.buycurrency .autocomplete-holder').css("background", "url(/media/5332/original_439412773.png) #FFF 98% no-repeat");
			count = 1;

		} else {

			if ($("#ui-id-1,#ui-id-2").css("display") == "none" || count == 0) {
				$('.buycurrency .autocomplete-holder').css("background", "url(/media/5332/original_439412773.png) #FFF 98% no-repeat");
				$('input[name="currency"]').addClass('noselect');
				countclk = 1;
				$('#ui-id-1,#ui-id-2').css('display', 'block');

				count = 1;
				$('.autocomplete-holder .autocomplete.ui-autocomplete-input').prop('readonly', true);

			} else {
                       
				$('#ui-id-1,#ui-id-2').css('display', 'none');
				$('.buycurrency .autocomplete-holder').css("background", "url(/media/5333/rendered.png) #FFF 98% no-repeat");
				$('.autocomplete-holder .autocomplete.ui-autocomplete-input').prop('readonly', true);
countselect=0;
				countclk = 0;

			}

		}

	}

	$('input[name="currency"]').on('focusout', function (e) {

		$('#ui-id-1,#ui-id-2').css('display', 'none');
		$('.buycurrency .autocomplete-holder').css("background", "url(/media/5333/rendered.png) #FFF 98% no-repeat");
		$('.autocomplete-holder .autocomplete.ui-autocomplete-input').prop('readonly', false);

		count = 0;
		countclk = 0;
	});

});

$(window).load(function () {
	if (AllDefaults)
		return;
	$('section .eighteen-col.add-more-currency-widget.main-blue.no-items.right-to-top').show();

/*Set Tab*/
if (CurrencyWidgetDefaultTab != "Default") {
	try {
		if ($('h2.rp-reload')[0].style.display != "none") {
			settab();
		}
	} catch (err) {
		settab();
	}
}

/*Set Product*/
if (CurrencyWidgetDefaultProduct != "Default") {
	setTimeout(SetProduct, 100);
}

/*Set Currency*/
if (CurrencyWidgetDefaultCurrency != "Default") {
	$('div.autocomplete-holder').children('input.autocomplete.ui-autocomplete-input').focus();
	$('div.autocomplete-holder').children('input.autocomplete.ui-autocomplete-input').blur();
	setTimeout(setCurrency, 100);
}

});
function settab() {

	if (CurrencyWidgetDefaultTab == "Currency") {
		$('h2.rp-buy-cur')[0].classList.add("active");
		$('h2.rp-reload')[0].classList.remove("active");
		$('a[href="#buycurrency"]').trigger('click');
	} else if (CurrencyWidgetDefaultTab == "Reload Cash Passport") {
		$('h2.rp-reload')[0].classList.add("active");
		$('h2.rp-buy-cur')[0].classList.remove("active");
		$('a[href="#cashpassport"]').trigger('click');
	}
}

function setCurrency() {
var settingdefaultcurrency = setInterval(function () {
				defaultcurrencyTimer()
			}, 100);

		function defaultcurrencyTimer() {
			if ($('.buycurrency').css('display')=="block") {
				$('div.autocomplete-holder').children('input.autocomplete.ui-autocomplete-input').focus();
	$('div.autocomplete-holder').children('input.autocomplete.ui-autocomplete-input').blur();
	WidgetDefaultCurrency = CurrencyWidgetDefaultCurrency.substring(0, 2);
	console.log("----------" + $('li.ui-menu-item').length + "----------------");

				$('li.ui-menu-item').find('span.' + WidgetDefaultCurrency).first().parent().parent().click();

				clearInterval(settingdefaultcurrency);
			}
		}
}

function SetProduct() {
	//Cash or Cash-passport button
	if (CurrencyWidgetDefaultProduct == "Cash") {
		$('#x-cash').trigger('click');
	} else if (CurrencyWidgetDefaultProduct == "Cash Passport") {
		$('#x-cash-passport').trigger('click');
	}
}