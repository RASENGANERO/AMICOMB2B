function getValuesGloss(checkVal){
    let valsKeys = document.getElementsByClassName('key-gloss-elem');
    let valsDivs = document.getElementsByClassName('gloss-item-hov');
    let pKey = 0;
    for (let i=0;i<valsKeys.length;i++) {
        pKey = 0;
        let divs = Array.from(valsKeys[i].childNodes).filter(node => node.nodeType === Node.ELEMENT_NODE && node.tagName === 'DIV');
        let countURLS = Array.from(divs[1].childNodes).filter(node => node.nodeType === Node.ELEMENT_NODE && node.tagName === 'DIV').length;
        let elemsURLS = Array.from(divs[1].childNodes).filter(node => node.nodeType === Node.ELEMENT_NODE && node.tagName === 'DIV');
        
        for(let j=0;j<elemsURLS.length;j++) {
            let linkElement = elemsURLS[j].childNodes[1].childNodes[1].childNodes[1].childNodes[1].childNodes[1];
            if (String(linkElement.textContent).indexOf(checkVal)!=-1){
                elemsURLS[j].setAttribute('style','display:flex;');
            }
            else{
                elemsURLS[j].setAttribute('style','display:none;');
                pKey +=1;
            }
        }
        if(pKey == countURLS) {
            divs[0].setAttribute('style','display:none;');
        }
        else{
            divs[0].setAttribute('style','display:flex;');
        }
    }
}
function filterGloss(){
    let valGloss = document.getElementById('search-term').value;
    getValuesGloss(valGloss);
}
document.addEventListener('DOMContentLoaded',()=>{
    let inputGloss = document.getElementById('search-term');
    inputGloss.addEventListener('input',filterGloss);
});