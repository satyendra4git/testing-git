 function displayRecords(numRecords, pageNum) {

 				jQuery.urlParam = function (name) {
                         var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                         if(results){
                         	return results[1] || 0;
                         } else {	
                         //console.log('it is null');
                    		 }
                         
                    }
                   			var club_id = jQuery.urlParam('genre');		
                                jQuery.ajax({
                                    type: "GET",
                                    url: "http://localhost/stashedaway/wp-content/plugins/solicitors/getrecords.php",
                                    data: "show=" + numRecords + "&pagenum=" + pageNum + "&club_id=" + club_id ,
                                    cache: false,
                                    beforeSend: function() {
                                    	jQuery('.loader').html('<img src="http://localhost/stashedaway/wp-content/plugins/solicitors/loader.gif" alt="" width="24" height="24" style="padding-left: 400px; margin-top:10px;" >');
                                    },
                                    success: function(html) {
                                    
                                        jQuery("#results").html(html);
                                        jQuery('.loader').html('');
                                    }
                                });
                            }

                            function changeDisplayRowCount(numRecords) {
                                displayRecords(numRecords, 1);
                            }

                            jQuery(document).ready(function() {
                                displayRecords(10, 1);
                            });

  jQuery( function() {

					jQuery(".editbutton").live('click', function(){

									var currentRow=jQuery(this).closest("tr"); 
									var current_row_id=currentRow.find("td:eq(1)").text();
								
									jQuery("#barcodeValue" + current_row_id).css("display","block");
									jQuery("#datepicker" + current_row_id).datepicker();
									jQuery("#datepicker1" + current_row_id).datepicker();

							        
							        var col13=currentRow.find("td:eq(13)").css("display","none");
							 		var col14=currentRow.find("td:eq(14),.actual_date").css("display","block");

							 		var test = currentRow.find("td:eq(14),.actual_date" + current_row_id).text(); 
									
									var col13=currentRow.find("td:eq(16)").css("display","none");
							 		var col14=currentRow.find("td:eq(17),.outdate").css("display","block");
								 	
								 	currentRow.find("#barcodeValue"+ current_row_id).css("display","block");

									currentRow.find(".editbutton").css("display","none");
						      		currentRow.find(".updatebutton").css("display","block");

									var indate = currentRow.find("td:eq(13)").text();

									var test = currentRow.find("#barcodeTarget" + current_row_id).text();

									 var out = currentRow.find("#updated_date_out" + current_row_id).val();
									 var actual_in = currentRow.find("#updated_date_in" + current_row_id).val();

									if(!(actual_in === "00/00/0000"))
									{
										
										currentRow.find("td:eq(13),.actual_date").css("display","block");
										currentRow.find("td:eq(14),.dateall ").css("display","none");

									}

									if(!(out === "00/00/0000"))
									{
										
										currentRow.find("td:eq(16),.out_date").css("display","block");
										currentRow.find("td:eq(17),.outdate").css("display","none");
									    currentRow.find("td:eq(18)").css("display","none");
									    currentRow.find("#barcodeValue" + current_row_id).css("display","none");

									}
									
									if(!(test === ""))
										{ currentRow.find("#barcodeTarget" + current_row_id).css("display","block"); }
									else
										{ currentRow.find("#barcodeValue" + current_row_id).css("display","block"); }

						
						jQuery("#datepicker" + current_row_id).change(function()
						{

									var dateObject =jQuery("#datepicker" + current_row_id).datepicker('getDate');
									var test=jQuery.datepicker.formatDate('d/m/yy', dateObject);
									
									currentRow.find("#updated_date_in"+ current_row_id).val(test);

						});

						
						jQuery("#datepicker1" + current_row_id).change(function()
						{

									var dateObject =jQuery("#datepicker1" + current_row_id).datepicker('getDate');
									var test1=jQuery.datepicker.formatDate('d/m/yy', dateObject);
									
									currentRow.find("#updated_date_out"+ current_row_id).val(test1);

						});
									



		});

/**************************************************************************/

		jQuery(".updatebutton").live('click', function()
		{
				
					    var currentRow=jQuery(this).closest("tr"); 

					    var current_row_id=currentRow.find("td:eq(1)").text();

					    var brvalue = jQuery("#barcodeValue"+ current_row_id).val();
					    	//alert(brvalue);
					    	if(brvalue === ""){

					    		brvalue = '';

					    	}

					    var updated_date_out = jQuery("#updated_date_out"+ current_row_id).val();
				 	
	 				    var updated_actual_date  = jQuery("#updated_date_in"+ current_row_id).val();

	 				    var dataString = 'updated_date_out='+ updated_date_out + '&updated_actual_date='+ updated_actual_date + '&current_user_id='+ current_row_id + '&barcode_value='+ brvalue ;

	                    jQuery.ajax({ 
		                        type: "POST",
		                        url:  'http://localhost/stashedaway/wp-content/plugins/solicitors/solicitors_update.php',
		                        data: dataString,
		                         success: function(response)
		                          {
		                          
		                            if(response > 0)
		                           	 {
										
										currentRow.find("td:eq(13)").text(updated_actual_date);
		      							currentRow.find("td:eq(16)").text(updated_date_out);
		      							var col14=currentRow.find("td:eq(2)").text(brvalue);

		      							var col13=currentRow.find("td:eq(13)").css("display","block");
								 		var col14=currentRow.find("td:eq(14),.indate").css("display","none");
										var col13=currentRow.find("td:eq(16)").css("display","block");
								 		var col14=currentRow.find("td:eq(17),.outdate").css("display","none");
								 		currentRow.find("#barcodeValue" + current_row_id).css("display","none");

								 		
										currentRow.find(".editbutton").css("display","block");
							      		currentRow.find(".updatebutton").css("display","none");

							      		 var out = currentRow.find("#updated_date_out" + current_row_id).val();

			 				     				if(!(out === "00/00/0000"))
											{
												currentRow.find("td:eq(16),.out_date").css("display","block");
												currentRow.find("td:eq(17),.outdate").css("display","none");
											    currentRow.find("td:eq(18)").css("display","none");
											    currentRow.find("#barcodeValue" + current_row_id).css("display","none");

												
											}
									}

		                         },
		                          error: function()
		                          {
		                             alert('something went wrong.')
		                         }
	                });	
			
			});


/**************************************************************************/

			  jQuery('.all_search_admin').live('change', function () {

			        jQuery.urlParam = function (name) {
                         var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                         return results[1] || 0;
                    }   
					   var club_id = jQuery.urlParam('genre');	
			           var ref_number = jQuery(".ref_num").val();
			           var user_name = jQuery(".user_name").val();
			           var user_branch = jQuery(".user_branch").val();
			           var entered = jQuery(".entered_by").val();
			          
							   var dataString = 'ref_number='+ ref_number + '&user_name=' + user_name + '&branch=' + user_branch + '&entered=' + entered + "&club_id=" + club_id;

			                    jQuery.ajax({ 
			                        type: "POST",
			                        url: 'http://localhost/stashedaway/wp-content/plugins/solicitors/getrecords.php',
			                        data: dataString,
			                        success: function(html){
			                        
			                        jQuery("#results").html(html);
			                     
			                      }
			                });

			   });


		jQuery(".clearall").on('click', function(){
						
					   var ref_number = jQuery(".ref_num").val("");
			           var user_name = jQuery(".user_name").val("");
			           var user_branch = jQuery(".user_branch").val("");
			           var entered = jQuery(".entered_by").val("");

			            window.location.reload(true);

		});


		jQuery(".seeall").on('click', function(){




		});




});

       


				 


