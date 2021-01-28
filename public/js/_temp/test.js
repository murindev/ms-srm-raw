ff = [];
dd = [{"id":1333,"amount":7000,"form":2,"type":1,"lid_id":2055,"act_id":[],"act_auto_payment":null,"contract":"20-7-20-7","customer":1045,"manager":21,"comment":null,"created_at":"2020-10-14 16:43:39","updated_at":"2020-10-14 16:43:39"},{"id":1332,"amount":16226,"form":2,"type":1,"lid_id":2055,"act_id":[],"act_auto_payment":null,"contract":"20-7-20-7","customer":1045,"manager":21,"comment":null,"created_at":"2020-10-14 16:43:15","updated_at":"2020-10-14 16:43:15"},{"id":1248,"amount":9588,"form":2,"type":1,"lid_id":2055,"act_id":[],"act_auto_payment":null,"contract":"20-7-20-7","customer":1045,"manager":21,"comment":null,"created_at":"2020-10-01 00:00:00","updated_at":"2020-10-05 11:21:32"},{"id":822,"amount":22435,"form":2,"type":1,"lid_id":2055,"act_id":[],"act_auto_payment":null,"contract":"20-7-20-7","customer":1045,"manager":21,"comment":null,"created_at":"2020-09-15 17:13:55","updated_at":"2020-09-15 17:13:55"},{"id":821,"amount":31983,"form":2,"type":1,"lid_id":2055,"act_id":[8312],"act_auto_payment":null,"contract":"20-7-20-7","customer":1045,"manager":21,"comment":null,"created_at":"2020-09-15 17:06:54","updated_at":"2020-09-15 17:06:54"},{"id":571,"amount":24074,"form":2,"type":1,"lid_id":2055,"act_id":[9122],"act_auto_payment":null,"contract":"20-7-20-7","customer":1045,"manager":21,"comment":null,"created_at":"2020-09-04 00:00:00","updated_at":"2020-09-04 15:57:28"},{"id":572,"amount":22300,"form":2,"type":1,"lid_id":2055,"act_id":[9322],"act_auto_payment":null,"contract":"20-7-20-7","customer":1045,"manager":21,"comment":null,"created_at":"2020-09-04 00:00:00","updated_at":"2020-09-04 15:57:47"},{"id":190,"amount":18885,"form":2,"type":1,"lid_id":2055,"act_id":[8310,8888],"act_auto_payment":null,"contract":"20-7-20-7","customer":1045,"manager":21,"comment":null,"created_at":"2020-08-12 00:00:00","updated_at":"2020-08-21 15:13:52"},{"id":192,"amount":13098,"form":2,"type":1,"lid_id":2055,"act_id":[8267],"act_auto_payment":null,"contract":"20-7-20-7","customer":1045,"manager":21,"comment":"12.08.2020","created_at":"2020-08-12 00:00:00","updated_at":"2020-08-21 15:13:53"},{"id":188,"amount":20952,"form":2,"type":1,"lid_id":2055,"act_id":[8297],"act_auto_payment":null,"contract":"20-7-20-7","customer":1045,"manager":21,"comment":"27.07.2020","created_at":"2020-07-27 00:00:00","updated_at":"2020-08-21 15:13:51"},{"id":189,"amount":23326,"form":2,"type":1,"lid_id":2055,"act_id":[8297],"act_auto_payment":null,"contract":"20-7-20-7","customer":1045,"manager":21,"comment":"27.07.2020","created_at":"2020-07-27 00:00:00","updated_at":"2020-08-21 15:13:51"},{"id":184,"amount":13098,"form":2,"type":1,"lid_id":2055,"act_id":[8175],"act_auto_payment":null,"contract":"20-7-20-7","customer":1045,"manager":21,"comment":"10.07.2020","created_at":"2020-07-10 00:00:00","updated_at":"2020-08-21 15:13:48"},{"id":186,"amount":18885,"form":2,"type":1,"lid_id":2055,"act_id":[8194],"act_auto_payment":null,"contract":"20-7-20-7","customer":1045,"manager":21,"comment":"10.07.2020","created_at":"2020-07-10 00:00:00","updated_at":"2020-08-21 15:13:49"},{"id":573,"amount":14115,"form":2,"type":1,"lid_id":2055,"act_id":[8146],"act_auto_payment":null,"contract":"20-7-20-7","customer":1045,"manager":21,"comment":null,"created_at":"2020-07-10 00:00:00","updated_at":"2020-09-04 15:58:56"}];

// let gg = dd.map(function(item) {
//     return item.act_id.map(sub => ff.push(sub))
// })
let gg = dd.map(item => item.act_id.map(sub => ff.push(sub)))

console.log(ff);


var date = new Date();
console.log(date.getTimezoneOffset());
// date.setTime( date.getTime() + date.getTimezoneOffset()*60*1000 );
var firstDay = new Date(date.getFullYear(), date.getMonth(), 1,-date.getTimezoneOffset()/60);
var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0,-date.getTimezoneOffset()/60);

console.log(firstDay,lastDay);

if(new Date('2020-11-10T17:18:18.000Z') >= new Date('2020-10-10T17:18:18.000Z' )){
    console.log('more');
} else {
    console.log('less');
}


// console.log('pp',new Date('2020-10-10 20:18:18'));
