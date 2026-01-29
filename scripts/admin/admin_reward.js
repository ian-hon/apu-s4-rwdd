const REWARDContainer = document.getElementById('reward-container');
let currentFilter = 'ALL';
let rewardsCache = [];

const getIsActive = (val) => val == 1 || val === true || val === 'true' || val === '1';

function createCardHTML(reward) {
    const isActive = getIsActive(reward.active);
    const statusLabel = isActive ? 'ACTIVE' : 'ENDED';
    const btnText = isActive ? 'Discontinue' : 'Reactivate';
    const statusClass = isActive ? 'active' : 'ended';

    return `
        <div class="card" data-id="${reward.ID}">                
            <div class="product-title-row">
                <div>
                    <h2>${reward.title}</h2>
                    <div class="tags">
                        <span class="type-tag">${reward.category || 'Eco'}</span>
                        <span class="pts-tag">${reward.price} pts</span>
                    </div>
                </div>
                <div class="badges">
                    <span class="badge ${statusClass}">${statusLabel}</span>
                </div>
            </div>

            <div class="image-container">
                <img src="${reward.media}" alt="${reward.title}">
            </div>

            <div class="description">
                <span class="descrip">${reward.description || 'Eco'}</span>
            </div>

            <div class="controls">
                <div class="control-row">
                    <span>Stock: ${reward.remaining}</span>
                    <div class="stock-count"> 
                        <span class="edit-icon" style="cursor:pointer">✎</span>
                    </div>
                </div>
            </div>

            <button class="state-btn ${statusClass}">
                ${btnText}
            </button>
        </div>`;
}

/**
 * 2. Updated Event Listener (Now handles both the Button and the Pencil)
 */
REWARDContainer.addEventListener('click', async (e) => {
    const card = e.target.closest('.card');
    if (!card) return;

    const rewardId = card.dataset.id;

    if (e.target.classList.contains('edit-icon')) {
        openEditPopup(rewardId);
        return; 
    }

    if (e.target.classList.contains('state-btn')) {
        const item = rewardsCache.find(r => r.ID == rewardId);
        const currentActive = getIsActive(item.active);
        const nextState = currentActive ? 0 : 1; 

        if (await updateDatabase(rewardId, { active: nextState })) {
            item.active = nextState;
            renderUI();
        }
    }
});

/**
 * 3. Function for NEW rewards
 */
//
function openPopup() {
    const form = document.querySelector('.reward-form');
    form.reset();
    document.querySelector('#popup-header h3').innerText = "Create New Reward";
    form.querySelector('.submit-btn').textContent = "Create Reward";
    
    const idInput = form.querySelector('[name="reward_id"]');
    if (idInput) idInput.value = "";
    
    form.querySelector('[name="reward_image"]').required = true;

    document.getElementById("overlay").classList.add("active");
    document.getElementById("parent").classList.add("blur");
}

function openEditPopup(id) {
    const item = rewardsCache.find(r => r.ID == id);
    if (!item) return;

    const form = document.querySelector('.reward-form');
    
    document.querySelector('#popup-header h3').innerText = "Edit Reward";
    form.querySelector('.submit-btn').textContent = "Confirm";

    form.querySelector('[name="title"]').value = item.title;
    form.querySelector('[name="category"]').value = item.category; // Uses database 'category'
    form.querySelector('[name="description"]').value = item.description || '';
    form.querySelector('[name="points"]').value = item.price;
    form.querySelector('[name="stock"]').value = item.remaining;
    
    let idInput = form.querySelector('[name="reward_id"]');
    if (!idInput) {
        idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'reward_id';
        form.appendChild(idInput);
    }
    idInput.value = item.ID;

    form.querySelector('[name="reward_image"]').required = false;

    document.getElementById("overlay").classList.add("active");
    document.getElementById("parent").classList.add("blur");
}

function closePopup() {
    const overlay = document.getElementById("overlay");
    const parent = document.getElementById("parent");
    if (overlay) overlay.classList.remove("active");
    if (parent) parent.classList.remove("blur"); 
}
/**
 * Filtering Logic
 */
function changeFilter(status, event) {
    const cards = document.querySelectorAll('#statistics span, .active-ended span');
    cards.forEach(card => card.classList.remove('active-filter'));

    if (event && event.currentTarget) {
        event.currentTarget.classList.add('active-filter');
    }

    switch (status) {
        case 'all':    currentFilter = 'ALL'; break;
        case 'low':    currentFilter = 'LOW_STOCK'; break;
        case 'active': currentFilter = 'ACTIVE'; break;
        case 'ended':  currentFilter = 'ENDED'; break;
    }

    renderUI();
}

function renderUI() {
    const filtered = rewardsCache.filter(r => {
        const isActive = getIsActive(r.active);
        if (currentFilter === 'LOW_STOCK') return isActive && parseInt(r.remaining) < 10; 
        if (currentFilter === 'ACTIVE') return isActive;
        if (currentFilter === 'ENDED') return !isActive;
        return true;
    });

    if (REWARDContainer) {
        REWARDContainer.innerHTML = filtered.length 
            ? filtered.map(createCardHTML).join('') 
            : `<p class="no-data">No rewards found for this filter.</p>`;
    }
}

/**
 * Data Syncing
 */
async function fetchAndRender() {
    try {
        const response = await fetch('api/reward/fetch_all.php');
        const dataObject = await response.json();
        rewardsCache = Object.values(dataObject);
        renderUI();
    } catch (error) {
        console.error("Fetch error:", error);
    }
}

REWARDContainer.addEventListener('click', async (e) => {
    const card = e.target.closest('.card');
    if (!card || !e.target.classList.contains('state-btn')) return;

    const rewardId = card.dataset.id;
    const item = rewardsCache.find(r => r.ID == rewardId);
    const currentActive = getIsActive(item.active);
    const nextState = currentActive ? 0 : 1; 

    if (await updateDatabase(rewardId, { active: nextState })) {
        item.active = nextState;
        renderUI();
    }
});

async function updateDatabase(id, data) {
    const params = new URLSearchParams({ id, ...data });
    try {
        const response = await fetch(`api/reward/update.php?${params.toString()}`);
        return response.ok; 
    } catch (error) {
        console.error("Update failed:", error);
        return false;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const totalRewardTab = document.getElementById('total-reward');
    if (totalRewardTab) {
        setTimeout(() => changeFilter('all', { currentTarget: totalRewardTab }), 100);
    }
});

fetchAndRender();
