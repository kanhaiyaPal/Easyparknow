$(document).ready(function(){ 

  var rootpath = $("input[name='rootpath_val']").val();
  $("select[name='town']").change(function(){ 
    var town = $(this).val();
    
    var dataString = "town_id="+town+"&function=getlocationnames_by_townid"; 

    $.ajax({ 
      type: "POST", 
      url: rootpath+"/master/ajax_handler.php", 
      data: dataString, 
      success: function(result){
        result += '<option value="" selected>Select Location</option>';
        $("select[name='location']").html(result); 
      }
    });
  });


  $('.datepicker_dep').datepicker({
    format: 'dd-mm-yyyy',
    endDate:new Date(),
    autoclose: true
  });
  
  $('.datepicker_ar').datepicker({
    format: 'dd-mm-yyyy',
    endDate:new Date(),
    autoclose: true
  });
});