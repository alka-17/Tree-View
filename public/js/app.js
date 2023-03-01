
var task = document.querySelector('#task');
fetch('http://127.0.0.1:8000/api/v1/get-tree').then(res=>res.json()).then(data=>{
    appendchild(data, 0);
    const clickable_task = document.querySelectorAll('.clickable-task');
    clickable_task.forEach(ctask => {
        ctask.addEventListener('click', (e)=>{
            let id = e.target.getAttribute('data-id');
            if(e.target.children.length > 0){
                removechild(e.target.children);
            }else{
                appendchild(data, id);
            }
        })
    });
});

//function to append child
function appendchild(data, id) {
    var arrayData = data.data;
    let ul = document.createElement('ul');
    if(id != 0){
        task = document.querySelector('#clickable_task'+id);
    }
    task.appendChild(ul);
    let parent = arrayData.filter(task=>task.parent_entry_id==id);
    parent.forEach((element, key) => {
        let li = document.createElement('li');
        let child_exist = arrayData.filter(task=>task.parent_entry_id == element.entry_id);
        ul.appendChild(li);
        if(child_exist.length > 0){
            li.classList.add('clickable-task');
        }else{
            li.classList.add('no-child');
        }
        li.innerHTML= "+"+element.name;
        li.setAttribute('data-id' , element.entry_id);
        li.setAttribute('id' , "clickable_task" + element.entry_id);
    });
}

//function to remove child
function removechild(child) {
    child[0].remove();
}