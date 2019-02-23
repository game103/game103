function addControl(event) {
    event.preventDefault();
    var br = document.createElement("br");
    br.setAttribute('id', 'br_' + controlCount);
    document.getElementById('controls_area').appendChild(br);
    document.getElementById('controls_area').appendChild(createNewKeyList());
    document.getElementById('controls_area').appendChild(createNewActionList());
    controlCount ++;
    document.getElementById('remove_control').style.display = 'inline-block';
}
function removeControl(event) {
    event.preventDefault();
    var keyList = document.getElementById('key_' + (controlCount - 1));
    var actionList = document.getElementById('action_' + (controlCount - 1));
    var br = document.getElementById('br_' + (controlCount - 1));
    document.getElementById('controls_area').removeChild(keyList);
    document.getElementById('controls_area').removeChild(actionList);
    document.getElementById('controls_area').removeChild(br);
    controlCount --;
    if(controlCount == 0) {
        document.getElementById('remove_control').style.display = 'none';
    }
}
function createNewKeyList() {
    var select = document.createElement("select");
    select.setAttribute('name', 'key_' + controlCount);
    select.setAttribute('id', 'key_' + controlCount);
    select.setAttribute('class', 'controls_list');
    for(var i = 0; i < controlsKeys.length; i++) {
        var key = controlsKeys[i];
        var option = document.createElement("option");
        option.text = key;
        option.value = controlsIds[i];
        select.appendChild(option);
    }
    return select;
}
function createNewActionList() {
    var select = document.createElement("select");
    select.setAttribute('name', 'action_' + controlCount);
    select.setAttribute('id', 'action_' + controlCount);
    select.setAttribute('class', 'actions_list');
    for(var i = 0; i < actionsNames.length; i++) {
        var action = actionsNames[i];
        var option = document.createElement("option");
        option.text = action;
        option.value = actionsIds[i];
        select.appendChild(option);
    }
    return select;
}
function addAControl(event) {
    event.preventDefault();
    var controlValue = document.getElementById('control_field').value;
    if(controlValue && !controlsKeys.includes(controlValue)) {
        var controlsLists = document.getElementsByClassName('controls_list');
        controlsKeys.push(controlValue);
        controlsIds.push(controlValue);
        for(var i = 0; i < controlsLists.length; i ++) {
            var option = document.createElement("option");
            option.text =controlValue;
            // In the php, you can check to see if value isn't a number. if its not, insert it.
            option.value = controlValue;
            controlsLists[i].add(option);
        }
    }
    document.getElementById('control_field').value = '';
}
function addAnAction(event) {
    event.preventDefault();
    var actionValue = document.getElementById('action_field').value;
    if(actionValue && !actionsNames.includes(actionValue)) {
        var actionsLists = document.getElementsByClassName('actions_list');
        actionsNames.push(actionValue);
        actionsIds.push(actionValue);
        for(var i = 0; i < actionsLists.length; i ++) {
            var option = document.createElement("option");
            option.text =actionValue;
            // In the php, you can check to see if value isn't a number. if its not, insert it.
            option.value = actionValue;
            actionsLists[i].add(option);
        }
    }
    document.getElementById('action_field').value = '';
}

document.addEventListener('DOMContentLoaded', function() {
    if( cat1 ) {
        document.getElementById('cat1').value = cat1;
    }
    if( cat2 ) {
        document.getElementById('cat2').value = cat2;
    }
    if( type ) {
        document.getElementById('type').value = type;
    }
    
    if( currentKeys ) {
        for(var i=0; i<currentKeys.length; i++) {
            addControl(document.createEvent('Event'));
            var keyList = document.getElementById('key_' + (controlCount - 1));
            var actionList = document.getElementById('action_' + (controlCount - 1));
            if( currentKeys[i] ) {
                keyList.value = currentKeys[i];
            }
            if( currentActions[i] ) {
                actionList.value = currentActions[i];
            }
        }
    }
}, false );