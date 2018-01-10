var members = [];
var selectedMembers = [];
var events = JSON.parse('{"events":[{"id":1,"status":0,"name":"Topic","description":"hello","start_date":"11-06-2016","due_date":"01-02-2017"},{"id":2,"status":2,"name":"Topic2","description":"bye","start_date":"11-06-2016","due_date":"01-02-2017"},{"id":3,"status":2,"name":"Topic2","description":"bye","start_date":"11-06-2016","due_date":"01-02-2017"},{"id":4,"status":2,"name":"Topic2","description":"bye","start_date":"11-06-2016","due_date":"01-02-2017"},{"id":5,"status":2,"name":"Topic2","description":"bye","start_date":"11-06-2016","due_date":"01-02-2017"},{"id":6,"status":2,"name":"Topic2","description":"bye","start_date":"11-06-2016","due_date":"01-02-2017"},{"id":7,"status":2,"name":"Topic2","description":"bye","start_date":"11-06-2016","due_date":"01-02-2017"},{"id":8,"status":2,"name":"Topic2","description":"bye","start_date":"11-06-2016","due_date":"01-02-2017"}]}');
var userid;
var myoffset =0;
var alloffset =0;
var myevents = undefined;
var allevents = undefined;

function loadHome(user_id){

    userid = user_id;

    var ultabs = $('ul.tabs');
    $(ultabs).tabs();

    $('select').material_select();

    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15 // Creates a dropdown of 15 years to control year
    });


    loadMembers();

    $("#create").on('click', function () {

        var formdata = {};

        formdata["name"] = $("#title").val();
        formdata["description"] = $("#message").val();
        formdata["start_date"] = $("#start_date").val();
        formdata["due_date"] = $("#due_date").val();
        formdata["department"] = [];
        var dpts =$("#dept").val();

        for(var i=0;i<dpts.length;i++){
            formdata["department"][i] = parseInt(dpts[i]);
        }

        formdata["members"] = selectedMembers;

        showLoader();

        $.ajax({
            url:"/tce/php/events.php",
            type:"post",
            contentType:"application/json",
            dataType:"json",
            data:JSON.stringify(formdata),
            success: function(res){
                  if(res["success"]==true){
                        Materialize.toast("Successfully created",1000);
                      $(ultabs).tabs('select_tab', 'mycards');
                      $("#title").val("");
                      $("#message").val("");
                      $("#start_date").val("");
                      $("#due_date").val("");
                      selectedMembers = [];
                      $("#tags").empty();
                      $("#dept").val("");
                  }
                else{
                      Materialize.toast("fatal error",1000);
                  }

                hideLoader();
            },
            error: function () {
                Materialize.toast("server error",1000);
                hideLoader();
            }
        });
    });

    var back = $("#back");
    var iback = $("#iback");

    $(back).hide();
    $(iback).hide();

    $("#next").on('click', function () {
        myoffset++;
        loadEvents(myoffset);
        $(back).show();
    });

    $(back).on('click', function () {
        --myoffset;
        if(myoffset==0)
            $(back).hide();
        loadEvents(myoffset);
    });

    $("#inext").on('click', function () {
        alloffset++;
        loadAllEvents(alloffset);
        $(iback).show();
    });

    $(iback).on('click', function () {
        --alloffset;
        if(alloffset==0)
            $(iback).hide();
        loadAllEvents(alloffset);
    });

    $("#search-btn").on('click', function () {
        myoffset=0;
         loadEvents(myoffset);
    });

    $("#isearch-btn").on('click', function () {
        alloffset =0;
        loadAllEvents(alloffset);
    });

    //my events

    loadEvents(0);

    loadAllEvents(0);

    //new event
    var add_input = $("#add");

    $(add_input).on('input', function () {
        if ($(this).val().length < 1) {
            $("#memlist_holder").addClass("hide");
        }
        else {
            showMembers($("#add").val());
        }
    });

    $(window).on('click', function (e) {
        if(e.target != $(add_input)){
            $("#add").val("");
            $("#memlist_holder").addClass("hide");
        }
    });


}


 function loadEvents(offset){

     var s = $("#search").val();

     var eventholder = $("#events");

     $.ajax({
         url:"/tce/php/fetch.php",
         type:"GET",
         data:{events:1,search:s,offset:offset},
         success: function(res){
             events = JSON.parse(res);
             if(events.events.length < 1)
                $("#next").hide();
             else
                 $("#next").show();

             events.idprefix = "myevent";

             myevents = events;

             var event_list = new EJS({url:'/tce/ejs/event_row.ejs'}).render(events);
             eventholder.html(event_list);
         }
     });

}




function loadAllEvents(offset){
    var s = $("#isearch").val();

    var eventholder = $("#ievents");

    $.ajax({
        url:"/tce/php/fetch.php",
        type:"GET",
        data:{events:0,search:s,offset:offset},
        success: function(res){
            var e = JSON.parse(res);
            if(e.events.length < 1)
                $("#inext").hide();
            else
                $("#inext").show();

            e.idprefix = "allevent";

            allevents = e;

            var event_list = new EJS({url:'/tce/ejs/event_row.ejs'}).render(e);
            eventholder.html(event_list);

        }
    });

}


function showEvent(type,i){
    if(type=="myevent"){
        loadEventInPage(myevents.events[i]);
    }
    else if(type == "allevent"){
        loadEventInPage(allevents.events[i]);
    }
}


function loadEventInPage(event){
        var wrapper = $('#wrapper');
        console.log(event);
        wrapper.animateCss('fadeOutUp', function () {
            wrapper.load('event.html', function () {
                loadEvent(event);
            }).animateCss('fadeInDown');
        });
}


function getColorCode(code) {
    switch (code) {
        case 0:
            return "green";
        case 1:
            return "red";
        default:
            return "grey";
    }
}

function showMembers(input){

    var showMembers = [];

    for (var i = 0; i < members["members"].length; i++) {
        var pos = members["members"][i];
        if (selectedMembers.indexOf(pos["userid"]) < 0 && pos["username"].toLowerCase().search(input.toLowerCase()) >= 0) {
            showMembers.push(pos);
        }
    }
    var showJson={};
    showJson["members"] = showMembers;

    var list = new EJS({url: '/tce/ejs/member_row.ejs'}).render(showJson);
    $("#memlist").html(list);
    $("#memlist_holder").removeClass("hide");
}

function selectedMember(elem) {
    console.log("selected");

    var i = elem.id-1;
    console.log(i);
    selectedMembers.push(members["members"][i]["userid"]);
    addChip(members["members"][i]);
    $("#memlist_holder").addClass("hide");
    $("#add").val("");
}

function addChip(member) {
    var tag = new EJS({url:'/tce/ejs/member_chip.ejs'}).render(member);
    $("#tags").append(tag);
}

function deleteTag(id){

    var tag = $("#tag"+id);

    var pos = selectedMembers.indexOf(id);

    if(pos != -1){
        selectedMembers.splice(pos,1);
    }

    $(tag).remove();
}
function loadMembers(){

    $.ajax({
        url:"/tce/php/fetch.php",
        type:"GET",
        data:{users:1},
        success: function(res){
            members = JSON.parse(res);
        }
    });

}
