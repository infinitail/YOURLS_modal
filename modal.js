function infinitail_remove_link_modal(id) {
	if( $('#delete-button-'+id).hasClass('disabled') ) {
		return false;
	}

    $('#infinitail-delete-confirm-modal input[name="keyword_id"]').val(id);

    var keyword = $('#keyword-'+id+' > a').text();
    keyword = trim_long_string(keyword, 80);
    $('#infinitail-delete-confirm-modal span[name="short_url"]').text(keyword);

    var url = $('#url-'+id+' > a').attr('href');
    url = trim_long_string(url, 80);
    $('#infinitail-delete-confirm-modal span[name="url"]').text(url);

    $('#infinitail-delete-confirm-modal-dimmer').show();
    $('#infinitail-delete-confirm-modal').show();

    // Only show delete confirm modal
    return false;
}

function infinitail_remove_link_confirmed() {
    var id = $('#infinitail-delete-confirm-modal input[name="keyword_id"]').val();
    var keyword = $('#keyword_'+id).val();
    var nonce = get_var_from_query( $('#delete-button-'+id).attr('href'), 'nonce' );
    $.getJSON(
        ajaxurl,
        { action: "delete", keyword: keyword, nonce: nonce, id: id },
        function(data){
            if (data.success == 1) {
                $("#id-" + id).fadeOut(function(){
                    $(this).remove();
                    if( $('#main_table tbody tr').length  == 1 ) {
                        $('#nourl_found').css('display', '');
                    }

                    zebra_table();
                });
                decrement_counter();
                decrease_total_clicks( id );
            } else {
                feedback('something wrong happened while deleting!' , 'fail');
            }
            $('#infinitail-delete-confirm-modal-dimmer').hide();
            $('#infinitail-delete-confirm-modal').hide();
        }
    );
}

function infinitail_remove_link_cancel() {
    $('#infinitail-delete-confirm-modal-dimmer').hide();
    $('#infinitail-delete-confirm-modal').hide();
}