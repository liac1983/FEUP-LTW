function encodeForAjax(data) {
    return Object.keys(data)
        .map(function(k) {
            return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
        })
        .join("&")
}

async function addMessage() {
    const content = document.querySelector('.add_comment .form textarea')
    let contentValue = content.value
    const queryString = window.location.search
    const urlParams = new URLSearchParams(queryString)
    const ticket_id = parseInt(urlParams.get('ticket_id'), 10)
    let request = new XMLHttpRequest()
    request.addEventListener('load', finishAddMessage)
    request.open('post', '../api/api_add_comment.php', true)
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    request.send(
        encodeForAjax({
            ticket_id: ticket_id,
            description: contentValue
        })
    )
}

function finishAddMessage(event) {
    event.preventDefault()
    const comments = JSON.parse(this.responseText)
    const comment_section = document.querySelector('.page_comment_section .comment_section')
    comment_section.innerHTML = ''
    
    for (const comment of comments) {
        const div = document.createElement('div')
        div.classList.add('comment')
        const content = document.createElement('p')
        content.textContent = comment.content
        const footer = document.createElement('footer')
        footer.classList.add('comment_author')
        const user = document.createElement('span')
        user.classList.add('username_comment_author')
        user.textContent = comment.username
        const time = document.createElement('time')
        time.textContent = comment.date
        footer.appendChild(user)
        footer.appendChild(time)
        div.appendChild(content)
        div.appendChild(footer)
        comment_section.appendChild(div)
    } 

    // clears textarea
    const content = document.querySelector('.add_comment .form textarea')
    content.value = ''
}
