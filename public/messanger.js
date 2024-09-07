const chat = document.getElementById('chat');
const messagesDisplayer = document.getElementById('messages-displayer-77');
const sendButton = document.getElementById('send-message-messanger');

let target = "";
let latest = "";

if(target) {
    messagesDisplayer.style.display = "flex"
}

// write message to db
sendButton.addEventListener('click', async () => {
    const messageInput = document.getElementById('message-input-sender');
    let req = await fetch('/ha/public/messages.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            messageInput:messageInput.value,
            target: target
        })
    });
    let res = await req.json()
    // if(res.data.sender_email == "target") {
    //     messagesDisplayer.innerHTML += `
    //         <li class="state-message-receive">
    //             <p>${res.data.content}</p>
    //         </li>
    //     `
    // } else {
    //     messagesDisplayer.innerHTML += `
    //         <li class="state-message-sent">
    //             <p>${res.data.content}</p>
    //         </li>
    //     `
    // }
    messagesDisplayer.scrollTop = messagesDisplayer.scrollHeight;
});

//update target email and get latest messages
function updateTargetEmail(targetEmail) {
    if(target) return
   // return  messagesDisplayer.classList.add("display-messages-displayer-77")
    target = targetEmail
        fetch('/ha/public/messages.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ get_all_messages: target })
        })
        .then(response => response.json())
        .then(data => {
            data["target email"].forEach(msg => {
                if(msg.sender_email == target) {
                    messagesDisplayer.innerHTML += `
                        <li style='display: flex;
                            color:#fff;
                            justify-content: flex-end;
                            text-align: right;
                            align-items: center;
                            background: #3b8ad4;
                            padding:4px;
                            border-radius: 10px;
                            margin:4px;' >
                        <p>${msg.content}</p>
                    </li>
                    `
                } else {
                    messagesDisplayer.innerHTML += `
                        <li style='color:#fff;
                        display: flex;
                        justify-contentflex-start
                        align-items: center;
                        background: #ababab;
                        padding:4px;
                        border-radius: 10px;
                        margin:4px;'
                       >
                        <p>${msg.content}</p>
                    </li>
                    `
                }
            })

        })
        .catch(error => console.error('Error:', error));
    
}

function poll_mesages_by_target(){
    if(target !== "") {
        fetch('/ha/public/messages.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ poll_by_limit: target })
        })
        .then(response => response.json())
        .then(data => {
            if(data["target email"].content === latest) return
            latest = data["target email"].content
            // console.log(data["target email"])
            if(data["target email"].sender_email === target) {
                messagesDisplayer.innerHTML += `
                    <li style='display: flex;
                            color:#fff;
                            justify-content: flex-end;
                            text-align: right;
                            align-items: center;
                            background: #3b8ad4;
                            padding:4px;
                            border-radius: 10px;
                            margin:4px;' >
                        <p>${data["target email"].content}</p>
                    </li>
                `
            } else {
                messagesDisplayer.innerHTML += `
                    <li style='color:#fff;
                        display: flex;
                        justify-contentflex-start
                        align-items: center;
                        background: #ababab;
                        padding:4px;
                        border-radius: 10px;
                        margin:4px;'
                       >
                        <p>${data["target email"].content}</p>
                    </li>
                `
            }
            messagesDisplayer.scrollTop = messagesDisplayer.scrollHeight;

        })
        .catch(error => console.error('Error:', error));
    }
}

setInterval(poll_mesages_by_target, 1000)