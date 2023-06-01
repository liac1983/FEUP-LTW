const add_agent_department = document.querySelector('.form input[name=new_agent]')
const department_name = document.querySelector('.add_content .form  input[name=name]')
const remove_agent_department = document.querySelector('.form input[name=agent_remove]')

if (add_agent_department && department_name) {
    add_agent_department.addEventListener('input', async function() {
        const response = await fetch('../api/api_add_agent_department.php?search=' + add_agent_department.value + '&department_name=' + department_name.value)
        const agents = await response.json()

        const list = document.querySelector('.form datalist[id=agents_new]')
        list.innerHTML = ''

        for (const agent of agents) {
            const option = document.createElement('option')
            option.value = agent.Username
            list.appendChild(option)
        }
    })
} 

if (remove_agent_department && department_name) {
    remove_agent_department.addEventListener('input', async function() {
        const response = await fetch('../api/api_remove_agents_department.php?search=' + remove_agent_department.value + '&department_name=' + department_name.value)
        const agents = await response.json()

        const list = document.querySelector('.form datalist[id=agents_remove]')
        list.innerHTML = ''

        for (const agent of agents) {
            const option = document.createElement('option')
            option.value = agent.Username
            list.appendChild(option)
        }
    })
}
