var currentLogs = [], interval;
$(document).ready(function() {

  $('#logs-time').select2();

});

/**
 * Created an EventSource instance.
 * This interface open a persistent connection to an HTTP server
 * which sends events in text/event-stream format
 */
if (typeof(EventSource) !== 'undefined') {

  // Create a instance
  var source = new EventSource(baseURL + 'dashboard/ossecLogs');

  // Open SSE
  source.addEventListener('open', function(e) {

    // console.info('Connection was opened.');

  }, false);

  // From this event, received data from server
  source.addEventListener('message', function(e) {

    data = JSON.parse(e.data)

    for (var i = data.length; i > 0; i--) {

      currentLogs.push(data[i]);

      if (data[i] != undefined) {

        $('#ossec-logs').prepend('<p>' + data[i] + '</p>');

      }

    }

    source.close();

  }, false);

  function sseOssecLogs(logsTime = 5000) {

    logsTime = parseInt(logsTime);

    setInterval(function() {

      var source = new EventSource(baseURL + 'dashboard/ossecLogs');
  
      source.addEventListener('open', function(e) {
  
      }, false);
  
      source.addEventListener('message', function(e) {
  
        data = JSON.parse(e.data)
  
        for (var i = data.length; i > 0; i--) {
  
          if (jQuery.inArray(data[i], currentLogs) == -1) {
  
            currentLogs.push(data[i]);
            $('#ossec-logs').prepend('<p class="log-color">' + data[i] + '<p>');
  
          }
  
        }
  
        source.close();
  
        
      }, false);
      
    }, logsTime);

  }

  // Call this function first time when the user open the web interface
  sseOssecLogs();
  
  $(document).ready(function() {

    // Added background for new alerts
    interval = setInterval(function() {
      $('.log-color').toggleClass('log-color-toggle');
    }, 1000);

    // Change event for select
    $('#logs-time').change(function() {
      if ($(this).val() != '') {
        sseOssecLogs($(this).val());
      }
    });

    // Remove background when the user enter on the div area
    $('#ossec-logs').mouseenter(function() {
      clearInterval(interval);
      $('.log-color').addClass('log-color-remove').removeClass('log-color log-color-toggle');
    });

  });


}
