var game103AccountID = null;
var game103AccountUsername = null;
var accountErrorMessage = "Sorry, an error has occured";
var accountSuccessfullyUpdatedMessage = "Your account has been successfully updated";
var accountLoggedInMessage = "You have logged in";
var accountLoggedOutMessage = "You have logged out";
var accountEmailSentMessage = "Please check your inbox for an email to update your account";
var accountNoAccountFoundMessage = "Sorry, no account was found";
var accountSuccessfullyMergedMessage = "Your account was successfully merged";

document.addEventListener('DOMContentLoaded', function() {
    
    var id = document.querySelector("#id");
    
    // We'll either have them login or login automatically if
    // it is a password recovery
    if( !id ) {
        accountEnableButtons();
    }
    else {
        accountloginOnLoad();
    }

} );

// Disable buttons from being clicked
function accountDisableButtons() {

    var login = document.querySelector("#login");
    var logout = document.querySelector("#logout");
    var update = document.querySelector("#update");
    var recover = document.querySelector("#recover");
    var merge = document.querySelector("#merge");

    login.onclick = null;
    logout.onclick = null;
    update.onclick = null;
    recover.onclick = null;
    merge.onclick = null;

    login.style.opacity = "0.5";
    logout.style.opacity = "0.5";
    update.style.opacity = "0.5";
    recover.style.opacity = "0.5";
    merge.style.opacity = "0.5";

}

// Login based on the IDs placed in the page (they will be there on a recovery)
function accountloginOnLoad() {
    accountDisableButtons();

    var id = document.querySelector("#id").value;
    var id2 = document.querySelector("#id2").value;

    // We try both ids
    // id2 is more likely since that's what matches immediately after token issueing
    accountLoginAccountID( id2, function() { accountLoginAccountID(id, function() {accountDisplayMessage(accountNoAccountFoundMessage, "failure")}) } );
}

// Login to an account with an id
// (Basically validate the id, get the username and what not)
function accountLoginAccountID(id, callback) {
    accountGetUsername( id, function(response) {
        try {
            var json = JSON.parse(response);
            if( json.id ) {
                game103AccountID = id;
                game103AccountUsername = json.username;
                document.querySelector("#username").value = json.username;
                document.querySelector("#email").value = json.email;
                accountFillInUsername();
                accountDisplayMessage(accountLoggedInMessage, "success");
            }
            else {
                callback();
            }
        }
        catch(err) {
            accountDisplayMessage(accountErrorMessage, "failure");
        }
        accountEnableButtons();
    }, function() { accountDisplayMessage(accountErrorMessage, "failure"); accountEnableButtons(); });
}

// Fill in the username
function accountFillInUsername() {
    var usernamePlaceholders = document.querySelectorAll(".current-account");

    for( var i=0; i<usernamePlaceholders.length; i++ ) {
        usernamePlaceholders[i].innerText = game103AccountUsername;
    }
}

