function showOrgs (event) {
    let attrShow = event.target.getAttribute('item-check');
    let btnsOrg = document.getElementsByClassName('button-main-org');
    let itemsOrg = document.getElementsByClassName('dashboard-info-org-item');
    for (let i = 0; i < btnsOrg.length; i++) {
        if (i == attrShow) {
            btnsOrg[i].setAttribute('class','button-main-org-active button-main-org');
            itemsOrg[i].setAttribute('class','org-item-active dashboard-info-org-item');
        }
        else {
            btnsOrg[i].setAttribute('class','button-main-org');
            itemsOrg[i].setAttribute('class','dashboard-info-org-item');
        }
    }
}
document.addEventListener('DOMContentLoaded',()=>{
    if (document.getElementsByClassName('button-main-org').length!=0) {
        let btnOrgs = document.getElementsByClassName('button-main-org');
        for (let i = 0; i < btnOrgs.length; i++) {
            btnOrgs[i].addEventListener('click',showOrgs);
        }
    }
});