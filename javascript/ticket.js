const my_tickets = document.querySelector('#select_option .my_tickets')
const my_tickets_click = document.querySelector('#select_option .my_tickets.click')
const agent_tickets = document.querySelector('#select_option .agent_tickets')
const general_tickets = document.querySelector('#select_option .general_tickets')
const track = document.querySelector('.far fa-star')
const department_filter = document.getElementById('department_filter_name')
const priority_filter = document.getElementById('priority_filter_name')
const status_filter = document.getElementById('status_filter_name')
const filters = document.getElementsByClassName('filter')
const header = document.querySelector('.page_header h1')

if (my_tickets_click) {
    for (const filter of filters) {
        filter.addEventListener('change', fill_my_tickets)
    }
}

function update_filter_event_handler(isAgent, isGeneral) {
    for (const filter of filters) {
        filter.removeEventListener('change', fill_my_tickets)
        filter.removeEventListener('change', fill_agent_tickets)
        filter.removeEventListener('change', fill_general_tickets)

        if (isAgent) {
            filter.addEventListener('change', fill_agent_tickets)
        }
        if (isGeneral) {
            filter.addEventListener('change', fill_general_tickets)
        }
        else {
            filter.addEventListener('change', fill_my_tickets)
        }
    }
}

if (header) {
    if (header.textContent === 'Tickets') fill_my_tickets()
}


if (my_tickets) {
    my_tickets.addEventListener('click', fill_my_tickets)
}

async function fill_my_tickets() {
    update_filter_event_handler(false, false)
    const response = await fetch('../api/api_my_tickets.php?department=' + department_filter.value +
        '&priority=' + priority_filter.value + '&status=' + status_filter.value)
    const tickets = await response.json()
    if (my_tickets) my_tickets.style = 'background-color: #a9a9a9'
    if (agent_tickets) agent_tickets.style = 'background-color: none'
    if (general_tickets) general_tickets.style = 'background-color: none'
    filters[0].classList.remove('hide')
    filters[2].classList.remove('hide')

    const ul = document.querySelector('.page_content_department .page_nav .page_list_content')
    ul.innerHTML = ''
    for (const ticket of tickets) {
        const response_comments = await fetch('../api/api_get_comments.php?ticket_id=' + ticket.id)
        const comments = await response_comments.json()
        const last_comment = comments[comments.length - 1]
        const li = document.createElement('li')
        li.classList.add('element_of_list')
        if (comments.length > 0 && last_comment.userID !== ticket.client) li.classList.add('pending')
        const link = document.createElement('a')
        link.classList.add('element_link')
        link.href = '../pages/ticket.php?ticket_id=' + ticket.id
        const ticket_id = document.createElement('span')
        ticket_id.classList.add('element_id')
        ticket_id.textContent = '#' + ticket.id
        const title = document.createElement('span')
        title.classList.add('title')
        title.textContent = ticket.title
        const status = document.createElement('span')
        const star = document.createElement('span')
        if (ticket.clientTrack === 0) {
            star.innerHTML = '<i class="far fa-star"></i>'
            star.setAttribute('onclick', 'trackTicket('+ticket.id+');')
        }
        else {
            star.innerHTML = '<i class="fa-solid fa-star"></i>'
            star.setAttribute('onclick', 'unTrackTicket('+ticket.id+', false);')
            li.classList.add('tracking')
        }
        status.classList.add('status')
        let status_str = ""
        if (ticket.status === 1) status_str = 'Open'
        if (ticket.status === 2) status_str = 'Pending'
        if (ticket.status === 3) status_str = 'Closed'
        status.textContent = status_str
        const priority = document.createElement('span')
        priority.classList.add('priority')
        let priority_str = ""
        if (ticket.priority === 1) {
            priority_str = 'High'
            priority.style.color = 'red'
        }
        if (ticket.priority === 2) {
            priority_str = 'Medium'
            priority.style.color = 'rgb(192, 192, 94)'
        }
        if (ticket.priority === 3) {
            priority_str = 'Low'
            priority.style.color = 'green'
        }
        priority.textContent = priority_str
        link.appendChild(ticket_id)
        link.appendChild(title)
        link.appendChild(status)
        link.appendChild(priority)
        li.appendChild(link)
        li.appendChild(star)
        ul.appendChild(li)
    }
}

