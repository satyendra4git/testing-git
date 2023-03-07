<!-- custom popup using jquery -->
<style>
    .pop-outer {
    background-color: rgba(0, 0, 0, 0.5);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.pop-inner {
    background-color: #fff;
    width: 60%;
    height: 80%;
    padding: 25px;
    margin: 5% auto;
}
</style>
<!-- View popup button -->
<button data-id="1234" type="submit" class="btn view_button" id="">View</button>

<!-- view popup Start -->
<div style="display: none;" class="pop-outer">
    <div class="pop-inner">
        <button class="pop_close">X</button>
        <div class="card-body">
            <div class="row">
                <div class="col-6">Sr. no</div>
                <div class="col-6" id="rev_srno" class="form-control"></div>
            </div>
            <div class="row">
                <div class="col-6">Revisions</div>
                <div class="col-6" id="rev_revisions"class="form-control"></div>
            </div>
            <div class="row">
                <div class="col-6">Date</div>
                <div class="col-6" id="rev_date"class="form-control"></div>
            </div>
        </div>
    </div>
</div>
<!-- view popup end -->
<script>
    jQuery(function() {
    jQuery(".view_button").click(function (){
		jQuery(".pop-outer").fadeIn("slow");
		var dataID = jQuery(this).attr('data-id');
        /*jQuery.ajax({
            url: ajaxurl,
            method: 'post',
            data: {
                action: 'view_revision',
                recordId: dataID
            },
            success: function(resp){
              console.log('view revision success');
            }
        });*/
        //console.log('recordId '+dataID );
    });

    jQuery(".pop_close").click(function (){
        jQuery(".pop-outer").fadeOut("slow");  
    }); 
    
});
</script>