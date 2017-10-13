$(document).ready(function(){ 

  var rootpath = $("input[name='rootpath_val']").val(); 

  $("select[name='contra_town']").change(function(){ 
    var town = $(this).val();
    
    var dataString = "town_id="+town+"&function=getlocationnames_by_townid"; 

    $.ajax({ 
      type: "POST", 
      url: rootpath+"/master/ajax_handler.php", 
      data: dataString, 
      success: function(result){
        $("select[name='contra_location']").html(result); 
      }
    });
  });

  $("input[name='bt_generate_slots']").click(function(e){ 
    e.preventDefault();
    var slt_cnt = $("input[name='contra_size']").val();
    console.log(isNaN(slt_cnt));
    if(isNaN(slt_cnt) || (parseInt(slt_cnt)<=0)){ alert('Please provide a number greater or more than 1'); return false; } 
    var ret_val = confirm("Are you sure you want to generate "+slt_cnt+" parking slots?");
    if(ret_val){
      $.ajax({
          type: "POST",
          url: rootpath+"/master/ajax_handler.php", 
          data: { 
              slots: slt_cnt, // < note use of 'this' here
              function: "generateParkingSlots" 
          },
          success: function(result) {
              if(result == '1'){

              }else{
                $('div#slots_parking').html(result);
                $('div#size_parking').hide();
              }
          },
          error: function(result) {
              alert('Error contacting server.Please try again');
          }
      });
    }else{
      return false;
    }    
  });

  $("input[name='bt_regenerate_slots']").click(function(e){ 
    e.preventDefault();
    $('div#slots_parking').html('');
    $('div#size_parking').show();   
  });
  


});

 

