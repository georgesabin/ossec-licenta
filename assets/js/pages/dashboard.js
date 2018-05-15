var current_calls, calls_traffic, trafficTable, currentLogs = [], interval;
$(document).ready(function() {

  $('#logs-time').select2();

  // updateCharts();
  // updateServerInfo();

  function updateServerInfo() {
    $.ajax({
      url: 'http://'+serverIP+':8501/xml.php?plugin=complete&json',
      type: 'post'
    }).done(function(result) {
      result = $.parseJSON(result);
      if(typeof result.Vitals !== 'undefined') {
        cpuLoadsAvg = (result.Vitals['@attributes'].LoadAvg).split(' ');
        if(cpuLoadsAvg.length == 3) {
          cpu1MinColor = 'progress-bar-success';
          cpu5MinColor = 'progress-bar-success';
          cpu15MinColor = 'progress-bar-success';
          diskUsedColor = 'progress-bar-success';
          cpuLoadsAvg[0] = cpuLoadsAvg[0]/result.Hardware['CPU'].CpuCore.length;
          cpuLoadsAvg[1] = cpuLoadsAvg[1]/result.Hardware['CPU'].CpuCore.length;
          cpuLoadsAvg[2] = cpuLoadsAvg[2]/result.Hardware['CPU'].CpuCore.length;
          $('*[data-name="cpu_1min"]').html((cpuLoadsAvg[0]*100).toFixed(2)+'%');
          $('*[data-name="cpu_5min"]').html((cpuLoadsAvg[1]*100).toFixed(2)+'%');
          $('*[data-name="cpu_15min"]').html((cpuLoadsAvg[2]*100).toFixed(2)+'%');
          $('*[data-name="cpu_1min_bar"]').css('width', (cpuLoadsAvg[0]*100).toFixed(2)+'%');
          $('*[data-name="cpu_5min_bar"]').css('width', (cpuLoadsAvg[1]*100).toFixed(2)+'%');
          $('*[data-name="cpu_15min_bar"]').css('width', (cpuLoadsAvg[2]*100).toFixed(2)+'%');
          if(cpuLoadsAvg[0]*100 > 25) { cpu1MinColor = 'progress-bar-info'; }
          if(cpuLoadsAvg[0]*100 > 50) { cpu1MinColor = 'progress-bar-warning'; }
          if(cpuLoadsAvg[0]*100 > 80) { cpu1MinColor = 'progress-bar-danger'; }
          if(!$('*[data-name="cpu_1min_bar"]').hasClass(cpu1MinColor)) { 
$('*[data-name="cpu_1min_bar"]').removeClass('progress-bar-success').removeClass('progress-bar-info').removeClass('progress-bar-warning').removeClass('progress-bar-danger').addClass(cpu1MinColor); 
}
          if(cpuLoadsAvg[1]*100 > 25) { cpu5MinColor = 'progress-bar-info'; }
          if(cpuLoadsAvg[1]*100 > 50) { cpu5MinColor = 'progress-bar-warning'; }
          if(cpuLoadsAvg[1]*100 > 80) { cpu5MinColor = 'progress-bar-danger'; }
          if(!$('*[data-name="cpu_5min_bar"]').hasClass(cpu5MinColor)) { 
$('*[data-name="cpu_5min_bar"]').removeClass('progress-bar-success').removeClass('progress-bar-info').removeClass('progress-bar-warning').removeClass('progress-bar-danger').addClass(cpu5MinColor); 
}
          if(cpuLoadsAvg[2]*100 > 25) { cpu15MinColor = 'progress-bar-info'; }
          if(cpuLoadsAvg[2]*100 > 50) { cpu15MinColor = 'progress-bar-warning'; }
          if(cpuLoadsAvg[2]*100 > 80) { cpu15MinColor = 'progress-bar-danger'; }
          if(!$('*[data-name="cpu_15min_bar"]').hasClass(cpu15MinColor)) { 
$('*[data-name="cpu_15min_bar"]').removeClass('progress-bar-success').removeClass('progress-bar-info').removeClass('progress-bar-warning').removeClass('progress-bar-danger').addClass(cpu15MinColor); 
}
        }
        $('*[data-name="ram_used_text"]').html(formatBytes(result.Memory['@attributes'].Used)+' / '+formatBytes(result.Memory['@attributes'].Total));
        $('*[data-name="ram_bar_used"]').css('width', result.Memory.Details['@attributes'].AppPercent+'%');
        $('*[data-name="ram_cached_text"]').html(formatBytes(result.Memory.Details['@attributes'].Cached)+' cached');
        $('*[data-name="ram_bar_cached"]').css('width', result.Memory.Details['@attributes'].CachedPercent+'%');
        $('*[data-name="disk_used_text"]').html(formatBytes(result.FileSystem.Mount[0]['@attributes'].Used)+' / '+formatBytes(result.FileSystem.Mount[0]['@attributes'].Total));
        $('*[data-name="disk_used_bar"]').css('width', result.FileSystem.Mount[0]['@attributes'].Percent+'%');
        if(result.FileSystem.Mount[0]['@attributes'].Percent > 25) { diskUsedColor = 'progress-bar-info'; }
        if(result.FileSystem.Mount[0]['@attributes'].Percent > 50) { diskUsedColor = 'progress-bar-warning'; }
        if(result.FileSystem.Mount[0]['@attributes'].Percent > 75) { diskUsedColor = 'progress-bar-danger'; }
        if(!$('*[data-name="disk_used_bar"]').hasClass(diskUsedColor)) { 
$('*[data-name="disk_used_bar"]').removeClass('progress-bar-success').removeClass('progress-bar-info').removeClass('progress-bar-warning').removeClass('progress-bar-danger').addClass(diskUsedColor); 
}
      }
      setTimeout(updateServerInfo, 5000);
    });
  }
});

if (typeof(EventSource) !== 'undefined') {

  var source = new EventSource(baseURL + 'dashboard/ossecLogs');

  source.addEventListener('open', function(e) {

    console.info('Connection was opened.');

  }, false);

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
    console.log(typeof logsTime);

    setInterval(function() {

      var source = new EventSource(baseURL + 'dashboard/ossecLogs');
  
      source.addEventListener('open', function(e) {
  
        console.info('Connection was opened.');
  
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

  sseOssecLogs();
  
  
  $(document).ready(function() {

    interval = setInterval(function() {
      $('.log-color').toggleClass('log-color-toggle');
    }, 1000);

    $('#logs-time').change(function() {
      if ($(this).val() != '') {
        sseOssecLogs($(this).val());
      }
    });

    $('#ossec-logs').mouseenter(function() {
      clearInterval(interval);
      $('.log-color').addClass('log-color-remove').removeClass('log-color log-color-toggle');
    });

  });


}
