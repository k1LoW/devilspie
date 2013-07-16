global = Function('return this')();
pubsub = Pubsub.create();

$ ->
  pubsub.subscribe 'checkconsole', (context, type)->
    $('.console-' + type).removeClass 'hide'
    cache = ''
    id = setInterval ()->
      url = global.baseUrl + 'devils/console/' + type
      $.ajax
        url: url
        type: 'GET'
        dataType: 'json'
        cache: false
      .done (res)->
        return if res.result == 'nofile'
        return unless res.output
        $console = $('.console-' + type)
        $inner = $('.console-' + type + ' div');
        if res.output.match(global.stdoutEof)
          clearInterval id
          res.output = res.output.replace(global.stdoutEof, '')
          pubsub.publish 'checkconsole.done', null, type
        $inner.html(res.output)
        $console.scrollTop($console[0].scrollHeight) unless res.output == cache
        cache = res.output
        return
    , 2000
    return

  pubsub.subscribe 'checkconsole.done', (context, type)->
    $img = $('.console-' + type + ' img')
    $img.remove()
    if type == 'getsoul'
      pubsub.publish('checkconsole', null, 'serverspec')
    if type == 'serverspec'
      pubsub.publish('checkconsole', null, 'setserver')
    if type == 'setserver'
      pubsub.publish('checkconsole', null, 'setapp')
    if type == 'setapp'
      pubsub.publish('checkconsole', null, 're_serverspec')
    if type == 're_serverspec'
      $('.console-link').removeClass 'hide'
    return

  pubsub.publish 'checkconsole', null, 'getsoul'
  return