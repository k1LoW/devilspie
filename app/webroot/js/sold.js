(function() {
  var global, pubsub;

  global = Function('return this')();

  pubsub = Pubsub.create();

  $(function() {
    pubsub.subscribe('checkconsole', function(context, type) {
      var cache, id;
      $('.console-' + type).removeClass('hide');
      cache = '';
      id = setInterval(function() {
        var url;
        url = global.baseUrl + 'devils/console/' + type;
        return $.ajax({
          url: url,
          type: 'GET',
          dataType: 'json',
          cache: false
        }).done(function(res) {
          var $console, $inner;
          if (res.result === 'nofile') {
            return;
          }
          if (!res.output) {
            return;
          }
          $console = $('.console-' + type);
          $inner = $('.console-' + type + ' div');
          if (res.output.match(global.stdoutEof)) {
            clearInterval(id);
            res.output = res.output.replace(global.stdoutEof, '');
            pubsub.publish('checkconsole.done', null, type);
          }
          $inner.html(res.output);
          if (res.output !== cache) {
            $console.scrollTop($console[0].scrollHeight);
          }
          cache = res.output;
        });
      }, 2000);
    });
    pubsub.subscribe('checkconsole.done', function(context, type) {
      var $img;
      $img = $('.console-' + type + ' img');
      $img.remove();
      if (type === 'getsoul') {
        pubsub.publish('checkconsole', null, 'serverspec');
      }
      if (type === 'serverspec') {
        pubsub.publish('checkconsole', null, 'setserver');
      }
      if (type === 'setserver') {
        pubsub.publish('checkconsole', null, 'setapp');
      }
      if (type === 'setapp') {
        pubsub.publish('checkconsole', null, 're_serverspec');
      }
      if (type === 're_serverspec') {
        $('.console-link').removeClass('hide');
      }
    });
    pubsub.publish('checkconsole', null, 'getsoul');
  });

}).call(this);
