/**
 * Created by JEGAN'S BEAST on 12/10/2016.
 */

$('.scrollspy').scrollSpy();

$('select').material_select();

function loadEvent(data){

        $('.scrollspy').scrollSpy();

    $('select').material_select();

    var updatediv = $('#updatediv');
    var updateshow = $('#update_show');

    $(updateshow).on('click', function () {

        if( $(updateshow).html() == 'Update'){
            $(updateshow).html("<i class='fa fa-caret-up fa-2x'></i>");
        }
        else {
            $(updateshow).html('Update');
        }

        $(updatediv).toggleClass('show hide');

    });

    $('#back').on('click', function () {
        var wrapper = $('#wrapper');

        wrapper.animateCss('fadeOutLeft', function () {
            wrapper.load('home.html', function () {
               loadHome();
            }).animateCss('fadeInDown');
        });

    });

    var defs = new EJS({url: '/tce/ejs/event/definition.ejs'}).render(data);
    $('#details').html(defs);

    var actions = new EJS({url: '/tce/ejs/event/actions.ejs'}).render(data);
    $('#action-container').html(actions);

    var mems = new EJS({url: '/tce/ejs/event/member.ejs'}).render(data);
    $('#member_container').html(mems);

}

function addAction(eventid){

}