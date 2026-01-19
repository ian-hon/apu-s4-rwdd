(function () {
    const container = document.querySelector("#card-container");
    let rewards = [];

    function fetchRewards() {
        fetch('/api/reward/fetch_all.php')
            .then(res => res.json())
            .then(data => {
                rewards = data;
                render();
            });
    }

    function render() {
        let html = '';
        rewards.forEach(r => {
            html += `
            <div class="card">
                <div class="card-header">
                    <div class="user-info">
                        <div class="avatar">${r.title.substring(0, 2).toUpperCase()}</div>
                        <div class="user-details">
                            <span class="username">Admin</span>
                            <span class="timestamp">${r.date_created}</span>
                        </div>
                    </div>
                    <div class="badges">
                        ${r.is_featured == 1 ? '<span class="badge featured">FEATURED</span>' : ''}
                        <span class="badge ${r.status.toLowerCase()}">${r.status.toUpperCase()}</span>
                    </div>
                </div>
                <div class="product-title-row">
                    <h2>${r.title}</h2>
                    <div class="tags">
                        <span class="type-tag">${r.category.toUpperCase()}</span>
                        <span class="pts-tag">${r.points} pts</span>
                    </div>
                </div>
                <div class="image-container">
                    <img src="${r.image_path || './assets/bag.png'}">
                </div>
                <div class="controls">
                    <div class="control-row">
                        <span>Stock:</span>
                        <div class="stock-count"> 
                            <h5 class="${r.stock < 5 ? 'text-danger' : ''}">${r.stock}</h5>
                            <span class="edit-icon">✎</span>
                        </div>
                    </div>
                    <div class="control-row">
                        <span>Auto-discontinue when out of stock</span>
                        <label class="switch">
                            <input type="checkbox" ${r.auto_discontinue == 1 ? 'checked' : ''} disabled>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
                <button class="discontinue-btn" onclick="updateStatus(${r.id})">Discontinue</button>
            </div>`;
        });
        container.innerHTML = html;
    }

    fetchRewards();
})();