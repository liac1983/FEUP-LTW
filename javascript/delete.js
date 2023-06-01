const delete_account_button = document.getElementById('delete_account')
const delete_faq_button = document.getElementById('delete_faq')
const delete_department_button = document.getElementById('delete_department')

if (delete_account_button) {
    delete_account_button.addEventListener('click', async function(event) {
        if (!confirm("Are tou sure you want to delete your account?")) event.preventDefault()
    })
} 

if (delete_faq_button) {
    delete_faq_button.addEventListener('click', async function(event) {
        if (!confirm("Are you sure you want to delete this FAQ?")) event.preventDefault()
    })
} 

if (delete_department_button) {
    delete_department_button.addEventListener('click', async function(event) {
        if (!confirm("Are you sure you want to delete this department?")) event.preventDefault()
    })
}
