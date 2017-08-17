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

  $('#dbs-add-kv-pair-button').on('click', function(event) {

    var settings = {
      data: {
        action: 'dbs_add_kv_pair',
        key: $('#dbs-session-key').val(),
        value: $('#dbs-session-value').val(),
        nonce: ajax_helper.nonce,
      },
      dataType: 'json',
      method: 'POST',
      url: ajaxurl
    }

    $.ajax(settings)
    .done(function(response) {
      if( response.success === true ) {
        $('#dbs-list').append('<li><p><strong>' + $('#dbs-session-key').val() + '</strong> - <span>' + $('#dbs-session-value').val() + '</span> <span data-dbs-key="' + $('#dbs-session-key').val() + '" class="dbs-remove">x</span></p></li>');
        $('#dbs-session-key').val('');
        $('#dbs-session-value').val('');
      }
    });
  });


});
