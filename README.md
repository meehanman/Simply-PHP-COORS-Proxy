#### Simply-PHP-COORS-Proxy

      Usage: /proxy.php?url=xxxx
      
Will accept JSON data in body and will overwrite Access-Control-Allow headers to bypass for POST,GET,OPTIONS and PUT. (You can add more manually).

Usage using JSON


      $.ajax({
        url: "/proxy.php?url=xxxx",
        context: JSONObject
        method: 'PUT'
      }).done(function(request) {
        alert(request.data);
      });
