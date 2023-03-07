jQuery( function(){
	// filter ajax
	jQuery('.ctafilters_select').on('change', function(e){
		e.preventDefault();
		//console.log('jhgjgjg'); 
		var selectVal = jQuery(this).val();
		//console.log(selectVal);
		  jQuery('#cta-reset-filter').show();
		
		
		var formData = jQuery('#ctafilters').serialize();
		//console.log(formData);
		//console.log(ajax_url);
		
		jQuery.get({
           url: ajax_url,
           data: {
             action: 'cta_filters_ajax',
             formdata: formData
           },
		  
		  beforeSend:function(){  
				  $('#cta-loader').show();  
			 },  
           success: function(resp) {
             $('#cta-loader').hide();
			 //console.log(resp);
             jQuery('.cta-classes-list').html(resp);
           }
         })
		
	});
	//load more ajax
	jQuery('.load-more-classes-home').on('click', function(e){
		e.preventDefault();
		//console.log('load more clicked..');
		var $this = jQuery(this);
       $this.find('.loadmore-loader-img').show();
	   var formData = jQuery('#ctafilters').serialize();
	   if (!jQuery(this).hasClass('loading')) {
		  jQuery(this).addClass('loading');
           var curLength = jQuery('.classesgrid').length;
           //console.log(curLength);
         jQuery.get({
           url: ajax_url,
           data: {
             action: 'cta_loadmore_ajax',
             offset: curLength,
             formdata: formData
           },
           success: function(resp) {
			   //console.log(resp);
             $this.find('.loadmore-loader-img').hide();
             $this.removeClass('loading');
             jQuery('.cta-classes-list').append(resp);
           }
         })		   
	   }
	});
	//reset form Button
	jQuery('#cta-reset-filter').on('click', function(e){
		e.preventDefault();
		jQuery('#ctafilters').trigger("reset");
		//console.log('reset clicked');
		
	});
});