if (agent_tickets) {
    agent_tickets.addEventListener('click', fill_agent_tickets)
}

async function fill_agent_tickets() {
    update_filter_event_handler(true, false) 
    const response = await fetch('../api/api_agent_tickets.php?department=' + department_filter.value +
    '&priority=' + priority_filter.value + '&status=' + status_filter.value)
    const tickets = await response.json()
    const ul = document.querySelector('.page_content_department .page_nav .page_list_content')
    ul.innerHTML = ''

    my_tickets.style = 'background-color: none'
    agent_tickets.style = 'background-color: #a9a9a9'
    general_tickets.style = 'background-color: none'
    filters[0].classList.remove('hide')
    filters[2].classList.remove('hide')
    my_tickets.classList.remove('click')

    for (const ticket of tickets) {
        const response_comments = await fetch('../api/api_get_comments.php?ticket_id=' + ticket.id)
        const comments = await response_comments.json()
        const last_comment = comments[comments.length - 1]
        const li = document.createElement('li')
        li.classList.add('element_of_list')
        if (comments.length > 0 && last_comment.userID !== ticket.agent) li.classList.add('pending')
        const link = document.createElement('a')
        link.classList.add('element_link')
        link.href = '../pages/ticket.php?ticket_id=' + ticket.id
        const ticket_id = document.createElement('span')
        ticket_id.classList.add('element_id')
        ticket_id.textContent = '#' + ticket.id
        const title = document.createElement('span')
        title.classList.add('title')
        title.textContent = ticket.title
        const status = document.createElement('span')
        const star = document.createElement('span')
        if (ticket.agentTrack === 0) {
            star.innerHTML = '<i class="far fa-star"></i>'
            star.setAttribute('onclick', 'trackTicket('+ticket.id+', true);')
        }
        else {
            star.innerHTML = '<i class="fa-solid fa-star"></i>'
            star.setAttribute('onclick', 'unTrackTicket('+ticket.id+');')
            li.classList.add('tracking')
        }
        status.classList.add('status')
        let status_str = ""
        if (ticket.status === 1) status_str = 'Open'
        if (ticket.status === 2) status_str = 'Pending'
        if (ticket.status === 3) status_str = 'Closed'
        status.textContent = status_str
        const priority = document.createElement('span')
        priority.classList.add('priority')
        let priority_str = ""
        if (ticket.priority === 1) {
            priority_str = 'High'
            priority.style.color = 'red'
        }
        if (ticket.priority === 2) {
            priority_str = 'Medium'
            priority.style.color = 'rgb(192, 192, 94)'
        }
        if (ticket.priority === 3) {
            priority_str = 'Low'
            priority.style.color = 'green'
        }
        priority.textContent = priority_str
        link.appendChild(ticket_id)
        link.appendChild(title)
        link.appendChild(status)
        link.appendChild(priority)
        li.appendChild(link)
        li.appendChild(star)
        ul.appendChild(li)
    }
}

if (general_tickets) {
    general_tickets.addEventListener('click', fill_general_tickets)
}

