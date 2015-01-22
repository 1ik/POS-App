$(document).ready(function(){
    var firstGroup = $("#group option:first").val();
    show_sub_groups(firstGroup , function(){
        var firstSubGroup = $("#sub_group option:first").val();
        show_item_types(firstSubGroup , function(){
            var item_type = $("#item_type option:first").val();
            show_sizes(item_type);
        });
    });

    /**
    * on submitting form we close the event take the color code and size id send to server.
    * the server returns the shopname location and number of items available in every particular shop.
    */
    $('form').on('submit' , function() {
        $('#loader_item_store').removeClass("invisible");
        var size_id = $("#size_id").val();
        var color_code = $("#color_code").val();
        var designer_style = $('#designer_style').val();
        var url = "../items/search";
        $.post(url, { size : size_id, color:  color_code, style : designer_style}, function(data) {
            $('.appended').remove();
            console.log(data);
            $('#loader_item_store').addClass("invisible");
            var objs = jQuery.parseJSON(data);
            var html = '';
            $.each(objs, function (index, obj){
                console.log(obj);
                html += '<tr class = "appended">';
                html += "<td>" + obj.showroom_name + "</td>";
                html += "<td>" + obj.showroom_location + "</td>";
                html += "<td>" + obj.item_count + "</td>";
                html += "</tr>";
            });

            $('#item_store_table').append(html);
        });
        return false;
    });
});


$( "#group" ).change(function() {
    show_sub_groups($(this).val(),  function(){
        var firstSubGroup = $("#sub_group option:first").val();
        show_item_types(firstSubGroup , function(){
            var item_type = $("#item_type option:first").val();
            show_sizes(item_type);
        });
    });
});



$("#sub_group").change(function(){
    show_item_types($(this).val() , function(){
        var item_type = $("#item_type option:first").val();
        show_sizes(item_type);
    });
});

$('#item_type').change(function(){
    show_sizes($(this).val());
});


function show_sub_groups(group_id , callback){
	$('#loader_sub_group').removeClass('invisible');
    var url = '../subgroup/get_sub_groups_json/'+group_id;
    $.get( url, function( data ) {
    	$('#loader_sub_group').addClass('invisible');
        var subgrps = jQuery.parseJSON(data);
        var html= '';
        for(i=0; i<subgrps.length; i++){
            var subgrp = subgrps[i];
            html += "<option value="+subgrp.id+">"+subgrp.name+"</option>";
        }

        $('#sub_group').html(html);
        callback();
    });
}

function show_item_types(sub_id , callback){

	$('#loader_item_type').removeClass('invisible');
    var url = "../item_type/get_item_type_json/"+sub_id;
    $.get( url, function( data ) {

        $('#loader_item_type').addClass('invisible');


        var item_types = jQuery.parseJSON(data);
        var html= '';
        for(i=0; i<item_types.length; i++){
            var item_type = item_types[i];
            html += "<option value="+item_type.id+">"+item_type.name+"</option>";
        }
        $('#item_type').html(html);
        callback();
    });
}


function show_sizes(item_type_id){
    $('#loader_size').removeClass('invisible');
    $('#loader_size').removeClass('invisible');
    var url = "../size/get_sizes_json/"+item_type_id;
    // var url = "http://jhinukfashion.com/inventory/size/get_sizes_json/"+item_type_id;
    $.get( url, function( json ){
        $('#loader_size').addClass('invisible');
        var sizes = jQuery.parseJSON(json);

        var html = '';
        for(i=0; i<sizes.length; i++){
            html += "<option value="+sizes[i].id+">"+sizes[i].name+"</option>";
        }
        $('#size_id').html(html);
    });
}