// Enable buttons that should be allowed to be clicked
function accountEnableButtons() {

    var login = document.querySelector("#login");
    var logout = document.querySelector("#logout");
    var update = document.querySelector("#update");
    var recover = document.querySelector("#recover");
    var mergeSection = document.querySelector("#merge-section");
    var merge = document.querySelector("#merge");

    login.style.opacity = 1;
    logout.style.opacity = 1;
    update.style.opacity = 1;
    recover.style.opacity = 1;
    merge.style.opacity = 1;

    if( game103AccountID ) {
        login.style.display = 'none';
        recover.style.display = 'none';
        update.style.display = 'block';
        logout.style.display = 'block';
        mergeSection.style.display = 'block';

        // Merge
        merge.onclick = function() {
            accountHideMessage();

            // Sanity check - there should always be an account ID
            if( game103AccountID ) {
                var mergeUsername = document.querySelector("#username-merge").value;
                var mergePassword = document.querySelector("#password-merge").value;

                accountDisableButtons();
                accountMerge( game103AccountID, mergeUsername, mergePassword, function(response) {
                    try {
                        var json = JSON.parse(response);
                        if( json.status == "success" ) {
                            document.querySelector("#username-merge").value = "";
                            document.querySelector("#password-merge").value = "";
                            accountDisplayMessage(accountSuccessfullyMergedMessage, json.status);
                        }
                        else {
                            accountDisplayMessage(json.message, json.status);
                        }
                    }
                    catch(err) {
                        accountDisplayMessage(accountErrorMessage, "failure");
                    }
                    accountEnableButtons();
                }, function() { accountDisplayMessage(accountErrorMessage, "failure"); accountEnableButtons(); });
            }
        }

        // Update
        update.onclick = function() {
            accountHideMessage();
            
            // Sanity check - there should always be an account ID
            if( game103AccountID ) {
                var username = document.querySelector("#username").value;
                var password = document.querySelector("#password").value;
                var email = document.querySelector("#email").value;

                accountDisableButtons();
                accountUpdateUser( game103AccountID, username, password, email, function(response) {
                    try {
                        var json = JSON.parse(response);
                        if( json.status == "success" ) {
                            accountDisplayMessage(accountSuccessfullyUpdatedMessage, json.status);
                        }
                        else {
                            accountDisplayMessage(json.message, json.status);
                        }
                    }
                    catch(err) {
                        accountDisplayMessage(accountErrorMessage, "failure");
                    }
                    accountEnableButtons();
                }, function() { accountDisplayMessage(accountErrorMessage, "failure"); accountEnableButtons(); });
            }

        };

        // Log out
        logout.onclick = function() {
            document.querySelector("#username").value = "";
            document.querySelector("#password").value = "";
            document.querySelector("#email").value = "";
            accountDisplayMessage(accountLoggedOutMessage, "success");
            game103AccountID = null;
            game103AccountID = null;
            accountEnableButtons();
        }
    }
    else {
        update.style.display = 'none';
        logout.style.display = 'none';
        mergeSection.style.display = 'none';
        login.style.display = 'block';
        recover.style.display = 'block';

        // Log in
        login.onclick = function() {
            accountHideMessage();
    
            var username = document.querySelector("#username").value;
            var password = document.querySelector("#password").value;
    
            accountDisableButtons();
            accountLoginUser( username, password, function(response) {
                try {
                    var json = JSON.parse(response);
                    if( json.status == "success" ) {
                        game103AccountID = json.id;
                        game103AccountUsername = username;
                        accountFillInUsername();
                        accountDisplayMessage(accountLoggedInMessage, json.status);
                    }
                    else {
                        accountDisplayMessage(json.message, json.status);
                    }
                }
                catch(err) {
                    accountDisplayMessage(accountErrorMessage, "failure");
                }
                accountEnableButtons();
            }, function() { accountDisplayMessage(accountErrorMessage, "failure"); accountEnableButtons(); });
        }

        // Recover
        recover.onclick = function() {
            accountHideMessage();

            var email = document.querySelector("#email").value;

            accountDisableButtons();
            accountRecoverAccount( email ,function(response) {
                try {
                    var json = JSON.parse(response);
                    if( json.status == "success" ) {
                        accountDisplayMessage(accountEmailSentMessage, json.status);
                    }
                    else {
                        accountDisplayMessage(json.message, json.status);
                    }
                }
                catch(err) {
                    accountDisplayMessage(accountErrorMessage, "failure");
                }
                accountEnableButtons();
            }, function() { accountDisplayMessage(accountErrorMessage, "failure"); accountEnableButtons(); });
        }
    }

}

// Display a message
function accountDisplayMessage(message, type) {
    var messageContainer = document.querySelector(".admin-error-message, .admin-success-message");
    if( type == "success" ) {
        messageContainer.classList.remove("admin-error-message");
        messageContainer.classList.add("admin-success-message");
    }
    else {
        messageContainer.classList.remove("admin-success-message");
        messageContainer.classList.add("admin-error-message");
    }
    messageContainer.innerHTML = message;
    messageContainer.style.display = 'block';
}

// Hide the message
function accountHideMessage() {
    document.querySelector(".admin-error-message, .admin-success-message").style.display = 'none';
}

// Update a user
function accountUpdateUser(id, username, password, email, callback, errorCallback) {
    accountMakeRequest("POST", "/ws/scores/update_user.php", { id: id, username: username, password: password, email: email }, callback, errorCallback );
}

// Login a user
function accountLoginUser(username, password, callback, errorCallback) {
    accountMakeRequest("POST", "/ws/scores/login.php", { username: username, password: password }, callback, errorCallback);
}

// Recover a user's account
function accountRecoverAccount(email, callback, errorCallback) {
    accountMakeRequest("POST", "/ws/scores/recover_account.php", { email: email }, callback, errorCallback);
}

// Merge a user's account
function accountMerge(id, mergeUsername, mergePassword, callback, errorCallback) {
    accountMakeRequest("POST", "/ws/scores/merge_accounts.php", { id: id, username: mergeUsername, password: mergePassword }, callback, errorCallback);
}

// Get the most up to date username for our user (they may have change it elsewhere)
function accountGetUsername(id, callback, errorCallback) {
    accountMakeRequest("GET", "/ws/scores/get_user.php", { id: id }, callback, errorCallback);
}

// Make a request
function accountMakeRequest(type, url, parameters, callback, errorCallback) {
    var parameterKeys = Object.keys(parameters);
    var parameterArray = [];
    for( var i=0; i<parameterKeys.length; i++ ) {
        parameterArray.push( parameterKeys[i] + "=" + parameters[parameterKeys[i]] );
    }

    if( type == "GET" && parameterKeys.length ) {
        url = url + "?" + parameterArray.join("&");
    }

    var xhttp = new XMLHttpRequest();
    xhttp.open(type, url, true);

    if( type == "POST" && parameterKeys.length ) {
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    } 

    xhttp.onreadystatechange = function() {
        if( this.readyState == 4 ) {
            if( this.status == 200 ) {
                if( callback ) { callback(this.responseText); }
            }
            else {
                if( errorCallback ) { errorCallback(); }
            }
        }
    }    
    if( type == "POST" && parameterArray.length ) {
        xhttp.send( parameterArray.join("&") );
    }
    else {
        xhttp.send();
    }
}