async function fill_general_tickets() {
    update_filter_event_handler(false, true) 
    const response = await fetch('../api/api_general_tickets.php?priority=' + priority_filter.value)
    const tickets = await response.json()

    const ul = document.querySelector('.page_content_department .page_nav .page_list_content')
    ul.innerHTML = ''

    my_tickets.style = 'background-color: none'
    agent_tickets.style = 'background-color: none'
    general_tickets.style = 'background-color: #a9a9a9'
    filters[0].classList.add('hide')
    filters[2].classList.add('hide')
    my_tickets.classList.remove('click')

    for (const ticket of tickets) {
        const li = document.createElement('li')
        li.classList.add('element_of_list')
        const link = document.createElement('a')
        link.classList.add('element_link')
        link.href = '../pages/ticket.php?ticket_id=' + ticket.id
        const ticket_id = document.createElement('span')
        ticket_id.classList.add('element_id')
        ticket_id.textContent = '#' + ticket.id
        const title = document.createElement('span')
        title.classList.add('title')
        title.textContent = ticket.title
        const status = document.createElement('span')
        status.classList.add('status')
        status.textContent = 'Open'
        const priority = document.createElement('span')
        priority.classList.add('priority')
        let priority_str = ""
        if (ticket.priority === 1) {
            priority_str = 'High'
            priority.style.color = 'red'
        }
        if (ticket.priority === 2) {
            priority_str = 'Medium'
            priority.style.color = 'rgb(192, 192, 94)'
        }
        if (ticket.priority === 3) {
            priority_str = 'Low'
            priority.style.color = 'green'
        }
        priority.textContent = priority_str
        link.appendChild(ticket_id)
        link.appendChild(title)
        link.appendChild(status)
        link.appendChild(priority)
        li.appendChild(link)
        ul.appendChild(li)
    }
}

async function trackTicket(ticket) {
    let request = new XMLHttpRequest()
    request.addEventListener('load', finishTrack)
    request.open('post', '../api/api_track_ticket.php', true)
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    request.send(
        encodeForAjax({
            ticket: ticket
        })
    )
}


async function finishTrack(event) {
    event.preventDefault()
    const tickets = JSON.parse(this.responseText)
    const ul = document.querySelector('.page_content_department .page_nav .page_list_content')
    ul.innerHTML = ''

    $role = tickets[0][0].role

    for (const ticket of tickets[1]) {
        const response_comments = await fetch('../api/api_get_comments.php?ticket_id=' + ticket.id)
        const comments = await response_comments.json()
        const last_comment = comments[comments.length - 1]
        const li = document.createElement('li')
        li.classList.add('element_of_list')
        const link = document.createElement('a')
        link.classList.add('element_link')
        link.href = '../pages/ticket.php?ticket_id=' + ticket.id
        const ticket_id = document.createElement('span')
        ticket_id.classList.add('element_id')
        ticket_id.textContent = '#' + ticket.id
        const title = document.createElement('span')
        title.classList.add('title')
        title.textContent = ticket.title
        const status = document.createElement('span')
        const star = document.createElement('span')

        // client -> 1  // agent -> 2
        if ($role === 1) {
            if (comments.length > 0 && last_comment.userID !== ticket.client) li.classList.add('pending')
            if (ticket.clientTrack === 0) {
                star.innerHTML = '<i class="far fa-star"></i>'
                star.setAttribute('onclick', 'trackTicket('+ticket.id+');')

            }
            else {
                star.innerHTML = '<i class="fa-solid fa-star"></i>'
                star.setAttribute('onclick', 'unTrackTicket('+ticket.id+');')
                li.classList.add('tracking')
            }
        }
        else {
            if (comments.length > 0 && last_comment.userID !== ticket.agent) li.classList.add('pending')
            if (ticket.agentTrack === 0) {
                star.innerHTML = '<i class="far fa-star"></i>'
                star.setAttribute('onclick', 'trackTicket('+ticket.id+');')
            }
            else {
                star.innerHTML = '<i class="fa-solid fa-star"></i>'
                star.setAttribute('onclick', 'unTrackTicket('+ticket.id+');')
                li.classList.add('tracking')
            }
        }
        status.classList.add('status')
        let status_str = ""
        if (ticket.status === 1) status_str = 'Open'
        if (ticket.status === 2) status_str = 'Pending'
        if (ticket.status === 3) status_str = 'Closed'
        status.textContent = status_str
        const priority = document.createElement('span')
        priority.classList.add('priority')
        let priority_str = ""
        if (ticket.priority === 1) {
            priority_str = 'High'
            priority.style.color = 'red'
        }
        if (ticket.priority === 2) {
            priority_str = 'Medium'
            priority.style.color = 'rgb(192, 192, 94)'
        }
        if (ticket.priority === 3) {
            priority_str = 'Low'
            priority.style.color = 'green'
        }
        priority.textContent = priority_str
        link.appendChild(ticket_id)
        link.appendChild(title)
        link.appendChild(status)
        link.appendChild(priority)
        li.appendChild(link)
        li.appendChild(star)
        ul.appendChild(li) 
    }
} 

