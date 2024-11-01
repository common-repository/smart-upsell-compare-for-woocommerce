function upselldivopen(product_id){
    showpopup();
    var productid = product_id;
    jQuery.ajax({
        url: wsuc_ajax_obj.ajaxurl,
        data: { 'action': 'wcsuc_ajax',
        'productid': product_id },
        success: function(res) {
            jQuery('.popup_box_in .popup_box_inner').html(res);
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert(errorMessage);
        }
    });
}

jQuery(document).ready(function(){
    jQuery("#display_popup").click(function(){
        showpopup();
    });
    jQuery("#cancel_button").click(function(){
        hidepopup();
    });
    
});
   
   
function showpopup()
{
    jQuery("#popup_box").fadeToggle();
    jQuery("#popup_box").css({"visibility":"visible","display":"block"});
}

function hidepopup()
{
    jQuery("#popup_box").fadeToggle();
    jQuery("#popup_box").css({"visibility":"hidden","display":"none"});
}