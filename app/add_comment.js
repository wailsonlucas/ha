let commentForm = document.getElementById("comment-form")
let commentsContainer = document.getElementById("comments-container")
let commentInput = document.getElementById("comment-input")
let pidInput = document.getElementById("post_id")
let uidInput = document.getElementById("uid")
let likeBtn = document.getElementById("reactions")
let likesCountPlaceholder = document.getElementById("reactions")



commentForm.addEventListener('submit', async function(e) {
	e.preventDefault()
	try {
		if(!commentInput.value) return console.error('empty field')
   
        let req = await fetch('../app/add_comment.php', {
        	method: "POST",
        	headers: {
        		"Content-Type":"application/json"
        	},
        	body: JSON.stringify({
        		comment: commentInput.value,
				pid: pidInput.value,
				uid: uidInput.value
        	})
        })

	    let res = await req.json()
	    // console.log(res)
    	commentsContainer.innerHTML += `
           <div class="comment_child"
           	style="animation: backgroundChange 2s;">
               <div class="img-container">
                 <a href="#" class="pull-left">
                        <img src="assets/images/users/user-4.jpg" alt="" class="img-circle">
                    </a>
               </div>
               <div class="comment-body">
                    <p>${res.comment}</p>
               </div>
            </div>
    	`

	}catch(error){
		console.error(error)
	}
	
});

likeBtn.addEventListener('click', async function(e) {
	e.preventDefault()
	try {
		// let likesCount = parseInt(e.currentTarget.innerText.trim())
		let postId = parseInt(e.currentTarget.getAttribute("data-pid").trim())
        let req = await fetch('../app/increment_likes.php', {
        	method: "POST",
        	headers: {
        		"Content-Type":"application/json"
        	},
        	body:JSON.stringify({
        		postId: postId
        		})
    	   })

    	let res = await req.json()

    		let old_count = likesCountPlaceholder.innerText 
    		let old_count_to_int = parseInt(old_count.trim())
    	if(res.liked) {
    		likesCountPlaceholder.innerText = old_count_to_int+1
    	} else {
    		likesCountPlaceholder.innerText = old_count_to_int-1
    	}
    

	}catch(error){
		console.error(error)
	}
	
});

