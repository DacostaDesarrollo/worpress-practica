jQuery(document).ready(function($) {
	init();
	jQuery('.wh-widget-button-activator').on('click',function(event) {
		event.preventDefault();

		if (jQuery('#wh-widget-send-button-wrapper-list').hasClass('wh-widget-activate')) {
			closeWidget();
		}else{
			showWidget();
		}
	});
	jQuery('body').on('click','.title-bar-icon-close',function(event) {
		event.preventDefault();
		jQuery('#popup-placement,#popup-placement-form').css('display','none');
	});
	//Identificamos la acción del usuario
	jQuery('#wh-widget-send-button-wrapper-list a').on('click',function(event) {
		event.preventDefault();
		var $this = jQuery(this);
		var accion = jQuery(this).data('action');
		switch(accion) {
		  	case 'facebook':
		    	// code block
		    	facebook();
		    	break;
		  	case 'whatsapp':
			    // code block
			    whatApp($this);
			    break;
		  	case 'phone':
			    // code block
			    phone($this);
			    break;
			case 'email':
			    // code block
			    email($this);
			    break;
		  default:
		    	// code block

		}
	});

});

function init() {
	// body...
	//FB.CustomerChat.hide();
}

function showWidget() {
	FB.CustomerChat.hideDialog();
	jQuery('#popup-placement,#popup-placement-form').css('display', 'none');
	jQuery('#wh-call-to-action').addClass('wh-animation-out wh-hide');
	jQuery('#wh-widget-send-button-wrapper-list').addClass('wh-widget-show-get-button wh-widget-activator-as-close wh-widget-activate')
	jQuery('#wh-widget-send-button-wrapper-list').find('a').removeClass('button-slide-out');
	jQuery('#wh-widget-send-button-wrapper-list').find('a').addClass('button-slide');
}

function closeWidget() {
	jQuery('#wh-call-to-action').removeClass('wh-animation-in');
	jQuery('#wh-widget-send-button-wrapper-list').removeClass('wh-widget-show-get-button wh-widget-activator-as-close wh-widget-activate')
	jQuery('#wh-widget-send-button-wrapper-list').find('a').addClass('button-slide');
	jQuery('#wh-widget-send-button-wrapper-list').find('a').removeClass('button-slide-out');
}

function facebook() {


    //jQuery('#popup-placement').html(plantilla);
    //jQuery('#wh-widget-send-button-wrapper-list').removeClass('wh-widget-activate')
    closeWidget();
    FB.CustomerChat.showDialog();
    //jQuery('#popup-placement').css('display', 'block');



}

function whatApp($this) {
	var numero = $this.data('number');
	if (!mobilecheck()) {
		window.open("https://web.whatsapp.com/send?phone=" + numero.replace(/\D+/g, ""),'_blank');

	}else{
		window.open("https://wa.me/" + numero.replace(/\D+/g, ""),'_blank');
	}
}

function phone($this) {
	var numero = $this.data('number');
	if (!mobilecheck()) {
		//window.open("https://wa.me/" + numero.replace(/\D+/g, ""),'_blank');
		var plantilla = `<div class="wh-widget-hello-popup-wrapper wh-popup wh-popup-right">
        <div class="wh-popup-title-bar wh-messenger-bg-call" wh-class="bgColorMessenger">
            <div class="title-bar-icon-close" wh-click="closePopup">
                <svg fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
                    <path d="M0 0h24v24H0z" fill="none"></path>
                </svg>
            </div>
            <div class="title-bar-icon-messenger" wh-html="icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-72 -72 704 704" class="wh-messenger-svg-call"><path d=" M166.156,512h-41.531c-7.375-0.031-20.563-8.563-20.938-8.906C37.438,437.969,0,348.906,0,255.938 C0,162.719,37.656,73.375,104.281,8.219C104.313,8.188,117.25,0,124.625,0h41.531c15.219,0,33.25,11.125,40.063,24.781l2.906,5.844 c6.781,13.594,6.188,35.563-1.344,48.75l-27.906,48.813c-7.563,13.219-26.188,24.25-41.406,24.25H110.75 c-36.813,64-36.813,143.094-0.031,207.125h27.75c15.219,0,33.844,11.031,41.406,24.25l27.875,48.813 c7.531,13.188,8.156,35.094,1.375,48.781l-2.906,5.844C199.375,500.844,181.375,512,166.156,512z M512,128v256 c0,35.344-28.656,64-64,64H244.688c-1.25-11.219-3.969-22.156-9.156-31.25l-27.875-48.813 c-13.406-23.406-42.469-40.375-69.188-40.375h-8.156c-20.188-45.438-20.188-97.719,0-143.125h8.156 c26.719,0,55.781-16.969,69.188-40.375l27.906-48.813c5.188-9.094,7.906-20.063,9.156-31.25H448C483.344,64,512,92.656,512,128z M328,368c0-13.25-10.75-24-24-24s-24,10.75-24,24s10.75,24,24,24S328,381.25,328,368z M328,304c0-13.25-10.75-24-24-24 s-24,10.75-24,24s10.75,24,24,24S328,317.25,328,304z M328,240c0-13.25-10.75-24-24-24s-24,10.75-24,24s10.75,24,24,24 S328,253.25,328,240z M392,368c0-13.25-10.75-24-24-24s-24,10.75-24,24s10.75,24,24,24S392,381.25,392,368z M392,304 c0-13.25-10.75-24-24-24s-24,10.75-24,24s10.75,24,24,24S392,317.25,392,304z M392,240c0-13.25-10.75-24-24-24s-24,10.75-24,24 s10.75,24,24,24S392,253.25,392,240z M456,368c0-13.25-10.75-24-24-24s-24,10.75-24,24s10.75,24,24,24S456,381.25,456,368z M456,304 c0-13.25-10.75-24-24-24s-24,10.75-24,24s10.75,24,24,24S456,317.25,456,304z M456,240c0-13.25-10.75-24-24-24s-24,10.75-24,24 s10.75,24,24,24S456,253.25,456,240z M456,144c0-8.844-7.156-16-16-16H296c-8.844,0-16,7.156-16,16v32c0,8.844,7.156,16,16,16h144 c8.844,0,16-7.156,16-16V144z" fill-rule="evenodd"></path></svg></div>
            <div class="title-bar-text" wh-html-unsafe="title">Teléfono</div>
            <div class="wh-clear"></div>
	        </div>
	        <div class="wh-popup-content wh-popup-content-call"><div>
	        <div class="content-call-number" wh-html-unsafe="callPhone">${numero}</div>
	    </div></div>
	        <div class="wh-clear"></div>

	        <div class="wh-widget-hello-popup-powered wh-hide" wh-class="pwdByClass">
	            <a href="//whatshelp.io/widget/?utm_campaign=multy_widget&amp;utm_medium=widget&amp;utm_source=expansionlocales.com" target="_blank">
	                Powered by&nbsp;<span>WhatsHelp</span>
	            </a>
	        </div>
	        <div class="wh-clear"></div>
	    </div>`;
	    jQuery('#popup-placement').html(plantilla);
	    jQuery('#wh-widget-send-button-wrapper-list').removeClass('wh-widget-activate')
	    closeWidget();
	    jQuery('#popup-placement').css('display', 'block');

	}else{
		window.open("tel:"+numero,'_blank');

	}
}
function email($this) {
	// body...
    jQuery('#wh-widget-send-button-wrapper-list').removeClass('wh-widget-activate');
    closeWidget();
    jQuery('#popup-placement-form').css('display', 'block');
}

function mobilecheck() {
  var check = false;
  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
  return check;
};