  const openDialogButton = document.getElementById('send-money-button');
  const closeDialogButton = document.getElementById('closeDialog');
  const dialog = document.getElementById('stripe-dialog');
    // const overlay = document.getElementById('overlay');
const AddFriendButton = document.getElementById('add-friend-button');
const AddFriendForm = document.getElementById('add-friend-form');

 // Function to open the dialog
    openDialogButton.onclick = function(e) {
      e.preventDefault()
        // dialog.style.display = 'block';
        dialog.classList.toggle('open-dialog');
        // overlay.style.display = 'block';
    };

   // Function to close the dialog
    closeDialogButton.onclick = function(e) {
      e.preventDefault()
        dialog.classList.toggle('open-dialog');
        // overlay.style.display = 'none';
    };


AddFriendButton.onClick = function(){
  return 0
}

form.addEventListener('submit', function(event) {
    event.preventDefault();
})