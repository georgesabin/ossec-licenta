function js_modal(modal_id, modal_ajax_url) {
	Noty.closeAll();
	$.post(
		modal_ajax_url,
		{ token: Cookies.get('token') },
		function(result) {
			$('#'+modal_id).html(result);
			$('#'+modal_id).modal('show');
		}
	);
}

$(document).ready(function() {
	$('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar({suppressScrollX: true});
	$('#modal_container').perfectScrollbar({suppressScrollX: true});
	$('#modal_container_2').perfectScrollbar({suppressScrollX: true});
	$('#modal_container_3').perfectScrollbar({suppressScrollX: true});
	setInterval(function() {
		$('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('update');
		$('#modal_container').perfectScrollbar('update');
		$('#modal_container_2').perfectScrollbar('update');
		$('#modal_container_3').perfectScrollbar('update');
	}, 500);
	$(document).on('click', '.has_childs', function(event) {
		event.preventDefault();
		var child = $(this).children('ul')
		if(child.css('display') == 'none') {
			$(this).css('background-color', '#282828');
			child.slideDown('normal');
		}else{
			child.slideUp('normal', function() {
				$(this).css('background-color', 'transparent');
			});
		}
	});
  $(document).on('click', '.toggleDetails', function() {
    if($(this).hasClass('onlyChanges')) {
      $(this).html('show changes only');
      $(this).removeClass('onlyChanges');
      $(this).parent('th').parent('tr').parent('thead').siblings('tbody').children('tr').css('display', 'table-row');
    }else{
      $(this).html('show everything');
      $(this).addClass('onlyChanges');
      $(this).parent('th').parent('tr').parent('thead').siblings('tbody').children('tr.same').css('display', 'none');
    }
  });
	$(document).on('focus', '.paginate_button > a', function() {
	    $(this).blur();
	});
});

function showLoader(element) {
	$(element).css('display', 'flex').hide().fadeIn();
}

function hideLoader(element) {
	$(element).fadeOut();
}
