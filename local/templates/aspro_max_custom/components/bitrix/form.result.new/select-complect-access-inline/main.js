function prev_val(event){
    let dtIDs = event.currentTarget.getAttribute('data-ids');
    let dtCont = Number(document.getElementById(dtIDs).value);
    dtCont=dtCont-1;
    if(dtCont<0){
        dtCont=0;
    }
    document.getElementById(dtIDs).setAttribute('value',dtCont);//.value = dtCont;
}
function next_val(event){
    let dtIDs = event.currentTarget.getAttribute('data-ids');
    let dtCont = Number(document.getElementById(dtIDs).value);
    dtCont=dtCont+1;
    document.getElementById(dtIDs).setAttribute('value',dtCont);//.value = dtCont;
}

document.addEventListener('DOMContentLoaded',()=>{
    let btns = document.getElementsByClassName('form-numberinput-btn');
    for(let i=0;i<btns.length;i++) {
        if(btns[i].hasAttribute('data-attr')){
            if(btns[i].getAttribute('data-attr')=='minus'){
                btns[i].addEventListener('click',prev_val);
            }
            else{
                btns[i].addEventListener('click',next_val);
            }
        }
    }
});