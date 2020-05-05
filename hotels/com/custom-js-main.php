<script>
$(document).ready(function(){
    BookHotel = function(FormId){
        waitingDialog.show('Contacting server... Please wait',{headerText:'<?=$settings['SiteName']?> Notifications',headerSize: 6,dialogSize:'sm'});
        var dataString = $("form[name=" + FormId + "]").serialize();
        $.ajax({
            type: 'post',
            url: '<?=APP_AJAX_RT?>?activity=reserve-htl-rm',
            data: dataString,
            success: function(res){
                var rtn = JSON.parse(res);
                if(rtn.hasOwnProperty("MSG")){
                    console.log(rtn.MSG);
                    waitingDialog.hide();
                    ShowToast('Success',rtn.MSG, 'success');
                    return;
                }
                if(rtn.hasOwnProperty("ERR")){
                    console.log(rtn.ERR);
                    waitingDialog.hide();
                    ShowToast('Error',rtn.ERR, 'error');
                    return;
                }
            }
            error: function(xhr, status, error){
                console.log(xhr);
            }
        });
    }
    function ShowToast(title, msg, icon){
        $.toast({
            heading: title,
            text: msg,
            position: 'top-right',
            stack: false,
            icon: icon,
            showHideTransition: 'fade'
        })
    }
    /*---slider-range here---*/
    $( "#slider-range" ).slider({
        range: true,
        min: 0,
        max: <?=str_replace(',','',$util->Forex(2000000))?>,
        values: [ 0, <?=str_replace(',','',$util->Forex(2000000))?> ],
        slide: function( event, ui ) {
        $( "#amount" ).val( "<?=$_SESSION['cry'].' '?>" + ui.values[ 0 ] + " - <?=$_SESSION['cry'].' '?>" + ui.values[ 1 ] );
       }
    });
    $( "#amount" ).val( "<?=$_SESSION['cry'].' '?>" + $( "#slider-range" ).slider( "values", 0 ) +
       " - <?=$_SESSION['cry'].' '?>" + $( "#slider-range" ).slider( "values", 1 ) );

    StarProduct = function(item, rate){
        waitingDialog.show('Contacting server... Please wait',{headerText:'<?=$settings['SiteName']?> Notifications',headerSize: 6,dialogSize:'sm'});
        var comment = $('#review_comment').val();
        if( comment === ''){
            $('#gpwrty_err').text("Review comment field is empty!");
            $('#gpwrty_err').show(500);
            waitingDialog.hide();
            $('#gpwrty_err').hide(10000);
            return;
        }
        var data = "item="+item+"&r="+rate+"&c="+comment;
        $.ajax({
            type: 'post',
            url: '<?=APP_AJAX_RT?>?activity=review-item',
            data: data,
            success: function(res){
                // console.log(res);
                var rtn = JSON.parse(res);
                if(rtn.hasOwnProperty("MSG")){
                    $('#gpwrty_err').text('');
                    $('#gpwrty_succ').text(rtn.MSG);
                    $('#gpwrty_succ').show(500);
                    if($("#btn_popup_flash_notes").get(0)){
                        $('#btn_popup_flash_notes').trigger('click');
                        waitingDialog.hide();
                        setTimeout(function(){
                            $('#popup_flash_notes').modal('toggle');
                        }, 2000)
                        return;
                    }
                    waitingDialog.hide();
                    $('#gpwrty_succ').hide(10000);
                    return;
                }
                if(rtn.hasOwnProperty("ERR")){
                    $('#gpwrty_succ').text('');

                    $('#gpwrty_err').text(rtn.ERR);
                    $('#gpwrty_err').show(500);
                    if($("#btn_popup_flash_notes").get(0)){
                        $('#btn_popup_flash_notes').trigger('click');
                        waitingDialog.hide();
                        setTimeout(function(){
                            $('#popup_flash_notes').modal('toggle');
                        }, 2000)
                        return;
                    }
                    waitingDialog.hide();
                    $('#gpwrty_err').hide(10000);
                    return;
                }
                console.log(res);
                waitingDialog.hide();
                return;
            },
            error: function(xhr, status, err){
                console.log(err);
                waitingDialog.hide();
                return;
            }
        });
    }
    WishList = function(item_string, t){
        var item_arr = item_string.split('--');
        // console.log(item_arr);
        item = item_arr[0];
        div_id = item_arr[1];
        waitingDialog.show('Contacting server... Please wait',{headerText:'<?=$settings['SiteName']?> Notifications',headerSize: 6,dialogSize:'sm'});
        var data = "item="+item+"&t="+t;
        $.ajax({
            type: 'post',
            url: '<?=APP_AJAX_RT?>?activity=wish-list',
            data: data,
            success: function(res){
                console.log(res);
                var rtn = JSON.parse(res);
                if(rtn.hasOwnProperty("MSG")){
                    $('#'+item+div_id+'_err').hide();
                    $('#'+item+div_id+'_succ').text(rtn.MSG);
                    $('#'+item+div_id+'_succ').show(500);
                    waitingDialog.hide();
                    $('#'+item+div_id+'_succ').hide(10000);
                    return;
                }
                if(rtn.hasOwnProperty("ERR")){
                    $('#'+item+div_id+'_succ').hide();
                    $('#'+item+div_id+'_err').text(rtn.ERR);
                    $('#'+item+div_id+'_err').show(500);
                    waitingDialog.hide();
                    $('#'+item+div_id+'_err').hide(10000);
                    return;
                }
                console.log(res);
                waitingDialog.hide();
                return;
            },
            error: function(xhr, status, err){
                console.log(err);
                waitingDialog.hide();
                return;
            }
        });
    }
    subscribe_newsletter = function(formid){
        waitingDialog.show('Contacting server... Please wait',{headerText:'<?=$settings['SiteName']?> Notifications',headerSize: 6,dialogSize:'sm'});
        var data = $('#newsletter_sub_form').serialize();
        console.log(data);
        $.ajax({
            type: 'post',
            url: '<?=APP_AJAX_RT?>?activity=newsletter-sbscrb',
            data: data,
            success: function(res){
                console.log(res);
                var rtn = JSON.parse(res);
                if(rtn.hasOwnProperty("MSG")){
                    $('#succ').text(rtn.MSG);
                    $('#succ').show(500);
                    waitingDialog.hide();
                    $('#succ').hide(8000);
                    return;
                }
                if(rtn.hasOwnProperty("ERR")){
                    $('#err').text(rtn.ERR);
                    $('#err').show(500);
                    waitingDialog.hide();
                    $('#err').hide(8000);
                    return;
                }
                console.log(res);
                waitingDialog.hide();
                return;
            },
            error: function(xhr, status, err){
                console.log(err);
                waitingDialog.hide();
                return;
            }
        });
    }
})
</script>

<!-- modals area start-->
<a href="#" data-toggle="modal" id="btn_popup_flash_notes" style="display:none;" data-target="#popup_flash_notes">ShowMessage</a>
<div class="modal fade" id="popup_flash_notes" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="min-width:10%;width:50%;" role="document">
        <div class="modal-content" style="min-width:10%;width:100%;">
            <div class="modal_body" style="padding: 6px 7px 6px 7px;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="alert alert-success" style="color:#green;" id="gpwrty_succ" style="display:none;"></div>
                            <div class="alert alert-danger" id="gpwrty_err" style="display:none;"></div>
                        </div> 
                    </div>     
                </div>
            </div>    
        </div>
    </div>
</div> 
<!-- modal area start-->
