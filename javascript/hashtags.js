const searchHashtags = document.querySelector('.form input[name=hashtag]')
if (searchHashtags) {
    searchHashtags.addEventListener('input', async function() {
        const response = await fetch('../api/api_hashtags.php?search=' + this.value)
        const hashtags = await response.json()

        const list = document.querySelector('.form datalist[id=hashtags]')
        list.innerHTML = ''

        for (const hashtag of hashtags) {
            const option = document.createElement('option')
            option.value = hashtag.name
            list.appendChild(option)
        }
    })
}

