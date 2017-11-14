jQuery(document).ready(function($){
  $('.canNotBeUsed').children('div').children('p').children('a').on('click', function(){
    event.preventDefault();
  });
});
