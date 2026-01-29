function filterLeaderboard(sortType) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', sortType);

    fetch(url.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        document.querySelector('.leaderboard').innerHTML = doc.querySelector('.leaderboard').innerHTML;
        document.querySelector('#leaderboard-list').innerHTML = doc.querySelector('#leaderboard-list').innerHTML;
        document.querySelector('.rank-card').innerHTML = doc.querySelector('.rank-card').innerHTML;

        window.history.pushState({ path: url.href }, '', url.href);
        
        document.querySelectorAll('.board-button').forEach(btn => {
            btn.classList.remove('active');
            if(btn.getAttribute('onclick').includes(sortType)) btn.classList.add('active');
        });
    })
    .catch(error => console.error('Error:', error));
}