$(document).idle({
  onIdle: function(){
    
    $.fancybox.close();
    $('.modal').modal('hide');

    formInput   =   '';

    $.post('/logout/logout_idle',formInput, function(data)
    {                   
        //
    }); 

    $('#lock-idle').modal('show');
  },
  //onActive: function(){
  //  alert('Hey, I\'m back!');
  //},
  idle: 300000 //5 menit
})    