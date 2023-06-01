const filter_users = document.querySelector('#filter_page input')

if (filter_users) {
    filter_users.addEventListener('input', async function() {
        const response = await fetch('../api/api_get_users.php?search=' + filter_users.value)
        const users = await response.json()
        const ul = document.querySelector('.page_list_content')
        ul.innerHTML = ''
    
        for (const user of users) {
            const li = document.createElement('li')
            li.classList.add('element_of_list')
            const link = document.createElement('a')
            link.classList.add('element_link')
            link.href = '../pages/profile.php?user_id=' + user.id
            const div = document.createElement('div')
            div.classList.add('agent_info')
            const img = document.createElement('img')
            img.classList.add('pfp')
            img.src = '../images/' + user.pfp
            img.alt = 'pfp_user'
            const span = document.createElement('span')
            span.classList.add('username_element')
            span.textContent = user.username
            div.appendChild(img)
            div.appendChild(span)
            link.appendChild(div)
            li.appendChild(link)
            ul.appendChild(li)
        } 
    })
}