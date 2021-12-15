
var global = global || {};

global.date = new Date();// .toLocaleString({ timeZone: 'Asia/Tokyo' });
global.date_ymd  = 0;
global.date_ymd += global.date.getFullYear() * 10000;
global.date_ymd += (global.date.getMonth() + 1) * 100;
global.date_ymd += global.date.getDate();

global.update_ymd = 0;

$('[data-event_date]').each(function(){

    var ymd = parseInt($(this).attr('data-event_date'));

    if (typeof ymd != 'undefined' && ymd < global.date_ymd) {
        $(this).remove();
        if (global.update_ymd < ymd + 1) global.update_ymd = ymd + 1;
    }
});

$('[data-open_date]').hide().each(function(){

    var ymd = parseInt($(this).attr('data-open_date'));

    if (typeof ymd != 'undefined' && ymd <= global.date_ymd) {
        $(this).show();
        if (global.update_ymd < ymd) global.update_ymd = ymd + 0;
    } else {
        $(this).remove();
    }
});

$('[data-close_date]').each(function(){

    var ymd = parseInt($(this).attr('data-close_date'));

    if (typeof ymd != 'undefined' && ymd <= global.date_ymd) {
        $(this).remove();
        if (global.update_ymd < ymd) global.update_ymd = ymd + 0;
    }
});

if (global.update_ymd != 0) {

    var t = '';
    t += global.update_ymd.toString().substr(0,4) + '年';
    t += global.update_ymd.toString().substr(4,2) + '月';
    t += global.update_ymd.toString().substr(6,2) + '日';

    $('#update_date').text(t);
}
