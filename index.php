<?php
function img() {
  $images = array();
  foreach(glob("*.png") as $file) {
    $images[] = array(
      'size' => filesize($file),
      'path' => $file,
      'mod' => filemtime($file)
    );
  }
  usort($images, function($a, $b) {
    if ($a['mod'] === $b['mod']) return 0;
    return ($a['mod'] > $b['mod']) ? -1 : 1;
  });
  return $images;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Gyazo'd</title>
  <style>
    #images { margin: 0 auto; width: 540px; }
    #images img { display: none; }
    #images img.loaded {
      display: block;
      margin: 10px;
      padding: 10px;
      border: 1px solid #EEEEEE;
      -moz-box-shadow: 5px 5px 5px #CCCCCC;
      -webkit-box-shadow: 5px 5px 5px #CCCCCC;
      box-shadow: 5px 5px 5px #CCCCCC;
    }
    #images img:hover {
      -moz-box-shadow: 5px 5px 5px #fe57a1;
      -webkit-box-shadow: 5px 5px 5px #fe57a1;
      box-shadow: 5px 5px 5px #fe57a1;
    }
    input { width: 100%; }
    #dialog img { display: block; margin: 20px auto; }
  </style>
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.0/themes/base/jquery-ui.css" />
</head>
<body>
  
  <section id="dialog">
    <input />
  </section>
  
  <section id="images"></section>
  
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
  
  <script>
    var images = <?php echo json_encode(img()) ?>,
        section = $('#images')
        dialog = $('#dialog');
        
    $.fn.scale = function(w, h) {
      return this.load(function() {
        var $this = $(this),
            width = $this.width(),
            height = $this.height(),
            ratio = 0;
            
        if (width > w) {
          ratio = w / width;
          height = height * ratio;
          width = w;
        }
        
        if (height > h) {
          ratio = h / height;
          width = width * ratio;
          height = h;
        }
        
        $this
          .css({height: height, width: width})
          .addClass('loaded');
      });
    };
    
    $.each(images, function(i, image) {
      var img = $('<img>')
        .attr('src', image.path)
        .attr('data-mod', image.mod)
        .scale(500, 500)
        .click(function() {
          var wat = $('<img>')
            .attr('src', this.src)
            .scale(750, 600).click(function() {
              window.location = this.src;
            });
          
          dialog
            .dialog('close')
            .find('img')
            .remove();
          
          dialog
            .append(wat)
            .dialog('option', 'title', this.src)
            .dialog('open')
            .find('input')
            .val(this.src);
        });
      
      section.append(img);
    });
    
    dialog.dialog({
      autoOpen: false,
      width: 800,
      height: 600,
      modal: true
    });
  </script>
</body>
</html>