async function unTrackTicket(ticket) {
    let request = new XMLHttpRequest()
    request.addEventListener('load', finishUnTrack)
    request.open('post', '../api/api_untrack_ticket.php', true)
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    request.send(
        encodeForAjax({
            ticket: ticket
        })
    )
}

async function finishUnTrack(event) {
    event.preventDefault()
    const tickets = JSON.parse(this.responseText)
    const ul = document.querySelector('.page_content_department .page_nav .page_list_content')
    ul.innerHTML = ''

    $role = tickets[0][0].role

    for (const ticket of tickets[1]) {
        const response_comments = await fetch('../api/api_get_comments.php?ticket_id=' + ticket.id)
        const comments = await response_comments.json()
        const last_comment = comments[comments.length - 1]
        const li = document.createElement('li')
        li.classList.add('element_of_list')
        const link = document.createElement('a')
        link.classList.add('element_link')
        link.href = '../pages/ticket.php?ticket_id=' + ticket.id
        const ticket_id = document.createElement('span')
        ticket_id.classList.add('element_id')
        ticket_id.textContent = '#' + ticket.id
        const title = document.createElement('span')
        title.classList.add('title')
        title.textContent = ticket.title
        const status = document.createElement('span')
        const star = document.createElement('span')
        if ($role === 1) {
            if (comments.length > 0 && last_comment.userID !== ticket.client) li.classList.add('pending')
            if (ticket.clientTrack === 0) {
                star.innerHTML = '<i class="far fa-star"></i>'
                star.setAttribute('onclick', 'trackTicket('+ticket.id+');')
            }
            else {
                star.innerHTML = '<i class="fa-solid fa-star"></i>'
                star.setAttribute('onclick', 'unTrackTicket('+ticket.id+');')
                li.classList.add('tracking')
            }
        }
        else {
            if (comments.length > 0 && last_comment.userID !== ticket.agent) li.classList.add('pending')
            if (ticket.agentTrack === 0) {
                star.innerHTML = '<i class="far fa-star"></i>'
                star.setAttribute('onclick', 'trackTicket('+ticket.id+');')
            }
            else {
                star.innerHTML = '<i class="fa-solid fa-star"></i>'
                star.setAttribute('onclick', 'unTrackTicket('+ticket.id+');')
                li.classList.add('tracking')
            }
        }
        status.classList.add('status')
        let status_str = ""
        if (ticket.status === 1) status_str = 'Open'
        if (ticket.status === 2) status_str = 'Pending'
        if (ticket.status === 3) status_str = 'Closed'
        status.textContent = status_str
        const priority = document.createElement('span')
        priority.classList.add('priority')
        let priority_str = ""
        if (ticket.priority === 1) {
            priority_str = 'High'
            priority.style.color = 'red'
        }
        if (ticket.priority === 2) {
            priority_str = 'Medium'
            priority.style.color = 'rgb(192, 192, 94)'
        }
        if (ticket.priority === 3) {
            priority_str = 'Low'
            priority.style.color = 'green'
        }
        priority.textContent = priority_str
        link.appendChild(ticket_id)
        link.appendChild(title)
        link.appendChild(status)
        link.appendChild(priority)
        li.appendChild(link)
        li.appendChild(star)
        ul.appendChild(li)
    }
}

