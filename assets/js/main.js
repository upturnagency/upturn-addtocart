jQuery(document).ready(function($){
  $('.canNotBeUsed').children('a').on('click', function(){
    event.preventDefault();
  });


  $('.runFunction').click(function() {
    $.ajax({
      type: "POST",
      url: "some.php",
      data: { name: "John" }
    }).done(function( msg ) {
      alert( "Data Saved: " + msg );
    });
  });
});
