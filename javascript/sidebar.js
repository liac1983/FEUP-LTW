const links = document.querySelectorAll('.sidebar_link')

if (links) {
    for (const link of links) {
        if (window.location.pathname === link.pathname) {
            link.parentElement.style.backgroundColor = '#a9a9a9'
        }

        if (link === links[0] && (window.location.pathname === '/pages/ticket.php' || 
                window.location.pathname === '/pages/edit_ticket.php' ||
                window.location.pathname === '/pages/ticket_changes.php' ||
                window.location.pathname === '/pages/add_ticket.php')) {
            link.parentElement.style.backgroundColor = '#a9a9a9'
        }

        if (link === links[1] && (window.location.pathname === '/pages/department.php' ||
                window.location.pathname === '/pages/edit_department.php' ||
                window.location.pathname === '/pages/add_department.php')) {
            link.parentElement.style.backgroundColor = '#a9a9a9'
        }

        if (link === links[2] && (window.location.pathname === '/pages/edit_faq.php' ||
                window.location.pathname === '/pages/add_faq.php')) {
            link.parentElement.style.backgroundColor = '#a9a9a9'
        }
    }
}