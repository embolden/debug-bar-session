jQuery(document).ready(function($) {
  $('#dbs-list').on('click', '.dbs-remove', function(event) {
    var settings = {
      data: {
        action: 'dbs_remove_session_item',
        key: $(event.target).data('dbs-key'),
        nonce: ajax_helper.nonce,
      },
      dataType: 'json',
      method: 'POST',
      url: ajaxurl
    }

    $.ajax(settings)
    .done(function(response) {
      if( response.success === true ) {
        $('span[data-dbs-key=' + response.data.removed + ']').parent().remove();
      }
    });

  });

  $('#clear-all-session').on('click', function(event) {
    var settings = {
      data: {
        action: 'dbs_remove_all_session',
        nonce: ajax_helper.nonce,
      },
      dataType: 'json',
      method: 'POST',
      url: ajaxurl
    }

    $.ajax(settings)
    .done(function(response) {
      if( response.success === true ) {
        $('span[data-dbs-key]').parent().remove();
      }
    });
  });
});
