const ticket_option = document.querySelector('#select_option .tickets_button')
const department = document.querySelector('#page_container .page_header h1');
const agents_option = document.querySelector('#page_container .agents_button')
const filter_page_department = document.getElementById('filter_page_department')
const department_filters = document.getElementsByClassName('filter')

function department_event_handler() {
    for (const filter of department_filters) {
        filter.addEventListener('change', fill_department_tickets)
    }
}

if (ticket_option && department && department_filters) {
    department_event_handler()
    ticket_option.addEventListener('click', fill_department_tickets)
}

async function fill_department_tickets() {
    const respose = await fetch('../api/api_department_tickets.php?department=' + department.innerHTML + 
    '&priority=' + priority_filter.value + '&status=' + status_filter.value)
    const tickets = await respose.json()

    agents_option.style = 'background-color: none'
    ticket_option.style = 'background-color: #a9a9a9'
    agents_option.classList.remove('click')
    filter_page_department.classList.add('open')

    
    const ul = document.querySelector('.page_content_department .page_nav .page_list_content')
    ul.innerHTML = ''

    for (const ticket of tickets) {
        const li = document.createElement('li')
        li.classList.add('element_of_list')
        if (!ticket.agent) li.classList.add('new_ticket')
        const link = document.createElement('a')
        link.classList.add('element_link')
        link.href = '../pages/ticket.php?ticket_id=' + ticket.id
        const ticket_id = document.createElement('span')
        ticket_id.classList.add('element_id')
        ticket_id.textContent = '#' + ticket.id
        const title = document.createElement('span')
        title.classList.add('title')
        title.textContent = ticket.title
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
        link.appendChild(priority)
        li.appendChild(link)
        ul.appendChild(li)
    }
}

if (agents_option && department) {
    agents_option.addEventListener('click', async function() {
        const response = await fetch('../api/api_department_agents.php?department=' + department.innerHTML)
        const agents = await response.json()
        agents_option.style = 'background-color: #a9a9a9'
        ticket_option.style = 'background-color: none'
        filter_page_department.classList.remove('open')
        const ul = document.querySelector('.page_content_department .page_nav .page_list_content')
        ul.innerHTML = ''

        for (const agent of agents) {
            const li = document.createElement('li')
            li.classList.add('element_of_list')
            const link = document.createElement('a')
            link.classList.add('element_link')
            link.href = '../pages/profile.php?user_id=' + agent.id
            const img = document.createElement('img')
            img.classList.add('pfp')
            img.src = '../images/' + agent.pfp
            const div = document.createElement('div')
            div.classList.add('agent_info')
            const username = document.createElement('span')
            username.classList.add('username_element')
            username.textContent = agent.username
            div.appendChild(img)
            div.appendChild(username)
            link.appendChild(div)
            li.appendChild(link)
            ul.appendChild(li)
        } 
    })
}
